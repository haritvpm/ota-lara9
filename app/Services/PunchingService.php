<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Auth;
use App\GovtCalendar;
use App\PunchingTrace;
use App\Punching;
use App\Calender;
use App\Employee;
use App\User;
use App\Services\EmployeeService;

class PunchingService
{
    // private EmployeeService $employeeService;
    // public function __construct(EmployeeService $employeeService)
    // {
    //     $this->employeeService = $employeeService;
    // }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function fetchSuccessAttendance($reportdate)
    {
        
        $apikey =  env('AEBAS_KEY');
        $offset = 0;

        $islocal_test = false;
        $count =  $islocal_test  ? 10000 : 500;

        // should be in format 2024-02-11
        if (!$this->validateDate($reportdate)) {
            //date is in dd-mm-yy 
            $reportdate = Carbon::createFromFormat(config('app.date_format'), $reportdate)->format('Y-m-d');
        }

        $insertedcount = 0;
        $pen_to_aadhaarid = [];

        $govtcalender = $this->getGovtCalender($reportdate); 
        if( $govtcalender->success_attendance_fetched){
            $offset = $govtcalender->success_attendance_rows_fetched; 
        
        }

        for (;; $offset += $count) {


            $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/attendance/offset/{$offset}/count/{$count}/reportdate/{$reportdate}/apikey/{$apikey}";

            if ($islocal_test) $url = 'http://localhost:3000/data';

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
            ])->get($url);

            if ($response->status() !== 200) {
               // \Session::flash('message-danger',  $response->status());
               Log::error('Response for fetchSuccessAttendance:' . $response->status());
               break;
            }
            $jsonData = $response->json();
            $jsonData = $jsonData['successattendance'];
            // dd($jsonData);

