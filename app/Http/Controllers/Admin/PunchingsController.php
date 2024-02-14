<?php

namespace App\Http\Controllers\Admin;

use App\Punching;
use App\Calender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
//use Yajra\DataTables\DataTables;
//use Laracasts\Utilities\JavaScript\JavaScriptFacade;
use JavaScript;
use Carbon\Carbon;
use Auth;
use PDF;

use Illuminate\Support\Facades\Log; 

class PunchingsController extends Controller
{

    //the args are set in route file web.php
    public function ajaxgetpunchtimes($date, $pen, $aadhaarid)
    {


    $tmp = strpos($pen, '-');
    if(false !== $tmp){
        $pen = substr($pen, 0, $tmp);
    }

    $date = Carbon::createFromFormat(config('app.date_format'), $date)->format('Y-m-d');
       
    $temp =  Punching::where('date',$date)  
            ->where( function ($query) use ($pen, $aadhaarid) {
                $query->where('pen',$pen) 
                    ->orwhere('aadhaarid',$aadhaarid) ;
            })
           // ->wherenotnull('punch_in') //prevent if only one column is available
           //  ->wherenotnull('punch_out') 
             ->first(); 
    
    if($temp){
        Log::info($temp);
       
   
      return [
            'punchin' => $temp['punch_in'],
            'punchout' => $temp['punch_out'],
            'creator' => $temp['creator'],
            'id' => $temp['id']
        ];
    } else return [];
        
    }


    public function index(Request $request)
    {
        if (! Gate::allows('my_form_access')) {
            return abort(401);
        }
        
   
        // $sessions = \App\Session::query();

        // if( \Auth::user()->isAdmin() ){
        //     $sessions =  $sessions->orderby('id','desc')->pluck('name');;
        // }
        // else{
        //     $sessions =  $sessions->whereshowInDatatable('Yes')->orderby('id','desc')->pluck('name');
        // }
         
        // if(!$request->filled('session'))
        // {            
        // 	return view('admin.punchings.index',compact('sessions'));
        // }

        // $str_sessionfilter = null;                 
        $str_datefilter = null;
        $str_namefilter = null;
        // $session = $request->query('session');
        $datefilter=  $request->query('datefilter');
        $namefilter=  $request->query('namefilter');

            
        // $punchings = Punching::where('session',$session);
        $punchings = Punching::query();

          
        // if ($request->filled('session')){
                  
        //     $punchings = $punchings->where( 'session',$session);
			               		

        //     $str_sessionfilter = '&session='.$session;
        // }
        
        if ($request->filled('datefilter')){
    
            $date = Carbon::createFromFormat(config('app.date_format'), $datefilter)->format('Y-m-d');
                  
            $punchings = $punchings->where( 'date',$date);
			               		

            $str_datefilter = '&datefilter='.$datefilter;
        } else {
            $punchings = $punchings->where( 'date','0000-00-00');
        }

        
        if ($request->filled('namefilter')){
                    
            $punchings = $punchings->where('pen','like', '%' . $namefilter.'%' );
                            
            $str_namefilter = '&namefilter='. $namefilter;
        }
        
        $punchings =  $punchings->paginate(10)->appends($request->except('page'));

        return view('admin.punchings.index',compact('punchings' ));
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
    }

    public function update(Request $request, Punching $punching)
    {
        $punching->update($request->all());

        return redirect()->route('admin.punchings.index');
    }

    public function show(Punching $punching)
    {
        // abort_if(Gate::denies('punching_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $punching->load('form');

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

    public function fetch(Request $request)
    {
        $apikey = 'OTlSaDVtaHRGRFV0VUVBaE5FV3FtV2R0cHpnZmdQUC9xWGpqTkhSSDNSYmZMWGFPQnIwN1drRjZyaENPVVpJWjlLby95eXp1M3N5YUZMNHhGVW1ZS21zTXN1N1B4NStwQ1p3dE1lNDl5U1U9';
        $offset = 0;
        $count = 500;
      
        // should be in format 2024-02-11
        $reportdate = Carbon::createFromFormat(config('app.date_format'), $request->query('reportdate'))->format('Y-m-d');
     
        $url = "https://basreports.attendance.gov.in/api/unibasglobal/api/attendance/offset/{$offset}/count/{$count}/reportdate/{$reportdate}/apikey/{$apikey}";

       // Log::info($url);
       //if this date is not in calender, do nothing
     
        if(! Calender::where('date', $reportdate )->exists()){
            $request->session()->flash('message-success', 'No such date in calender'  );
            return view('admin.punchings.index');

        }

        $url = 'http://localhost:3000/data';

        $insertedcount = 0;
        for ($offset=0;   ; $offset += $count+1 ) { 
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->get($url);

            if($response->status() !== 200){
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

                //punchout date can be diff to punchin date, not sure
                if($dateIn && $intime && $dateOut && $outtime && ($dateIn === $dateOut)){
                    $matchThese = ['aadhaarid' =>$dataItem['emp_id'] ,'date'=> $dateIn];
                    $vals = ['punch_in'=> $intime,'punch_out'=> $outtime, 'pen'=>null];
                    if($dataItem['org_emp_code'] != '')  $vals['pen'] = $dataItem['org_emp_code' ];
                  
                    $punch = Punching::updateOrCreate($matchThese,$vals);
                }
                else
                if($dateIn && $intime){
                   // $date = Carbon::createFromFormat('Y-m-d', $dateIn)->format(config('app.date_format'));
                   //org_emp_code can be null. since empty can cause unique constraint violations, dont allow
                    $matchThese = ['aadhaarid' =>$dataItem['emp_id'] ,'date'=> $dateIn];
                    $vals = ['punch_in'=> $intime,'pen'=>null];

                    if($dataItem['org_emp_code'] != '') $vals['pen'] = $dataItem['org_emp_code' ];
                    
                    $punch = Punching::updateOrCreate($matchThese,$vals);
                   
                }
                else
                if($dateOut && $outtime){
                   // $date = Carbon::createFromFormat('Y-m-d', $dateOut)->format(config('app.date_format'));
                    $matchThese = ['aadhaarid' =>$dataItem['emp_id'],'date'=> $dateOut];
                    $vals = ['punch_out'=> $outtime,'pen'=>null];
                    if($dataItem['org_emp_code'] != '')  $vals['pen'] = $dataItem['org_emp_code' ];
                    
                    $punch = Punching::updateOrCreate($matchThese,$vals);
                   
                }
                $insertedcount++;
            }

            //if reached end of data, break
            if(count($jsonData) <  $count){ 
               
                break;
            }
           
        }

        $request->session()->flash('message-success', 'Processed: ' . $insertedcount );

        return view('admin.punchings.index');
    }
    

}