<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\FormOther;
use App\OvertimeOther;
use Carbon\Carbon;

class ReportsOthersController extends Controller
{
    public function index()
    {
        if (! Gate::allows('my_form_others_access')) {
            return abort(401);
        }
        $begintime = microtime(true);

        //user can login and see forms even after data entry is disabled
        //$session_array = \App\Session::whereDataentryAllowed('Yes')->pluck('name');
        $sessions = \App\Session::query();
     

        $sessions =  $sessions->whereshowInDatatable('Yes')
        					->orderby('id','desc')->pluck('name');
        
        $rows = array();
        $report_type = 'Detailed';//Input::get('report_type');
        
        $added_bies = \App\User::OtherDeptUsers()
                                 ->orderBy('name','asc')
                                 ->get(['username','name'])->pluck('name','username');


        if(!Input::filled('session'))
        {            
        	return view('admin.reports_others.index',compact('sessions', 'rows','report_type', 'added_bies'));
        }
      
        $createdby =  Input::get('created_by');


        $session =  Input::get('session');

        $sessionitem = \App\Session::where('name', $session )->first();
        $romankla = $sessionitem->getRomanKLA();
        $sessionnumber_th = $sessionitem->session .  '<sup>' . $sessionitem->getOrdinalSuffix($sessionitem->session) . '</sup>';
        $malkla = $sessionitem->getMalayalamOrdinalSuffix($sessionitem->kla);
        $sessionnumber = $sessionitem->session;


        $data = \App\OvertimeOther::with("form")
        						->wherehas( 'form', function($q) use ($session) {
                                  $q->where('session',$session)
                                    ->where('owner','admin')                    ;
                            });

        $usernameofsection = \Auth::user()->username;
        $usernameloggedin = \Auth::user()->username;
                                 
        if(auth()->user()->isAdmin())
        {
            $usernameofsection = $createdby;
            $usernameloggedin = $createdby;
        }
 
       $data->wherehas( 'form', function($q) use ($session, 
                        $usernameofsection,$usernameloggedin) {
                       
                      $q->where( 'creator', $usernameloggedin )
                      ->orwhere( 'creator', $usernameofsection );
                        
                    });
                
        

        /*if (Input::filled('created_by')){ 
                           
            if($createdby != 'all'){
               
                $data->wherehas( 'form', function($q) use ($createdby){
                                $q->where( 'creator', 'like', '%'.$createdby); 
                                }); 

                                                       
                $str_created_by = '&created_by='.$createdby;
            }
            
        }*/

        //if($report_type == 'Detailed')
        {
            $desig_sort_order = \App\Preset::
                where('name','default_designation_sortorder')
                ->first()->pens;

            $desig_sort_order =trim($desig_sort_order,",");

            $data = $data
            //->orderby('designation','asc')
            
           // ->orderbyraw("field (designation, " . $desig_sort_order . ")" )
            ->orderbyraw("SUBSTRING_INDEX(pen,'-',-1)",'asc');
            //->orderbyraw("designation",'asc')
            ;
        }

        $overtimes = $data->get();

// dd($overtimes );

      
        $rates = \App\DesignationsOther::all()->pluck('rate','designation');
 //dd($rates );
        $calendermap = \App\Calender::with("session")
                                ->wherehas('session', function($q) use ($session) {
                                  $q->where( 'name', $session);}
                     )->pluck('day_type','date');

      
        define("F_SITTING", 0x8);
        define("F_FIRST", 0x1);
        define("F_SECOND", 0x2);
        define("F_THIRD", 0x4);
        define("F_ADDITIONAL", 0x10);
        $totalamountfromcontroller = 0;
        $totalots = 0;


        foreach ($overtimes as $key => $value) {

            $hiphen = strpos($value->pen, '-');
            $coma = strpos($value->pen, ',');

            $pen_actual = $value->designation;
            $designation_actual = trim(substr($value->pen, $coma+1));
            $name = substr($value->pen, $hiphen+1, $coma-$hiphen-1);
            $rate = $rates[$designation_actual];
            $k = $pen_actual . ', ' . $rate ;
                        

            if(!array_key_exists($k, $rows)){
                $rows[$k] = [];
                $rows[$k]['ns'] = 0;
                $rows[$k]['s'] = 0;
                $rows[$k]['rate'] = $rate;

                $rows[$k]['desig'] = $pen_actual . ' - ' . 
                                     $designation_actual;
                $rows[$k]['name'] = $name;
                

            }
                 
           

            if( $value->form->overtime_slot != "Sittings" ){
                
                $d = Carbon::createFromFormat(config('app.date_format'), $value->form->duty_date )->format('Y-m-d');

               
                $date = date('d', strtotime($d));
                $m = date('M', strtotime($d));
                
                $flag = 0;
                                          

                if( $value->form->overtime_slot == "First" ){
                    
                     $flag = F_FIRST;

                }
                else
                if( $value->form->overtime_slot == "Second" ){
                   
                     $flag = F_SECOND;
                }
                else
                if( $value->form->overtime_slot == "Third" ){
                     
                     $flag = F_THIRD;
                }
                else
                if( $value->form->overtime_slot == "Additional" ){
                     
                     $flag = F_ADDITIONAL;
                }
                else{
                    dd($value->form->overtime_slot);
                }

                if($calendermap[$d] == 'Sitting day') {
                    $flag |= F_SITTING;

                }

                $rows[$k]['ns'] += 1;

                $totalamountfromcontroller += $rate ;  
                $totalots += 1; 

                $arr = $rows[$k];
                if(is_array($arr) && array_key_exists($m, $arr)){
                    $arr2 = $rows[$k][$m];
                    if(array_key_exists($date, $arr2)){

                        $rows[$k][$m][$date] |= $flag;
                    }
                    else{
                        $rows[$k][$m][$date] = $flag;   
                    }
                }
                else{
                    $rows[$k][$m][$date] = $flag;

                }

            }
            else{
                if(array_key_exists($k, $rows)){
                    $rows[$k]['s'] += $value->count; 
                    //there can be multiple entries for sitting
                }
                else{
                    $rows[$k]['s'] = $value->count; 
                    //there can be multiple entries for sitting
                 
                }

                $totalamountfromcontroller += $rate*$value->count ;
                $totalots += $value->count; 

            }


        }

      
/*
        foreach ($rows_ns as $key => $value) {
            echo "Name: $key =>";

            foreach ($value as $m => $date) {
                echo "$m : ";
                $str = '';
                foreach ($date as $d => $v) {
                $v = rtrim($v,',');
                    $str .= "$d $v, ";
            
                }

                echo rtrim($str,',');
            
            }
            
             echo "\n";

        }
       */

        $timetaken = round(microtime(true) - $begintime,4);

        return view('admin.reports_others.index', compact('sessions', 'rows', 'session', 'romankla', 'sessionnumber_th', 'malkla', 'sessionnumber', 'report_type', 'overtimes','added_bies', 'totalamountfromcontroller' , 'timetaken', 'totalots'));
    }
/*
    public function detailed_report()
    {
        if (! Gate::allows('report_access')) {
            return abort(401);
        }

        //user can login and see forms even after data entry is disabled
        //$session_array = \App\Session::whereDataentryAllowed('Yes')->pluck('name');
        $sessions = \App\Session::query();
     

        $sessions =  $sessions->whereshowInDatatable('Yes')
                            ->orderby('id','desc')->pluck('name');
        
        $rows = array();
                     
        if(!Input::filled('session'))
        {            
            return view('admin.reports.index',compact('sessions', 'rows'));
        }

        $str_submitted_before = null;
        $str_submitted_after = null;
        $submitted_before =  Input::get('submitted_before');
        $submitted_after =  Input::get('submitted_after');


        $session =  Input::get('session');

        $sessionitem = \App\Session::where('name', $session )->first();
        $romankla = $sessionitem->getRomanKLA();
        $sessionnumber_th = $sessionitem->session .  '<sup>' . $sessionitem->getOrdinalSuffix($sessionitem->session) . '</sup>';
        $malkla = $sessionitem->getMalayalamOrdinalSuffix($sessionitem->kla);
        $sessionnumber = $sessionitem->session;




        $data = \App\Overtime::with("form")
                                ->wherehas( 'form', function($q) use ($session) {
                                  $q->where('session',$session)
                                    ->where('owner','admin')
                                    ->where( 'creator','<>', 'admin' ) //exclude pa2mla
                                    ;
                            });

        if(!auth()->user()->isAdminorAudit()){

            //if cur user is C.A of JS or assistant, include section and JS created ones too

            $usernameofsection = \Auth::user()->username;

            if( strpos($usernameofsection,'de.') === 0  ){
                $usernameofsection = substr($usernameofsection,3);
            }
            else{
                $usernameofsection = 'de.' . $usernameofsection;
            }

            $data = $data
                                ->wherehas( 'form', function($q) use ($session, $usernameofsection) {
                                  $q->where( 'creator', \Auth::user()->username )
                                  ->orwhere( 'creator', $usernameofsection );
                                });
        }

        if (Input::filled('submitted_before')){
                  
            $date = Carbon::createFromFormat(config('app.date_format'), $submitted_before )->format('Y-m-d');

            $data = $data->wherehas( 'form', function($q) use ($date){
                                $q->where( 'submitted_on', '<=', $date );
                                }); 
            

            $submitted_before = '&submitted_before='.$submitted_before;
        }

        if (Input::filled('submitted_after')){
                  
            $date = Carbon::createFromFormat(config('app.date_format'), $submitted_after )->format('Y-m-d');

            $data = $data->wherehas( 'form', function($q) use ($date){
                                $q->where( 'submitted_on', '>=', $date );
                                }); 

            $submitted_before = '&submitted_after='.$submitted_before;
        }

        $data = $data->get();

// dd($data );

        $designations = $data->pluck('designation');

        $rates = \App\Designation::wherein ('designation', $designations )->pluck('rate','designation');

        $calendermap = \App\Calender::with("session")
                                ->wherehas('session', function($q) use ($session) {
                                  $q->where( 'name', $session);}
                     )->pluck('day_type','date');

      
        define("F_SITTING", 0x8);
        define("F_FIRST", 0x1);
        define("F_SECOND", 0x2);
        define("F_THIRD", 0x4);
        define("F_ADDITIONAL", 0x10);


        foreach ($data as $key => $value) {

            $k = $value->pen . ', ' . $rates[$value->designation];

            if(!array_key_exists($k, $rows)){
                $rows[$k] = '';
                $rows[$k]['ns'] = 0;
                $rows[$k]['s'] = 0;
               
                $rows[$k]['desig'] = $value->designation;

            }
                 
           

            if( $value->form->overtime_slot != "Sittings" ){
                
                $d = Carbon::createFromFormat(config('app.date_format'), $value->form->duty_date )->format('Y-m-d');

               

                $date = date('d', strtotime($d));
                $m = date('M', strtotime($d));
                
                $flag = 0;
                                          

                if( $value->form->overtime_slot == "First" ){
                    
                     $flag = F_FIRST;

                }
                else
                if( $value->form->overtime_slot == "Second" ){
                   
                     $flag = F_SECOND;
                }
                else
                if( $value->form->overtime_slot == "Third" ){
                     
                     $flag = F_THIRD;
                }
                else
                if( $value->form->overtime_slot == "Additional" ){
                     
                     $flag = F_ADDITIONAL;
                }
                else{
                    dd($value->form->overtime_slot);
                }

                if($calendermap[$d] == 'Sitting day') {
                    $flag |= F_SITTING;

                }

                $rows[$k]['ns'] += 1;    

                $arr = $rows[$k];
                if(is_array($arr) && array_key_exists($m, $arr)){
                    $arr2 = $rows[$k][$m];
                    if(array_key_exists($date, $arr2)){

                        $rows[$k][$m][$date] |= $flag;
                    }
                    else{
                        $rows[$k][$m][$date] = $flag;   
                    }
                }
                else{
                    $rows[$k][$m][$date] = $flag;

                }

            }
            else{
                if(array_key_exists($k, $rows))
                    $rows[$k]['s'] += $value->count; //there can be multiple entries for sitting
                else
                    $rows[$k]['s'] = $value->count; //there can be multiple entries for sitting

            }


        }

 

        return view('admin.reports.index', compact('sessions', 'rows', 'session', 'romankla', 'sessionnumber_th', 'malkla', 'sessionnumber' ));
    }
    */

}