            //now this is ugly repeated calls to db. lets optimize later
            for ($i = 0; $i < count($jsonData); $i++) {
                $dataItem = $jsonData[$i];
                $dateIn = null;
                $dateOut = null;
                $intime = null;
                $outtime = null;
                $this->processPunchingData($jsonData[$i], $dateIn, $intime, $dateOut, $outtime);
                //user may punchin but not punchout. so treat these separately

                //org_emp_code from api can be klaid, mobilenumber or empty. 
                $org_emp_code = $dataItem['org_emp_code'];
                $attendanceId = $dataItem['emp_id'];
                if ('0000-00-00 00:00:00' == $dataItem['in_time'])  $dataItem['in_time'] = null;
                if ('0000-00-00 00:00:00' == $dataItem['out_time'])  $dataItem['out_time'] = null;

                //date-aadhaarid-pen composite keys wont work if we give null. so something to pen
                //punchout date can be diff to punchin date, not sure
                $punch = null;
                if ($dateIn && $intime && $dateOut && $outtime && ($dateIn === $dateOut)) {
                    $matchThese = ['aadhaarid' => $attendanceId, 'date' => $dateIn];
                    $vals = [
                        'punch_in' => $intime, 'punch_out' => $outtime, 'pen' => '-', 'punchin_from_aebas' => true,
                        'punchout_from_aebas' =>     true, 'in_device' => $dataItem['in_device_id'], 'in_time' => $dataItem['in_time'],
                        'out_device' =>  $dataItem['out_device_id'], 'out_time' => $dataItem['out_time'],
                        'at_type' => $dataItem['at_type']
                    ];

                    if ($org_emp_code != '')  $vals['pen'] = $org_emp_code;


                    $punch = Punching::updateOrCreate($matchThese, $vals);
                } else
                if ($dateIn && $intime) {
                    // $date = Carbon::createFromFormat('Y-m-d', $dateIn)->format(config('app.date_format'));
                    //org_emp_code can be null. since empty can cause unique constraint violations, dont allow
                    $matchThese = ['aadhaarid' => $attendanceId, 'date' => $dateIn];
                    $vals = [
                        'punch_in' => $intime, 'pen' => '-', 'punchin_from_aebas' => true, 'punchout_from_aebas' => false,
                        'in_device' => $dataItem['in_device_id'], 'in_time' => $dataItem['in_time'], 'out_device' =>  $dataItem['out_device_id'], 'out_time' => $dataItem['out_time'], 'at_type' => $dataItem['at_type']
                    ];

                    if ($org_emp_code != '') $vals['pen'] = $org_emp_code;

                    $punch = Punching::updateOrCreate($matchThese, $vals);
                } else
                if ($dateOut && $outtime) {
                    // $date = Carbon::createFromFormat('Y-m-d', $dateOut)->format(config('app.date_format'));
                    $matchThese = ['aadhaarid' => $attendanceId, 'date' => $dateOut];
                    $vals = [
                        'punch_out' => $outtime, 'pen' => '-', 'punchin_from_aebas' => false, 'punchout_from_aebas' => true,
                        'in_device' => $dataItem['in_device_id'], 'in_time' => $dataItem['in_time'],
                        'out_device' => $dataItem['out_device_id'], 'out_time' => $dataItem['out_time'], 'at_type' => $dataItem['at_type']
                    ];
                    if ($org_emp_code != '')  $vals['pen'] = $org_emp_code;

                    $punch = Punching::updateOrCreate($matchThese, $vals);
                } else {
                    \Log::info('found punching fetch edge case');
                }
                $insertedcount++;


                if ($org_emp_code != '' && $org_emp_code != null) {
                    $pen_to_aadhaarid[$org_emp_code] = $attendanceId;
                }

            }
            //if reached end of data, break
            if (count($jsonData) <  $count) {

                break;
            }
        }

        $calenderdate = Calender::where('date', $reportdate)->first();
        if ($calenderdate && $insertedcount) {
            $calenderdate->update(['punching' => 'AEBAS']);
        }

        $totalrowsindb  = PunchingTrace::where('date',$reportdate)->count(); 
        
        $govtcalender->update([

            'success_attendance_fetched' =>  $calender->success_attendance_fetched+1,
            'success_attendance_rows_fetched' => $totalrowsindb,
            'success_attendance_lastfetchtime' => Carbon::now(),

        ]);

        if($insertedcount){
            $this->makePunchingRegister($reportdate);
        }
        
        if (count($pen_to_aadhaarid)) {
            //Update our employee db with aadhaarid from api
            //since org_emp_code can be empty or even mobile number, make sure this is our pen
            $emps = Employee::select('id', 'pen', 'aadhaarid')
                ->wherein('pen', array_keys($pen_to_aadhaarid))->get();
            foreach ($emps->chunk(1000) as $chunk) {
                $cases = [];
                $ids = [];
                $params_aadhaarid = [];

                foreach ($chunk as $emp) {
                    if (!$emp->aadhaarid || $emp->aadhaarid == '') { //only if it is not set already
                        if (array_key_exists($emp->pen, $pen_to_aadhaarid)) {
                            $cases[] = "WHEN '{$emp->pen}' then ?";
                            $params_aadhaarid[] = $pen_to_aadhaarid[$emp->pen];
                            $ids[] = $emp->id;
                        }
                    }
                }

                $ids = implode(',', $ids);
                $cases = implode(' ', $cases);

                if (!empty($ids)) {
                    //dd( $params_aadhaarid);
                    \DB::update("UPDATE employees SET `aadhaarid` = CASE `pen` {$cases} END WHERE `id` in ({$ids})", $params_aadhaarid);
                }
            }
        }

        return $insertedcount;
    }

    private function processPunchingData($dataItem, &$dateIn, &$intime,  &$dateOut, &$outtime)
    {
        $in_time = $dataItem['in_time'] ?? null;

        //this is like "2024-02-07 07:13:47". so separate date and time
        if ($in_time && strlen($in_time) && !str_contains($in_time, "0000-00-00")) {
            $datetime = explode(' ', $in_time);
            // "out_time": "0000-00-00 00:00:00",

            $dateIn = $datetime[0];
            //$date = Carbon::createFromFormat('Y-m-d', $request->query('reportdate'))->format('Y-m-d');
            $intime = date('H:i', floor(strtotime($datetime[1]) / 60) * 60);
        }

        $out_time = $dataItem['out_time'] ?? null;
        //this is like "2024-02-07 07:13:47". so separate date and time
        if ($out_time && strlen($out_time) && !str_contains($out_time, "0000-00-00")) {

            $datetime = explode(' ', $out_time);
            $dateOut = $datetime[0];

            //if punchin, round down else round up
            $outtime = date('H:i', ceil(strtotime($datetime[1]) / 60) * 60);
        }
    }

    private function makePunchingRegister($reportdate )
    {
        $success_punchs = Punching::where('date',$reportdate )->get();

        foreach ($success_punchs as $dataItem) {
        /*
        'date',
        'employee_id',
        'punchin_id',
        'duration',
        'flexi',
        'grace_min',
        'extra_min',
    */
            //find employee 
            $emp = Employee::where('aadhaarid',  $dataItem->emp_id)->first();
            if(!$emp) continue;
            $duration = "0";

            if($dataItem['at_type'] == 'C'){
                $datein = Carbon::parse($dataItem->in_time);
                $dateout = Carbon::parse($dataItem->out_time);
                $duration = $dateout->diff($datein)->format('%H:%i:%s');
            }
        }

    }

    public function fetchApi( $apinum, $reportdate )
    {
        $apikey =  env('AEBAS_KEY');
        $offset = 0;
        $count = 2000;


        $returnkey = "successattendance";
        // should be in format 2024-02-11
        $reportdate = Carbon::createFromFormat(config('app.date_format'), $reportdate)->format('Y-m-d');

        $data = [];

        for ($offset = 0;; $offset += $count) {

            //5
            $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/attendance/offset/{$offset}/count/{$count}/reportdate/{$reportdate}/apikey/{$apikey}";

            if (1 == $apinum) {
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/employee/offset/{$offset}/count/{$count}/apikey/{$apikey}";
                $returnkey = "employee";
            } else
            if ($apinum == 6) {
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/trace/offset/{$offset}/count/{$count}/reportdate/{$reportdate}/apikey/{$apikey}";
                $returnkey = "attendancetrace";
            } else if ($apinum == 4) {
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/attendancetodaytrace/offset/{$offset}/count/{$count}/apikey/{$apikey}";
                $returnkey = "attendancetodaytrace";
            } else if ($apinum == 3) {
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/attendancetoday/offset/{$offset}/count/{$count}/apikey/{$apikey}";
                $returnkey = "SuccessAttendanceToday";
            }  else if ($apinum == 9) {
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/orgleave/offset/{$offset}/count/{$count}/apikey/{$apikey}";
                $returnkey = "leavedetails";
            } else if ($apinum == 11) {
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/orgleavebydate/offset/{$offset}/count/{$count}/reportdate/{$reportdate}/apikey/{$apikey}";
                $returnkey = "leavedetails";
            } else if ($apinum == 13) {
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/attendancewithdetails/reportdate/{$reportdate}/offset/{$offset}/count/{$count}/apikey/{$apikey}";
                $returnkey = "AttendanceWithdetails";
            } else if ($apinum == 14) {
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/tracewithdetails/reportdate/{$reportdate}/offset/{$offset}/count/{$count}/apikey/{$apikey}";
                $returnkey = "AttendanceTraceWithdetails";
            } else if ($apinum == (14 + 5)) {
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/orgshift/{$apikey}";
                $returnkey = "orgshift";
            }else if ($apinum == (14 + 9)) {
                
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/detailsbydistrictid/districtid/00581/offset/{$offset}/count/{$count}/apikey/{$apikey}";
                $returnkey = "DeviceDetailsDistrictId";
            }


            // $url = 'http://localhost:3000/data';
            \Log::info($url);
            $response = Http::withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
            ])->get($url);


            if ($response->status() !== 200) {
              //  \Session::flash('message-danger',  $response->status());
                Log::error('Response for fetchAPI:' . $response->status());
                return ;
                //break;
            }
            $jsonData = $response->json();
            $jsonData = $jsonData ? $jsonData[$returnkey] : [];
            $data = array_merge($data, $jsonData);


            //if reached end of data, break
            if (count($jsonData) < $count) {
                break;
            }
        }

        return $data;
    }

    private function getGovtCalender($reportdate)
    {
        $calender = GovtCalendar::where('date',$reportdate)->first();
        if($calender){
            if( $calender->attendance_today_trace_fetched){
                $offset = $calender->attendance_today_trace_rows_fetched; 
            }
        } else {
            $calender = new GovtCalendar();
            $calender->date = $reportdate;

            $calender->attendance_today_trace_fetched = 0;
            $calender->attendance_today_trace_rows_fetched = 0;


            $calender->success_attendance_fetched = 0;
            $calender->success_attendance_rows_fetched = 0;

            $calender->save();
        }

        return  $calender;
    }
  
    public function fetchTodayTrace($fetchdate = null)
    {
       $apikey =  env('AEBAS_KEY');

       $offset = 0;
       $count = 2000; //make it to 500 in prod

      
        // should be in format 2024-02-11
        $reportdate = Carbon::now()->format('Y-m-d'); //today
        $returnkey = "attendancetodaytrace";
        if($fetchdate){
          $returnkey = "attendancetrace";
        // should be in format 2024-02-11
            if (!$this->validateDate($fetchdate)) {
            //date is in dd-mm-yy 
             $reportdate = Carbon::createFromFormat(config('app.date_format'), $fetchdate)->format('Y-m-d');
            }
        }


        //check calender for this date's count.

        $govtcalender = $this->getGovtCalender($reportdate); 
        if( $govtcalender->attendance_today_trace_fetched){
            $offset = $govtcalender->attendance_today_trace_rows_fetched; 
        
        }
       
        $insertedcount = 0;

        for (; ; $offset += $count) {

            $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/attendancetodaytrace/offset/{$offset}/count/{$count}/apikey/{$apikey}";
            
            if($fetchdate){
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/trace/offset/{$offset}/count/{$count}/reportdate/{$reportdate}/apikey/{$apikey}";
            }
            // $url = 'http://localhost:3000/data';
            \Log::info($url);
            $response = Http::withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
            ])->get($url);


            if ($response->status() !== 200) {
              //  \Session::flash('message-danger',  $response->status());
                Log::error('Response for fetchAPI:' . $response->status());
                return ;
                //break;
            }
            $jsonData = $response->json();
            $jsonData = $jsonData ? $jsonData[$returnkey] : [];

            $datatoinsert = [];
            for ($i = 0; $i < count($jsonData); $i++) {
                //ignore errors
                //if(  $jsonData['attendance_type'] != 'E' && $jsonData['auth_status'] == 'Y'  )
                {
                 assert($reportdate === $jsonData[$i]['att_date']);
                 $datatoinsert[] = $this->mapTraceToDBFields($reportdate, $jsonData[$i]);
                }
            }

            //All databases except SQL Server require the columns in the second argument of the upsert method to have a "primary" or "unique" index. In addition, the MySQL database driver ignores the second argument of the upsert method and always uses the "primary" and "unique" indexes of the table to detect existing records.
            PunchingTrace::upsert($datatoinsert, ['aadhaarid', 'att_date', 'att_time']);      

            
            $insertedcount += count($jsonData);
          
          

            //if reached end of data, break
            if (count($jsonData) < $count) {

                break;
            }
            
        }
        
        \Log::info('Newly fetched rows:' . $insertedcount);

        $totalrowsindb  = PunchingTrace::where('att_date',$reportdate)->count(); 
        
        $govtcalender->update([

            'attendance_today_trace_fetched' =>  $govtcalender->attendance_today_trace_fetched+1,
            'attendance_today_trace_rows_fetched' => $totalrowsindb,// $calender->attendance_today_trace_rows_fetched+$insertedcount,
            'attendancetodaytrace_lastfetchtime' => Carbon::now(),

        ]);
        
    }

    private function mapTraceToDBFields($traceItem)
    {
            
        $trace = [];
        $trace['aadhaarid']= $traceItem['emp_id'];
        $trace['device']= $traceItem['device_id'];
        $trace['attendance_type']= $traceItem['attendance_type'];
        $trace['auth_status']= $traceItem['auth_status'];
        $trace['err_code']= $traceItem['err_code'];
        $trace['att_date']= $traceItem['att_date'];
        $trace['att_time']= $traceItem['att_time'];
       
        return $trace;
       // $trace->save();
    }
}