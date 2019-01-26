<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\FormOther;
use App\OvertimeOther;
use Carbon\Carbon;
use App\Setting;

class SearchesOtherController extends Controller
{
    public function index()
    {
      /*  if (! Gate::allows('search_access')) {
            return abort(401);
        }*/

        //user can login and see forms even after data entry is disabled
        //$session_array = \App\Session::whereDataentryAllowed('Yes')->pluck('name');
        $sessions = \App\Session::query();

        if( \Auth::user()->isAdmin() ){
            $sessions =  $sessions->orderby('id','desc')->pluck('name');;
        }
        else{

            $sessions =  $sessions->whereshowInDatatable('Yes')->orderby('id','desc')->pluck('name');

        }


        $added_bies = \App\User::where('role_id', '3')
                                ->get(['username'])->pluck('username');

        /*$added_bies->transform(function ($v) {
            return $v == 'admin' ? 'all' : $v;
        });
        */
        $added_bies->prepend('all');


        // FILTERS
               
        if(!Input::filled('session'))
        {            
        	return view('admin.searches_other.index',compact('sessions','added_bies'));
        }
       
        $str_session = null;             
        $str_status = null;
        $str_overtime_slot = null;
        $str_datefilter = null;
        $str_namefilter = null;
        $str_desigfilter = null;
        $str_created_by = null;

        $session = Input::get('session');
        $overtime_slot = Input::get('overtime_slot');
        $status =  Input::get('status');
        $datefilter=  Input::get('datefilter');
        $namefilter=  Input::get('namefilter');
        $desigfilter=  Input::get('desigfilter');
        $createdby =  Input::get('created_by');
        
        $overtimes = OvertimeOther::with('form')
        			 ->wherehas( 'form', function($q) use ($session){
			               $q->where('session',$session);
			         }); 

        if (Input::filled('created_by')){ 
            if(\Auth::user()->isAdmin()){
                
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

        if (Input::filled('status')){
                  
            $overtimes = $overtimes->wherehas( 'form', function($q) use ($status){
			               		$q->filterstatus($status);
			               		}); 

            $str_status = '&status='.$status;
        }

        			 

        if (Input::filled('namefilter')){
                    
            $overtimes = $overtimes->where('pen','like', '%' . $namefilter.'%' );
                            
            $str_namefilter = '&namefilter='. $namefilter;
        }
    
        if (Input::filled('desigfilter')){
                      
             $overtimes = $overtimes->where('designation', 'like', '%' . $desigfilter.'%' );
                           
             $str_desigfilter = '&desigfilter=' . $desigfilter;
         }
     
        $overtimes2 =  $overtimes->orderBy('pen', 'asc');
     

        $res = $overtimes2->get();
        $total_overtimes = 0;
        $total_amount = 0;

        $uniques = array();
        foreach ($res as $c) {
            $pen = substr($c->pen,  0, strpos($c->pen, '-') );
            $uniques[$pen] = $c; 
            $total_overtimes += $c->count;
            $total_amount += $c->rate*$c->count;

        }

       // dd($uniques );

        $user_count = count($uniques);
      

        $overtimes =  $overtimes->paginate(10)->appends(Input::except('page'));

        return view('admin.searches_other.index',compact('sessions','added_bies','overtimes','user_count', 
                                                   'total_overtimes', 'total_amount'));
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
cannot trust form no, as a user might have started a form, but waited long to submit it. so submit date is the key.
*/
        
        $overtimes = OvertimeOther::with('form')
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
                  
          
            $overtimes = $overtimes->wherehas( 'form', function($q) use ($date){
                                $q->where( 'form_no', '<=', $formno_before );
                                }); 

            $formno_before = '&formno_before='.$formno_before;
        }
        if (Input::filled('formno_after')){
                  
          
            $overtimes = $overtimes->wherehas( 'form', function($q) use ($date){
                                $q->where( 'form_no', '>=', $formno_after );
                                }); 

            $formno_after = '&formno_after='.$formno_after;
        }



     
        $overtimes =  $overtimes
                                ->orderBy('pen', 'asc')->get(); //this will be helpful to make sure duplicate pen_rate are side by side
     

        $filename = "otherdept_ot-{$session}-";

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
        $csvExporter->build($overtimes, [ 'form.form_no', 'form.creator','form.id','form.overtime_slot',
                            'form.session', 'form.duty_date', 'form.date_from', 
                            'form.date_to','form.submitted_by','form.submitted_on',
                             'id', 'pen',  'from', 'to','count'
                            ]);
        //rate is not needed. we store rates in seperate csv so we can adjust to changes in rate later

        $csvExporter->download($filename);

        /*$lastexported = Setting::firstOrCreate( ['name' => $session.'-odlastexporttime'], 
                                                ['value' => Carbon::now() ]);
        $lastexported->value = Carbon::now();
        $lastexported->save();*/

        

    }




    public function download_desig()
    {
        

        $desig = \App\DesignationsOther::orderby('rate','desc')->orderby('designation','asc')->get();

        $filename =  'otherdept_designation_rates-'.  date('Y-m-d') . '.csv';
        
        $csvExporter = new \Laracsv\Export();


        $csvExporter->build($desig, [ 'designation','rate'  ]);

        $csvExporter->download($filename);


    }

    

    public function download_emp()
    {
        

        $desig = \App\EmployeesOther::orderby('pen','asc')->get();

        $filename =  'otherdept_employees-'.  date('Y-m-d') . '.csv';
        
        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($desig, [ "srismt","name","pen","department_idno","account_type","ifsc","account_no","mobile","added_by"  ]);

        $csvExporter->download($filename);


    }

}
