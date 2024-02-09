<?php

namespace App\Http\Controllers\Admin;

use App\Punching;
use App\Calender;
use Illuminate\Http\Request;

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
    public function ajaxgetpunchtimes($date, $pen)
    {


    $tmp = strpos($pen, '-');
    if(false !== $tmp){
        $pen = substr($pen, 0, $tmp);
    }

    $date = Carbon::createFromFormat(config('app.date_format'), $date)->format('Y-m-d');
       
    $temp =  Punching::where('date',$date)  
             ->where('pen',$pen) 
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
        
   
        $sessions = \App\Session::query();

        if( \Auth::user()->isAdmin() ){
            $sessions =  $sessions->orderby('id','desc')->pluck('name');;
        }
        else{
            $sessions =  $sessions->whereshowInDatatable('Yes')->orderby('id','desc')->pluck('name');
        }
         
        if(!$request->filled('session'))
        {            
        	return view('admin.punchings.index',compact('sessions'));
        }

        $str_sessionfilter = null;                 
        $str_datefilter = null;
        $str_namefilter = null;
        $session = $request->query('session');
        $datefilter=  $request->query('datefilter');
        $namefilter=  $request->query('namefilter');

            
        // $punchings = Punching::where('session',$session);
        $punchings = Punching::query();

          
        if ($request->filled('session')){
                  
            $punchings = $punchings->where( 'session',$session);
			               		

            $str_sessionfilter = '&session='.$session;
        }
        
        if ($request->filled('datefilter')){
    
            $date = Carbon::createFromFormat(config('app.date_format'), $datefilter)->format('Y-m-d');
                  
            $punchings = $punchings->where( 'date',$date);
			               		

            $str_datefilter = '&datefilter='.$datefilter;
        }

        
        if ($request->filled('namefilter')){
                    
            $punchings = $punchings->where('pen','like', '%' . $namefilter.'%' );
                            
            $str_namefilter = '&namefilter='. $namefilter;
        }
        
        $punchings =  $punchings->paginate(10)->appends($request->except('page'));

        return view('admin.punchings.index',compact('sessions','punchings'
                                                   ));
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


}