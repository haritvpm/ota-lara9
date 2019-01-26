<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Form;
use App\Overtime;
use Carbon\Carbon;
use App\Setting;

class SearchesController extends Controller
{
    public function index()
    {
      /*  if (! Gate::allows('search_access')) {
            return abort(401);
        }*/

        //user can login and see forms even after data entry is disabled
        //$session_array = \App\Session::whereDataentryAllowed('Yes')->pluck('name');
        $sessions = \App\Session::query();

        if( \Auth::user()->isAdminorAudit() ){
            $sessions =  $sessions->orderby('id','desc')->pluck('name');;
        }

        else{

            $sessions =  $sessions->whereshowInDatatable('Yes')->orderby('id','desc')->pluck('name');

        }

        $designations = \App\Designation::orderby('designation','asc')->pluck('designation');


        $added_bies = \App\User::wherein('role_id', ['2','4'])
                                ->where('username', 'not like', 'de.%')
                                ->get(['username'])->pluck('username');

        /*$added_bies->transform(function ($v) {
            return $v == 'admin' ? 'all' : $v;
        });
        */
        $added_bies->prepend('all');


        // FILTERS
               
        if(!Input::filled('session'))
        {            
            
        	return view('admin.searches.index',compact('sessions','added_bies', 'designations'));
        }
       
   
        $str_session = null;             
        $str_status = null;
        $str_overtime_slot = null;
        $str_datefilter = null;
        $str_namefilter = null;
        $str_desigfilter = null;
        $str_created_by = null;
        $str_worknaturefilter=null;

        $session = Input::get('session');
        $overtime_slot = Input::get('overtime_slot');
        $status =  Input::get('status');
        $datefilter=  Input::get('datefilter');
        $namefilter=  Input::get('namefilter');
        $desigfilter=  Input::get('desigfilter');
        $createdby =  Input::get('created_by');
        $worknaturefilter = Input::get('worknaturefilter');
        /*
        $overtimes = Overtime::with(array(
                            'form' => function ($query) {
                                return $query->orderBy('duty_date', 'desc');
                            }
                        ) //'form'
                     )
        			 ->wherehas( 'form', function($q) use ($session){
			               $q->where('session',$session);
			         }); 
                    */
        //above query was not sorting according to duty_date                     
        $overtimes = Overtime::join('forms', 'overtimes.form_id', '=', 'forms.id')
        ->orderBy('forms.duty_date','desc')
                     ->wherehas( 'form', function($q) use ($session){
                           $q->where('session',$session);
                     }); 

        if (Input::filled('created_by')){ 
            if(\Auth::user()->isAdminorAudit()){
                
                if($createdby != 'all'){
                    $overtimes = $overtimes->wherehas( 'form', function($q) use ($createdby){
                                                $q->where( 'creator', 'like', '%'.$createdby.'%');
                                        }); 
                }
            }
            else{
                if ($createdby == 'Us'){
                    $overtimes = $overtimes->wherehas( 'form', function($q) use ($createdby){
                                                $q->CreatedOrOwnedOrApprovedByLoggedInUser();
                                            }); 

                    
                }

            
              $str_created_by = '&created_by='.$createdby;

            }


        }


        if (Input::filled('overtime_slot')){

           if($overtime_slot == 'Non-Sittings'){
               $overtimes = $overtimes->wherehas( 'form', function($q) {
                                $q->where( 'overtime_slot' , '!=', 'Sittings');
                            }); 
            }
            else{ 
                $overtimes = $overtimes->wherehas( 'form', function($q) use ($overtime_slot){
			               		$q->whereOvertimeSlot($overtime_slot);
			               	 }); 
            }

            $str_overtime_slot = '&overtime_slot='.$overtime_slot;
        }

        if (Input::filled('datefilter')){
                  
            $overtimes = $overtimes->wherehas( 'form', function($q) use ($datefilter){
			               		$q->filterDate($datefilter);
			               		}); 

            $str_datefilter = '&datefilter='.$datefilter;
        }

        //override
        if(auth()->user()->isAudit()){
           
            $status = 'Submitted';
         
        }
                     
        $overtimes = $overtimes->wherehas( 'form', function($q) use ($status){
		               		$q->filterstatus($status)
                             /*->where('form_no', '>=', 0)*/;
		               		}); 

        $str_status = '&status='.$status;
        


        			 

        if (Input::filled('namefilter')){
        
             $overtimes->where(function($query) use ($namefilter)
                    {
                      $query->where('pen','like', '%' . $namefilter.'%' )
                            ->orWhere('name','like', '%' . $namefilter.'%' );
                    });

            
                            
            $str_namefilter = '&namefilter='. $namefilter;
        }

        if (Input::filled('worknaturefilter')){
                    
            $overtimes->where('worknature','like', '%' . $worknaturefilter.'%' );
                            
            $str_worknaturefilter = '&worknaturefilter='. $worknaturefilter;
        }

    
        if (Input::filled('desigfilter')){
                      
            if( strpos($desigfilter, '^') === 0  )
            {
               $overtimes= $overtimes->where('designation', 'like',  substr($desigfilter,1).'%' );
             
            }
            elseif( strpos($desigfilter, '=') === 0  )
            {
                $overtimes=$overtimes->where('designation',  'like', substr($desigfilter,1) );
             
            }
            else
            {
                $overtimes=$overtimes->where('designation', 'like', '%' . $desigfilter.'%' );
             
            }
                           
             $str_desigfilter = '&desigfilter=' . $desigfilter;
         }
     
       // $overtimes =  $overtimes->latest();
       //$overtimes =  $overtimes->orderbyraw("SUBSTRING_INDEX(pen,'-',-1)",'asc')->latest();
     

        $res = $overtimes->get();
        $total_overtimes = 0;
        $total_amount = 0;
        $total_amount_submitted = 0;


        $uniques = array();
        foreach ($res as $c) {
            $uniques[$c->pen] = $c; 
            $total_overtimes += $c->count;
            $total_amount += $c->rate*$c->count;

            if( $c->form->form_no > 0 ){
                $total_amount_submitted += $c->rate*$c->count;
            }

        }

        $user_count = count($uniques);
      
        $overtimes =  $overtimes->paginate(15)->appends(Input::except('page'));

        return view('admin.searches.index',compact('sessions','added_bies', 'designations', 'overtimes','user_count', 
                                                   'total_overtimes', 'total_amount', 'total_amount_submitted'));
    }

