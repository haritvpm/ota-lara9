<?php

namespace App\Http\Controllers\Admin;

use App\Punching;
use App\Calender;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
//use Yajra\DataTables\DataTables;
//use Laracasts\Utilities\JavaScript\JavaScriptFacade;
use JavaScript;
use Carbon\Carbon;
use Auth;
use Yajra\DataTables\DataTables;


use Illuminate\Support\Facades\Log; 

class PunchingsController extends Controller
{
    
    //the args are set in route file web.php
    public function ajaxgetpunchsittings($session, $datefrom, $dateto, $pen, $aadhaarid)
    {
        // Log::info($datefrom);

    $dateformatwithoutime = '!'.config('app.date_format'); //! to set time to zero
    $datefrom = Carbon::createFromFormat($dateformatwithoutime, $datefrom)->format('Y-m-d');
    $dateto = Carbon::createFromFormat($dateformatwithoutime, $dateto)->format('Y-m-d');

   // Log::info($session);
    // Calender::where('session_id', $session->id)
     //get all sitting days between these two days
    $sittingsInRange = Calender::with('session')
                            ->whereHas('session', function($query)  use ($session) { 
                                    $query->where('name', $session);
                            })                         
                            ->where('date', '>=', $datefrom)
                            ->where('date', '<=', $dateto)
                            ->where('day_type','Sitting day')->get(['date','day_type',"punching"]);

//     Log::info($sittingsInRange);
    
    
    $tmp = strpos($pen, '-');
    if(false !== $tmp){
        $pen = substr($pen, 0, $tmp);
    }
    // Log::info($pen);
    // Log::info($aadhaarid);
    $sittingsWithPunchok = 0; 
    $sittingsWithNoPunching = 0; 
    $dates = [];
    foreach ($sittingsInRange as $day) {

        $date = Carbon::createFromFormat($dateformatwithoutime, $day->date)->format('Y-m-d');
        
        $data = [
            'applicable' => true,
            'date' =>  $day->date, 
            'punchin' => "",
            'punchout' => "",
        ];

        //ignore pen if data from aebas and ignore aadhhar if data is from us saving
        $query =  Punching::where('date',$date);
        // $query->when( $day->punching == 'MANUALENTRY' && $pen  && strlen($pen) >= 5, function ($q)  use ($pen) {
        //     return $q->where('pen',$pen);
        // });
        $query->when( /*  $day->punching == 'AEBAS' &&  */ $aadhaarid   && strlen($aadhaarid) >= 8, function ($q)  use ($aadhaarid){
            return $q->where('aadhaarid',$aadhaarid);
        });
        
        //->wherenotnull('punch_in') //prevent if only one column is available
        //->wherenotnull('punch_out') 

        $temp = $query->first(); 
       
        if($temp ){
           $sittingsWithPunchok++; 
           $data['punchin'] =  $temp['punch_in'];
           $data['punchout'] =  $temp['punch_out'];
        }

        //check if user has entered first OT for that day.
        $sit = \App\Overtime::with('form')
                    ->wherehas( 'form', function($q) use( $date){
                        $q->where( 'overtime_slot' , 'Multi' )
                        ->where( 'duty_date', $date );
                    })->where('pen', $pen )
                    ->where('slots','like','%First%')
                    ->first(); 


        if( $day->punching !== 'AEBAS' ){
        
            $sittingsWithNoPunching++; 
            $data['applicable'] =  false; //whether to count
            $data['ot'] =  $sit ? "Entered in that day's form" : "Enter in OT Form";//'Punching excused Use DutyForm to enter for the day',
        } 

        //may be it was nopunching intitially, and user entered sit in multi and we changed the day type to aebas 
        //in that case, do not include it in count
        if($sit) {  //user has already entered sitting for that day
            $sittingsWithNoPunching++; 
            $data['applicable'] =  false; //whether to count
            $data['ot'] =   "Entered in that day's form";//'Punching excused Use DutyForm to enter for the day',
        
        }


        $dates[] = $data;

     }
               
   
    return [
            'sittingsWithPunchok' => $sittingsWithPunchok,
            // 'sittingsWithNoPunching' => $sittingsWithNoPunching,
            'sittingsInRange' => $sittingsInRange->count(),
            'dates' =>  $dates,
           ];
          
    }


