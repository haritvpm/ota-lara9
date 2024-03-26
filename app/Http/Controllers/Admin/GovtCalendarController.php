<?php

namespace App\Http\Controllers\Admin;

use App\GovtCalendar;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateGovtCalendarRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Services\PunchingService;
use App\Jobs\AebasFetch;

class GovtCalendarController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('govt_calendar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $govtCalendars = GovtCalendar::orderBy('date', 'DESC')->get();

        return view('admin.govtCalendars.index', compact('govtCalendars'));
    }

    public function edit(GovtCalendar $govtCalendar)
    {
        abort_if(Gate::denies('govt_calendar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.govtCalendars.edit', compact('govtCalendar'));
    }

    public function update(UpdateGovtCalendarRequest $request, GovtCalendar $govtCalendar)
    {
        $govtCalendar->update($request->all());

        return redirect()->route('admin.govt-calendars.index');
    }

    public function show(GovtCalendar $govtCalendar)
    {
        abort_if(Gate::denies('govt_calendar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.govtCalendars.show', compact('govtCalendar'));
    }
    public function updatemonth(Request $request)
    {
        $jsonData = json_decode($request->jsonfromgovtsite, true);

        $currentmonth = $jsonData['currentmonth'];

        $calendars = $jsonData['calendar'];
        foreach ($calendars as $jsonDay) {
           
            GovtCalendar::updateOrCreate([
                'date' => $jsonDay['actualdate'],
                'govtholidaystatus' => $jsonDay['govtholidaystatus'],
                'bankholidaystatus' => $jsonDay['bankholidaystatus'],
                'restrictedholidaystatus' => $jsonDay['restrictedholidaystatus'],
            ]);
        }
        $festivallist = $jsonData['festivallist'];

        foreach ($festivallist as $festivals_in_day) {

            $date = Carbon::now()->setMonth($currentmonth)->setDay($festivals_in_day['calnumber'])->format('Y-m-d');

            $calender = GovtCalendar::where('date','=',$date)->first();
            $festivals = [];
            foreach ($festivals_in_day['title'] as $festival) {
                $festivals[]=$festival['maltitle'];
            }
            $calender->update( [
                'festivallist' =>  implode("; ", $festivals)
            ] );
        }
        return redirect()->back();


    }

    //not working. seems some cookie issue. try postman
    public function fetchFromGovtSite(Request $request)
    {
       // abort_if(Gate::denies('govt_calendar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        for ($i=1; $i <= 12 ; $i++) { 
            
            $url = "https://www.kerala.gov.in/ajaxmonth/{$i}";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
            ])->get($url);

            if ($response->status() !== 200) {
                break;
            }
           // dd($url);
            dd($response);
            $jsonData = $response->json();
            $calendars = $jsonData['calendar'];
dd($calendars);
            foreach ($calendars as $jsonDay) {
                $calender = new GovtCalendar();
                $calender->date = $jsonDay['actualdate'];
                $calender->govtholidaystatus = $jsonDay['govtholidaystatus'];
                $calender->bankholidaystatus = $jsonDay['bankholidaystatus'];
                $calender->restrictedholidaystatus = $jsonDay['restrictedholidaystatus'];

                $calender->attendance_today_trace_fetched = 0;
                $calender->attendance_today_trace_rows_fetched = 0;
                $calender->save();
            }
            $festivallist = $jsonData['festivallist'];

            foreach ($festivallist as $festivals_in_day) {

                $date = Carbon::now()->setMonth($i)->setDay($festivals_in_day['calnumber'])->format('Y-m-d'); //today

                $calender = GovtCalendar::whereDate($date)->first();
                $festivals = [];
                foreach ($festivals_in_day['title'] as $festival) {
                    $festivals[]=$festival['maltitle'];
                }
                $calender->update( [
                    'festivallist' =>  implode("|", $festivals)
                ] );
            }



        }
        return redirect()->back();
    }


    //fetch both success and trace for this day. 
    //now only fetching trace. todo successattendance after moving OT calender functionality to here
    public function fetchApi(Request $request)
    {
        $reportdate = $request->query('reportdate');
     
        if(!$reportdate)   return redirect()->back();

        \Log::info("fetch attendance trace !. " .  $reportdate);
       // (new PunchingService())->fetchTodayTrace($reportdate);


        return redirect()->back();
    }
    
    public function fetchmonth(Request $request)
    {
        
        \Log::info("fetchmonth attendance trace !. " );
       // (new PunchingService())->fetchTodayTrace($reportdate);
       $today = today(); 
       $dates = []; 
   
       for($i=1; $i < $today->daysInMonth + 1; ++$i) {
           $date = Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
           //AebasFetch::dispatch($date)->delay(now()->addMinutes($i));

           //dont forget to set queue driver in env
           //QUEUE_CONNECTION=database
          // $job = (new AebasFetch($date))->delay(Carbon::now()->addMinutes($i));
 
          // $this->dispatch($job);
          AebasFetch::dispatch($date)
          ->delay(now()->addMinutes($i));
       }

        return redirect()->back();
    }
}