    public function download()
    {
        

        $str_session = null;             
        $str_overtime_slot = null;
        $str_created_by = null;
        $str_submitted_before = null;
        $str_submitted_after = null;
        $str_formno_before = null;
        $str_formno_after = null;


        $session = Input::get('session');
        $overtime_slot = Input::get('overtime_slot');
        $createdby =  Input::get('created_by');
        $submitted_before =  Input::get('submitted_before');
        $submitted_after =  Input::get('submitted_after');
        $formno_before =  Input::get('formno_before');
        $formno_after =  Input::get('formno_after');

/*
cannot trust form id, as a user might have started a form, but waited long to submit it. so submit date is the key.
*/
        
        $overtimes = Overtime::with('form')
                     ->wherehas( 'form', function($q) use ($session){
                           $q->where('session',$session)
                             ->where('form_no', '>=', 0)
                             ->where('owner', 'admin'); //submitted to us
                     }); 

        if (Input::filled('created_by')){ 
                          
            if($createdby != 'all'){
                $overtimes = $overtimes->wherehas( 'form', function($q) use ($createdby){
                                            $q->where( 'creator', 'like', '%'.$createdby.'%');
                                    }); 
            }
                   
        }

        if (Input::filled('overtime_slot')){
           if($overtime_slot == 'Non-Sittings'){
               $overtimes = $overtimes->wherehas( 'form', function($q) {
                                $q->where( 'overtime_slot' , '!=', 'Sittings');
                            }); 
            }
            else{ 
                $overtimes = $overtimes->wherehas( 'form', function($q) use ($overtime_slot){
                                $q->whereOvertimeSlot($overtime_slot);
                             }); 
            }
        }

        if (Input::filled('submitted_before')){
                  
            $date = Carbon::createFromFormat(config('app.date_format'), $submitted_before )->format('Y-m-d');

            $overtimes = $overtimes->wherehas( 'form', function($q) use ($date){
                                $q->where( 'submitted_on', '<=', $date );
                                }); 

            $submitted_before = '&submitted_before='.$submitted_before;
        }

        if (Input::filled('submitted_after')){
                  
            $date = Carbon::createFromFormat(config('app.date_format'), $submitted_after )->format('Y-m-d');

           

            $overtimes = $overtimes->wherehas( 'form', function($q) use ($date){
                                $q->where( 'submitted_on', '>=', $date );
                                }); 

            $submitted_after = '&submitted_after='.$submitted_after;
        }

        if (Input::filled('formno_before')){
                  
          
            $overtimes = $overtimes->wherehas( 'form', function($q) use ($formno_before){
                                $q->where( 'form_no', '<=', $formno_before );
                                }); 

            $formno_before = '&formno_before='.$formno_before;
        }
        if (Input::filled('formno_after')){
                  
          
            $overtimes = $overtimes->wherehas( 'form', function($q) use ($formno_after){
                                $q->where( 'form_no', '>=', $formno_after );
                                }); 

            $formno_after = '&formno_after='.$formno_after;
        }

     
        $overtimes =  $overtimes
                                ->orderBy('pen', 'asc')->get(); //this will be helpful to make sure duplicate pen_rate are side by side

        $totalotcount = $overtimes->sum('count');
     

        $filename = "sectt_ot-{$session}_count-" . $totalotcount . '-';

        if($submitted_after)
            $filename .= $submitted_after .'-';
        if($submitted_before)
            $filename .= $submitted_before . '-';

        if($formno_after)
            $filename .= $formno_after .'-';
        if($formno_before)
            $filename .= $formno_before . '-';


        $filename .=  date('Y-m-d') . '.csv';
        
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build($overtimes, [ 'form.form_no', 'form.creator','form.id',
                            'form.overtime_slot', 'form.session','form.duty_date', 
                            'form.date_from', 'form.date_to', 'form.submitted_on',
                             'id', 'pen', 'designation', 'from', 'to','count', 'name','worknature'
                            ]);
        //rate is not needed. we store rates in seperate csv so we can adjust to changes in rate later

        $csvExporter->download($filename);

        /*$lastexported = Setting::firstOrCreate( ['name' => $session.'-lastexporttime'], 
                                                ['value' => Carbon::now() ]);
        $lastexported->value = Carbon::now();
        $lastexported->save();*/

        

    }