    //the args are set in route file web.php
    public function ajaxgetpunchtimes($date, $pen, $aadhaarid)
    {


    $tmp = strpos($pen, '-');
    if(false !== $tmp){
        $pen = substr($pen, 0, $tmp);
    }

    $date = Carbon::createFromFormat(config('app.date_format'), $date)->format('Y-m-d');

    $day = Calender::where('date',$date)->first();
       
    $query =  Punching::where('date',$date);
    // $query->when ($day->punching == 'MANUALENTRY' && $pen != '' && strlen($pen) >= 5, function ($q)  use ($pen) {
    //     return $q->where('pen',$pen);
    // });
     $query->when( /* $day->punching == 'AEBAS' &&  */ strlen($aadhaarid) >= 8, function ($q)  use ($aadhaarid){
        return $q->where('aadhaarid',$aadhaarid);
    });
    
           // ->wherenotnull('punch_in') //prevent if only one column is available
           //  ->wherenotnull('punch_out') 
    $temp = $query->first(); 
    
    if($temp){
      //  Log::info($temp);
       
   
      return [
            'punchin' => $temp['punch_in'],
            'punchout' => $temp['punch_out'],
            'creator' => $temp['creator'],
            'aadhaarid' => $temp['aadhaarid'],
            'punchout_from_aebas'=> $temp['punchout_from_aebas'],
            'punchin_from_aebas'=> $temp['punchin_from_aebas'],

            'id' => $temp['id']
        ];
    } else return [];
        
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public function index(Request $request)
    {
        // abort_if(Gate::denies('punching_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (request()->ajax()) {
            //Log::info($request);
            $query = Punching::query();

             
            if ($request->filled('datefilter')){
                $date =  $request->query('datefilter');
            
                if(!$this->validateDate( $date, 'Y-m-d')){
                    $date = Carbon::createFromFormat(config('app.date_format'), $date)->format('Y-m-d');
                }
                    
                $query = $query->where( 'date',$date);
             }
				

           // $str_datefilter = '&datefilter='.$datefilter;
        
            
           $query = $query->orderBy('date', 'DESC');


            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
           
      
             
            $table->addColumn('actions', '&nbsp;')->rawColumns(['actions']);;
            $table->editColumn('actions', function ($row) {
                $gateKey  = 'punching_';
                $routeKey = 'admin.punchings';

                return view('actionsTemplate', compact('row', 'gateKey', 'routeKey'));
            });


            return $table->make(true);
        }

        return view('admin.punchings.index');


        
    }

    public function create()
    {
        // abort_if(Gate::denies('punching_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

       // $forms = PunchingForm::pluck('creator', 'id')->prepend(trans('global.pleaseSelect'), '');
       
        return view('admin.punchings.create');
    }

    public function store(Request $request)
    {
        $punching = Punching::create($request->all());
       
        return redirect()->route('admin.punchings.index');
    }

    public function edit(Punching $punching)
    {


        // abort_if(Gate::denies('punching_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

      //  $forms = PunchingForm::pluck('creator', 'id')->prepend(trans('global.pleaseSelect'), '');

      //  $punching->load('form');

      return view('admin.punchings.edit', compact('punching'));
      //return view('admin.punchings.edit', compact('punching'));
    }

    public function update(Request $request, Punching $punching)
    {
    
       
        $punching->update(
            [
                'pen' => $request['pen'],
                'punch_in' => !$punching->punchin_from_aebas ? $request['punch_in'] : $punching->punch_in,
                'punch_out' =>  !$punching->punchout_from_aebas ?  $request['punch_out'] : $punching->punch_out,
            ]

        );
        $reportdate = Carbon::createFromFormat('Y-m-d', $punching->date)->format(config('app.date_format'));

        return redirect()->route('admin.punchings.index', ['datefilter'=> $reportdate]);
    }

    public function show(Punching $punching)
    {
        // abort_if(Gate::denies('punching_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

      
        return view('admin.punchings.show', compact('punching'));
    }

    public function destroy(Punching $punching)
    {
        // abort_if(Gate::denies('punching_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $punching->delete();

        return back();
    }
    private function processPunchingData($dataItem, &$dateIn, &$intime,  &$dateOut, &$outtime){
        $in_time = $dataItem['in_time'] ?? null;
       
        //this is like "2024-02-07 07:13:47". so separate date and time
        if($in_time && strlen($in_time) && !str_contains($in_time,"0000-00-00")){
            $datetime = explode(' ', $in_time);
           // "out_time": "0000-00-00 00:00:00",
            
            $dateIn = $datetime[0];
            //$date = Carbon::createFromFormat('Y-m-d', $request->query('reportdate'))->format('Y-m-d');
            $intime = date('H:i', floor(strtotime($datetime[1])/60)*60);
        
         
        }

        $out_time = $dataItem['out_time'] ?? null;
        //this is like "2024-02-07 07:13:47". so separate date and time
        if($out_time && strlen($out_time) && !str_contains($out_time,"0000-00-00")){
            
            $datetime = explode(' ', $out_time);
            $dateOut = $datetime[0];
                      
            //if punchin, round down else round up
            $outtime = date('H:i', ceil(strtotime($datetime[1])/60)*60);
         
        }
    }

    public function fetch($reportdate)
    {
        $apikey =  env('AEBAS_KEY');
        $offset = 0;
        $count = 500;
      
        // should be in format 2024-02-11
        $reportdate = Carbon::createFromFormat(config('app.date_format'), $reportdate)->format('Y-m-d');
     
       
       //if this date is not in calender, do nothing
        $calenderdate = Calender::where('date', $reportdate )->first();
        if(! $calenderdate ){
            \Session::flash('message-success', 'No such date in calender'  );
            return view('admin.punchings.index');

        }

     

        $insertedcount = 0;
        $pen_to_aadhaarid = [];
        for ($offset=0;   ; $offset += $count ) { 
            
            
            $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/attendance/offset/{$offset}/count/{$count}/reportdate/{$reportdate}/apikey/{$apikey}";
            // $url = 'http://localhost:3000/data';
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
            ])->get($url);

            if($response->status() !== 200){
               \Session::flash('message-danger',  $response->status() );
                break;
            }
            $jsonData = $response->json();
            $jsonData = $jsonData['successattendance'];
           // dd($jsonData);