    public function download_calender()
    {
        

        $str_session = null;             
      

        $session = Input::get('session');
            
        //save calender
        $calender = \App\Calender::with("session")
                     ->wherehas( 'session', function($q) use ($session){
                           $q->where('name',$session);
                            
                     })->orderby('date','asc')->get();

        $filename =  $session . '-calender'.  '.csv';
        
        $csvExporterCalender = new \Laracsv\Export();

        // The field 'date short' doesn't exist so values for this field will be blank by default 
        $csvExporterCalender->beforeEach(function ($day) {
            // Now notes field will have this value
            $myDate = Carbon::createFromFormat('d-m-Y', $day->date);
            $day->shortdate = $myDate->format('M_Y-j-');

            if($day->day_type == 'Sitting day')
                $day->shortdate .=  'S';

            
        });


        $csvExporterCalender->build($calender, [ 'date','day_type', 'shortdate'
                            ]);

        $csvExporterCalender->download($filename);


    }


    public function download_desig()
    {
        

        $desig = \App\Designation::orderby('rate','desc')->orderby('designation','asc')->get();

        $filename =  'sectt_designation_rates-'.  date('Y-m-d') . '.csv';
        
        $csvExporter = new \Laracsv\Export();


        $csvExporter->build($desig, [ 'designation','rate' ]);

        $csvExporter->download($filename);


    }


    public function download_emp()
    {        

        $desig = \App\Employee::with('designation','categories' )
                            ->where('category','<>','Relieved')->orderby('id','desc')->get();

        $filename =  'sectt_employees-'.  date('Y-m-d') . '.csv';
        
        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($desig, [ "pen","srismt","name","designation.designation","desig_display",'categories.category',"added_by","designation.rate", "category" ]);

        $csvExporter->download($filename);

    }

    public function download_user()
    {        

        $desig = \App\User::with(['routing','role'])->orderby('id','desc')->get();

        $filename =  'users-'.  date('Y-m-d') . '.csv';
        
        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($desig, [ "id","name","email","username","displayname","role.title", "routing.route", "routing.last_forwarded_to"]);

        $csvExporter->download($filename);

    }

}