            //now this is ugly repeated calls to db. lets optimize later
            for ($i=0; $i < count($jsonData); $i++ ) { 
                $dataItem = $jsonData[$i];
                $dateIn = null;
                $dateOut = null;
                $intime = null;
                $outtime =null;
                $this->processPunchingData($jsonData[$i], $dateIn, $intime, $dateOut, $outtime);
                //user may punchin but not punchout. so treat these separately
                
                //org_emp_code from api can be klaid, mobilenumber or empty. 
                $org_emp_code = $dataItem['org_emp_code'];
                $attendanceId = $dataItem['emp_id'];
                
                //date-aadhaarid-pen composite keys wont work if we give null. so something to pen
                //punchout date can be diff to punchin date, not sure
                if($dateIn && $intime && $dateOut && $outtime && ($dateIn === $dateOut)){
                    $matchThese = ['aadhaarid' => $attendanceId ,'date'=> $dateIn];
                    $vals = ['punch_in'=> $intime,'punch_out'=> $outtime, 'pen'=>'-', 'punchin_from_aebas' => true, 'punchout_from_aebas'=> true];
                    if($org_emp_code != '')  $vals['pen'] = $org_emp_code;
                  
                    $punch = Punching::updateOrCreate($matchThese,$vals);
                }
                else
                if($dateIn && $intime){
                   // $date = Carbon::createFromFormat('Y-m-d', $dateIn)->format(config('app.date_format'));
                   //org_emp_code can be null. since empty can cause unique constraint violations, dont allow
                    $matchThese = ['aadhaarid' =>$attendanceId ,'date'=> $dateIn];
                    $vals = ['punch_in'=> $intime,'pen'=>'-', 'punchin_from_aebas' => true, 'punchout_from_aebas'=> false];

                    if($org_emp_code != '') $vals['pen'] = $org_emp_code;
                    
                    $punch = Punching::updateOrCreate($matchThese,$vals);
                   
                }
                else
                if($dateOut && $outtime){
                   // $date = Carbon::createFromFormat('Y-m-d', $dateOut)->format(config('app.date_format'));
                    $matchThese = ['aadhaarid' =>$attendanceId,'date'=> $dateOut];
                    $vals = ['punch_out'=> $outtime,'pen'=>'-', 'punchin_from_aebas' => false, 'punchout_from_aebas'=> true];
                    if($org_emp_code != '')  $vals['pen'] = $org_emp_code;
                    
                    $punch = Punching::updateOrCreate($matchThese,$vals);
                   
                }
                $insertedcount++;

              
                if($org_emp_code != '' && $org_emp_code != null){
                    $pen_to_aadhaarid[$org_emp_code] = $attendanceId;
                }
            }
            //if reached end of data, break
            if(count($jsonData) <  $count){ 
               
                break;
            }
           
        }

        if( $insertedcount ){
            $calenderdate->update( [ 'punching' => 'AEBAS']);

            //$lastfetch = Setting::firstOrCreate( ['name' => 'lastfetch'], 
                                              //  ['value' => Carbon::now() ]);
            //$lastfetch->value = Carbon::now();
           // $lastfetch->save();
        }

        if(count($pen_to_aadhaarid)){
            //Update our employee db with aadhaarid from api
            //since org_emp_code can be empty or even mobile number, make sure this is our pen
            $emps = Employee::select('id', 'pen','aadhaarid')
                    ->wherein('pen', array_keys($pen_to_aadhaarid))->get();
            foreach ($emps->chunk(1000) as $chunk) {
                $cases = [];
                $ids = [];
                $params_aadhaarid = [];
                            
                foreach ($chunk as $emp) {
                        if(!$emp->aadhaarid || $emp->aadhaarid == ''){ //only if it is not set already
                        if (array_key_exists($emp->pen, $pen_to_aadhaarid) ){
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


        \Session::flash('message-success', "Fetched\Processed: {$insertedcount} records for {$reportdate}" );

        return view('admin.calenders.index');
    }
    ////
     
    public function fetchApi(Request $request)
    {
       
        $apikey =  env('AEBAS_KEY');
        $offset = 0;
        $count = 2000;
        $apinum = $request->query('apinum');
        $reportdate = $request->query('reportdate','01-01-2000');

        $returnkey = "successattendance";
         // should be in format 2024-02-11
        $reportdate = Carbon::createFromFormat(config('app.date_format'), $reportdate)->format('Y-m-d');
     
       
     
       
        $data = [];

        
        for ($offset=0;   ; $offset += $count ) { 
            
            $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/attendance/offset/{$offset}/count/{$count}/reportdate/{$reportdate}/apikey/{$apikey}";

            if(1 == $apinum){
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/employee/offset/{$offset}/count/{$count}/apikey/{$apikey}";
                $returnkey = "employee";
            }else
            if( $apinum == 6 ){
                $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/trace/offset/{$offset}/count/{$count}/reportdate/{$reportdate}/apikey/{$apikey}";
                $returnkey = "attendancetrace";

            }
           // $url = 'http://localhost:3000/data';
            Log::info($url);
            $response = Http::withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
            ])->get($url);
                      
            
            if($response->status() !== 200){
                \Session::flash('message-danger',  $response->status() );
                return view('admin.punchings.index');
                //break;
            }
            $jsonData = $response->json();
            $jsonData =  $jsonData[$returnkey];
            $data = array_merge($data,$jsonData);
            //if reached end of data, break
            if(count($jsonData) < $count){ 
               
                break;
            }
           
        }
       
        if(!count($data)) {

            \Session::flash('message-danger', "No Data" );
            return view('admin.punchings.index');
        }
       

        $list = array_values($data);
      //  dd( $list ); # add headers for each column in the CSV download
        array_unshift($list, array_keys($data[0]));
       

        $callback = function() use ($list) 
        {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) { 
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => "attachment; filename={$returnkey}.csv"
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];
        
        return response()->stream($callback, 200, $headers);
    }
 
 

}