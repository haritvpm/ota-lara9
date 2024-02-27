<?php

namespace App\Http\Controllers\Admin;

use PDF;
use Auth;
use App\Form;
use Exception;
use JavaScript;
use App\Calender;
use App\Employee;

use App\Overtime;
use App\Punching;
//use Yajra\DataTables\DataTables;
//use Laracasts\Utilities\JavaScript\JavaScriptFacade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\OvertimeSitting;
use Illuminate\Support\Facades\Gate;


class MyForms2Controller extends Controller
{
    public function index(Request $request)
    {
        if (! Gate::allows('my_form_access')) {
            return abort(401);
        }
        
   
       $begintime = microtime(true);


        //user can login and see forms even after data entry is disabled
        //$session_array = \App\Session::whereDataentryAllowed('Yes')->pluck('name');

        
        $session_array = \App\Session::where(function ($q) {
            $q->where('kla','>', 15)
            ->orWhere(function ($q) {
                $q->where('kla', 15)
                ->Where('session','>=', 10);
            });
        })
        ->whereshowInDatatable('Yes')->latest()->pluck('name');

        if(auth()->user()->isAudit()){
            $session_array = \App\Session::latest()->pluck('name');

        }


        $forms = Form::with(['created_by','owned_by'])
                     ->withCount('overtimes')
                     ->whereIn('session',$session_array)
                     ->CreatedOrOwnedOrApprovedByLoggedInUser()
                     ->where('creator','<>','admin') //exclude PA2MLA forms created by admin
                     ->when(\Auth::user()->isAudit(),
                            function($q){
                                return $q->where('form_no','>=', 0);
                           });


        
        // FILTERS
        $str_session = null;                 
        $str_status = null;
        $str_overtime_slot = null;
        $str_datefilter = null;
        $str_namefilter = null;
        $str_desigfilter = null;
        $str_idfilter = null;
        $str_created_by = null;
            
        $str_submittedbyfilter=null;

        $session = $request->query('session');
        $overtime_slot = $request->query('overtime_slot');
        $status =  $request->query('status');
        $datefilter =  $request->query('datefilter');
        $namefilter =  $request->query('namefilter');
        $desigfilter =  $request->query('desigfilter');
        $idfilter   =  $request->query('idfilter');
        $createdby =  $request->query('created_by');
       
        $submittedbyfilter = $request->query('submittedbyfilter');


        if ($request->filled('created_by')){ 
                           
            if($createdby != 'all'){
                //$forms->where( 'creator', $createdby);
                $forms->where( 'creator', 'like', '%'.$createdby); //accomodate de.
                                         
                $str_created_by = '&created_by='.$createdby;
            }

            
        }
         

        //undersec should be able to view any new forms from prev sessions if there are no session filter
        
        if ($request->filled('session')){
             
            $forms->where('session',$session);
            $str_session = '&session='.$session;
 
        }
        else{
           // $session = $session_array[0];
        }

        //tab takes care of status. 
        
        if (!$request->filled('status')){
            if(!auth()->user()->isAdminorAudit()){
                $status = 'todo';
            }
            else{
                
                if(auth()->user()->isAudit()){
                    $status = 'Submitted';
                }
                else
                if(auth()->user()->isAdmin()){
                   // $status = 'todo';
                }
            }
        }
        else{
            if(auth()->user()->isAudit()){
                $status = 'Submitted';
            }
            else
            if(auth()->user()->isAdmin()){
                // $status = 'Submitted';
            }
        }

                

        $forms->filterStatus($status);

        $str_status = '&status='.$status;
                         


        if ($request->filled('idfilter'))
        {
            $forms->where(function($query) use ($idfilter)
                    {
                      $query->where('id',$idfilter)
                           ->orwhere('form_no', $idfilter);
                    });
                   
            $str_idfilter = '&idfilter='.$idfilter;
            
        }


        if ($request->filled('overtime_slot')){
            if($overtime_slot == 'Non-Sittings'){
                $forms->where( 'overtime_slot' , '!=', 'Sittings' );    
            }
            else
            if($overtime_slot == 'Withheld'){
                $forms->where( 'form_no' , '<=', 0 );    
            }
            else
            {
                $forms->whereOvertimeSlot('Multi');
            }

            $str_overtime_slot = '&overtime_slot='.$overtime_slot;
        }

        
        if ($request->filled('datefilter')){
            
            $dates = null;
            if( strcasecmp($datefilter, 'S') == 0  || 
                strcasecmp($datefilter, 'NS') == 0 || 
                strcasecmp($datefilter, 'H') == 0  || 
                strcasecmp($datefilter, 'W') == 0  )
            {
                $session = \App\Session::where('name', $session )->first(); 

                if( strcasecmp($datefilter, 'S') == 0)  {

                    $dates = $session->calender()->where( 'day_type','Sitting day')
                                                ->pluck('date');
                } 
                else if( strcasecmp($datefilter, 'H') == 0){

                    $dates = $session->calender()->where( 'day_type','Prior holiday')
                                    ->orwhere( 'day_type','Holiday')
                                    ->pluck('date');
                } 
                else if( strcasecmp($datefilter, 'W') == 0)  {

                    $dates = $session->calender()->where( 'day_type','Prior Working day')
                                            ->orwhere( 'day_type','Intervening saturday')
                                            ->orwhere( 'day_type','Intervening Working day')
                                            ->pluck('date');
                }
                else {
                    $dates = $session->calender()->where( 'day_type', '<>', 'Sitting day')
                                            ->pluck('date');
                }

            }

            $forms->filterDate( $datefilter, $dates );

            $str_datefilter = '&datefilter='.$datefilter;
        }

        if ($request->filled('submittedbyfilter')){
                      
            $forms->where( 'submitted_by', 'like', '%' . $submittedbyfilter.'%' );
                             
            $str_submittedbyfilter = '&submittedbyfilter='. $submittedbyfilter;
        }
 

        if ($request->filled('namefilter') || $request->filled('desigfilter')){
            $forms->with('overtimes');
        }

        if ($request->filled('namefilter')){
                    
            $forms->wherehas( 'overtimes', function($q) use ($namefilter){
               $q->where('pen','like', '%' . $namefilter.'%' )
                ->orwhere('name','like', '%' . $namefilter.'%' );
            }); 
                
            $str_namefilter = '&namefilter='. $namefilter;
        }


        if ($request->filled('desigfilter')){
                        
             $forms->wherehas( 'overtimes', function($q) use ($desigfilter){

                if( strpos($desigfilter, '^') === 0  )
                {
                   $q->where('designation', 'like',  substr($desigfilter,1).'%' );
                 
                }
                elseif( strpos($desigfilter, '=') === 0  )
                {
                    $q->where('designation',  'like', substr($desigfilter,1) );
                 
                }
                else
                {
                    $q->where('designation', 'like', '%' . $desigfilter.'%' );
                 
                }

               /* $q->where('designation','like', '%' . $desigfilter.'%' );*/


             }); 
                 
             $str_desigfilter = '&desigfilter=' . $desigfilter;
         }
     

        $sort =  $request->filled('sort') ? $request->query('sort') : 'updated_at'; // if user type in the url a column that doesnt exist app will default to id
        $order = $request->query('order') === 'asc' ? 'asc' : 'desc'; // default desc
                
        $forms = $forms->orderBy($sort, $order)->distinct()->paginate(10)
                                               ->appends($request->except('page'));
        $to_approve = 0; 
        //sections and admins have nothing to approve
        if ( auth()->user()->isDataEntryLevel() || auth()->user()->isAdminorAudit()) {
           // It starts with 'http'
            $to_approve = -1;
        }

        $pending_approval = 0;
        if ( auth()->user()->isFinalLevel() ) {
           // It starts with 'http'
            $pending_approval = -1;
        }
        

        //this inverts sorting order for next click                                       
        $querystr = '&order='.($request->query('order') == 'asc' || null ? 'desc' : 'asc').$str_session.$str_status.$str_overtime_slot.$str_datefilter.$str_namefilter.$str_desigfilter.$str_idfilter.$str_created_by.$str_submittedbyfilter;

        $added_bies = \App\User::SimpleUsers()
                                 ->where('username','not like','de.%')
                                 ->orderBy('name','asc')
                                ->get(['username','name'])->pluck('name','username');
       
        $added_bies->put( 'de.sn.protocol' , 'Protocol');
        
        JavaScript::put([
           'adminoraudit' => auth()->user()->isAdminorAudit(),
          
            
        ]);

        $timetaken = round(microtime(true) - $begintime,4);

        return view('admin.my_forms2.index',compact('forms','querystr', 'to_approve',  'pending_approval', 'session_array','session','added_bies', 'timetaken' ));
    }



    public function preparevariablesandGotoView( $issitting, $id=null, $id_to_copy = null )
    {
        $enum_overtime_slot = Form::$enum_overtime_slot;

        $q = \App\Session::with('calender')->whereDataentryAllowed('Yes'); 
            
      
        $q = $q->latest();

        $session_array = $q->get();

        //if sitting, prevent ongoing sessions
        if($issitting){
            $session_array = $session_array->filter(function ($value) {
                $maxdate = \App\Calender::where('session_id',$value->id)->max('date');
                
                return $maxdate <= Carbon::now();
            });
           
        }

        $sessions = $session_array->pluck('name');

        $latest_session = $sessions->first();
         
        $calenderdaysmap = [];
        $calenderdaypunching = [];
        $daylenmultiplier = [];
        $calenderdays2 = array();
     
        foreach ($session_array as $session) {
    
            $daysall = $session->calender()->orderby('date','asc');
                       
            if(!$issitting)
            {
                $daysall->where( function ($query) {
                    $query->wherenot('punching','AEBAS_FETCH_PENDING') 
                        ->orwherenull('punching') ;
                })->where('date', '<=', date('Y-m-d'));
                
                $days = $daysall->get();
            }
            else{
                $days = $daysall->where( 'day_type','Sitting day')->get();
            }

            
            foreach ($days as $day) {
              
                $calenderdaypunching[$day['date']] = $day['punching'] ?? 'NOPUNCHING';
                $calenderdaysmap[$day['date']] = $day['day_type'];
                $daylenmultiplier[$day['date']] = $day['daylength_multiplier'] ?? 1.0;

                $calenderdays2[$session->name][] = $day['date'];    
            }
        }

       
      //hard corded. ugly i know. 
        $ispartimefulltime = 0;
        $iswatchnward = 0;
      //  $isspeakeroffice = 0;


        if(!$issitting && $id)
        {
           $form = Form::findOrFail($id);
           
           //we should set parttime even if it is being edited by house keeping

            if(false !== strpos( $form->creator, 'health') || 
               false !== strpos( $form->creator, 'agri' )){
                $ispartimefulltime = 1;            
            }

            if(false !== strpos($form->creator, 'watchnward')){
                $iswatchnward = 1;
            }

            if(false !== strpos($form->creator, 'sn.am') || 
               false !== strpos($form->creator, 'sn.ma')){
                $ispartimefulltime = 1;
            }

        }

        if( false !== strpos(  \Auth::user()->username, 'watchnward') ){
            $iswatchnward = 1;
        }

       // if( false !== strpos(  \Auth::user()->username, 'oo.') ){
        //    $isspeakeroffice = 1; //dyspkr and sec too
       // }

        //amhostel and sn.mae and sn.amresspkr has parttimes too
        
        if( false !== strpos( \Auth::user()->username, 'health' ) || 
            false !== strpos( \Auth::user()->username, 'agri') || 
            false !== strpos( \Auth::user()->username, 'sn.am') || 
            false !== strpos( \Auth::user()->username, 'sn.ma')
             ){

            $ispartimefulltime = 1;            
        }

       // $designations = \App\Designation::orderby('designation','asc')->get(['designation'])->pluck('designation');

        $data["calenderdaysmap"] = json_encode($calenderdaysmap);
      //  $data["designations"] = json_encode($designations); //we no longer allow user to set deisg, it is auto selected
        $data["calenderdays2"] = json_encode($calenderdays2);
        $data["calenderdaypunching"] = json_encode($calenderdaypunching);
        
        $data["daylenmultiplier"] = json_encode($daylenmultiplier);

        $presets = \App\Preset::
                  where('user_id',\Auth::user()->id)
                ->where('name','not like', 'default_%')
                ->pluck('name');
       
        $presets_default = \App\Preset::
                  where('user_id',\Auth::user()->id)
                ->where('name','like', 'default_%')
                ->pluck('pens','name');

       
        //user wants to copy a form
        $autoloadpens = null;

        if($id_to_copy != null){
            $formtocopy = Form::with(['created_by','overtimes','overtimes.employee.categories','overtimes.employee.designation'])->findOrFail($id_to_copy);
            
            $autoloadpens = $formtocopy->overtimes()->get();
            
            $autoloadpens = $autoloadpens->mapWithKeys(function ($item) {
               
                return [$item['pen'] .'-' . $item['name'] => 
                [
                    'desig' => $item['designation'],
                    'category' =>  $item?->employee?->categories?->category,
                    'employee_id' => $item?->employee?->id,
                    'punching'   => ($item?->employee?->categories?->punching ?? true) && ($item?->employee?->designation?->punching ?? true),
                    'aadhaarid' => $item?->employee?->aadhaarid,
                    'normal_office_hours' =>   $item?->employee?->designation?->normal_office_hours,
                    'slots' =>  explode(';',  $item->slots) ,
                ]
            ];
                
            });
            
        }
       
        JavaScript::put([
            'latest_session' => $latest_session,
            'old_slotselected' => old('overtime_slot') ? old('overtime_slot') : '',
            'old_calenderdayselected' => old('duty_date') ? old('duty_date') : '',
            'presets' => $presets,
            'ispartimefulltime' => $ispartimefulltime,
            'iswatchnward' => $iswatchnward,
          //  'isspeakeroffice' => $isspeakeroffice,
            'autoloadpens' => $autoloadpens,
            'presets_default' => $presets_default,
            
        ]);
    
        $collapse_sidebar = true;
        if(!$issitting){
            if($id)
            {
                $form = Form::with(['created_by','overtimes','overtimes.employee.categories','overtimes.employee.designation'])->findOrFail($id);
//Log::info($form);
                $form->overtimes->transform(function ($item) use ($form) {
                    if($item['name'] != null){
                        $item['pen'] = $item['pen'] . '-' . $item['name'];
                        // $item['allowpunch_edit'] = $item['punching_id'] == null; //otherwise it will be disabled on edit

                    }
                    $item['category'] = $item?->employee?->categories?->category;
                    $item['normal_office_hours'] = $item?->employee?->designation?->normal_office_hours;
                    $item['aadhaarid'] = $item?->employee?->aadhaarid;
                    $item['punching'] = $item?->employee->categories?->punching &&
                                        $item?->employee->designation->punching && $item?->employee->punching;
                    $item['slots'] =  explode(';',  $item->slots);
                    //if there is punching id, load punching time readlonly too

                    if( $item['punching_id']  ){
                        $punch = Punching::find($item['punching_id'] );
                        if($punch){
                            $item['punchin_from_aebas'] = $punch->punchin_from_aebas == 1;
                            $item['punchout_from_aebas'] =  $punch->punchout_from_aebas == 1;
                        }
                    }

                  
                    
                    return $item;
                                    
                });

                                    
                return view('admin.my_forms2.edit', compact('form', 'data','sessions', 'collapse_sidebar' ));
            }
            else
            {
                return view('admin.my_forms2.create', compact('data','sessions', 'collapse_sidebar' ) );
            }
       }
       else{ //sitting forms
            if($id)
            {
                $form = Form::with(['created_by','overtimes','overtimes.employee.categories','overtimes.employee.designation',
                'overtimes.overtimesittings'])->findOrFail($id);

                $form->overtimes->transform(function ($item) {
                    if($item['name'] != null){
                        $item['pen'] = $item['pen'] . '-' . $item['name'];
                    }
                    $item['category'] = $item?->employee?->categories?->category;
                    $item['aadhaarid'] = $item?->employee?->aadhaarid;
                    $item['punching'] =  $item?->employee->categories?->punching &&
                                         $item?->employee->designation->punching && $item?->employee->punching;
                    $item['normal_office_hours'] = $item?->employee?->designation?->normal_office_hours;

                   // $item['overtimesittings_'] =$item?->overtimesittings->pluck('date');


                 //   Log::info($item['overtimesittings']);
                    
                    return $item;
                                   
                });

                                    
                return view('admin.my_forms2.edit_sitting', compact('form', 'data','sessions', 'collapse_sidebar' ));
            }
            else
            {
                return view('admin.my_forms2.create_sitting', compact('data','sessions', 'collapse_sidebar') );
            }
        }   
       
                
    }

    /**
     * Show the form for creating new Form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('my_form_create')) {
            return abort(401);
        } 
    
        return $this->preparevariablesandGotoView( false, null);
       
    }
    public function create_copy($id)
    {
        if (! Gate::allows('my_form_edit')) {
            return abort(401);
        }  
        
        //$form = Form::findOrFail($id);
      
        return $this->preparevariablesandGotoView(false, null, $id ) ;
        
    }


    public function createovertimes( Request $request, &$myerrors, $formid=null)
    {
        
        $date = Carbon::createFromFormat(config('app.date_format'), $request['duty_date'])->format('Y-m-d');

      
           

        $collection = collect($request->overtimes);
        $collection->transform(function($overtime) 
        {
            $tmp = strpos($overtime['pen'], '-');
            if(false !== $tmp){
                //note change name first before changing pen itself
                $overtime['name'] =  substr($overtime['pen'], $tmp+1 );
                $overtime['pen'] =   substr($overtime['pen'], 0, $tmp );
            }

            return $overtime;
        });


        $designations = $collection->pluck('designation');

        $rates = \App\Designation::wherein ('designation', $designations )->pluck('rate','designation');
        $calender_day = \App\Calender::where('date', $date)->first();
        //$isHolidey = str_contains($calender_day->day_type,'oliday');
       //\Log::info(print_r($rates, true));
      
       

///////////////

        $pens = $collection->pluck('pen');
        /*$pens->transform(function ($item, $key) {
            $tmp = strpos($item, '-');
            if(false !== $tmp){
                return  substr($item,0, $tmp);
            }

            return $item;
            
        });*/

        //$placeholder = implode(', ', array_fill(0, count($pens), '?')); //returns '?, ?'
        //find if the same employee has occupied this slot on this day
        //commented as no time to write and test pens only, not PEN-NAME, so we can change names
        //if no - is found, SUBSTRING_INDEX returns whole string
        $res =  \App\Overtime::with('form')
                    //->whereRaw("SUBSTRING_INDEX(`pen`, '-' ,1) in ($placeholder)",$pens )
                    ->wherein('pen',$pens )
                    ->whereHas('form', function($query)  use ($request,$date,$formid) { 
                          $query->where('duty_date', $date)
                                ->where('overtime_slot', 'Multi')
                                ->where('session', $request['session'])
                                ->where('id', '!=', $formid); //skip this item if on update
                    })->get();
    
        
        ///////////////////////////////

        $designations = $collection->pluck('designation');

        $rates = \App\Designation::wherein ('designation', $designations )->pluck('rate','designation');
     
        $overtimes = $collection->transform(function($overtime)  
                                            use ($res, $request,$date, &$myerrors,$formid,$rates,$calender_day) 
        {
            $pen = $overtime['pen'];
            $tmp = strpos($pen, '-');
            if(false !== $tmp){
                $pen = substr($pen, 0, $tmp);
            }

            //all ots for this particular employee only
            $emp_ots = $res->reject(function($element) use ($pen) {
                //return strpos($element['pen'], $pen) === false;
                return strncasecmp($element['pen'], $pen, strlen($pen)) != 0;
            });

                    
           
            $otcount = 0;
            $emp_ots->each(function ($element) use ($request, $overtime,&$myerrors,&$otcount) {
                // \Log::info(explode(';', $element->slots));
                // \Log::info($overtime['slots']);
                $common = array_intersect( explode(';', $element->slots), $overtime['slots']);
                if (count($common)) {
                    array_push($myerrors, 'Already entered ' . implode(',',$common)  . ' OT for this day for '  .  $overtime['pen'] . '-' . $overtime['name']. ' (' . $element->form->creator . ' )' );
                }
                $otcount += $element->count;
            });  
                                   
            if( $otcount >= 3  )
            {
                //list($pen, $name) = array_map('trim', explode("-", $overtime['pen']));
                array_push($myerrors, $overtime['name'] . ' -' . $overtime['pen'] . ' : 3 OTs already entered for the day');
              //  return null;
            }

            if (count($myerrors)) {
                return null;
            }  

            $strtimes_totimestamps = function ( $from, $to )  {
              $timefrom = strtotime($from);   $timeto = strtotime($to);
               if($timeto <= $timefrom){  $timeto += 24*60*60; }
               return [ $timefrom,$timeto ];
            };

    
           [$timefrom_comp, $timeto_comp] = $strtimes_totimestamps( $overtime['from'], $overtime['to']);

            //check overlap with other OTS of this day for this employee
            foreach ($emp_ots as $e) {
          
                [$timefrom, $timeto] = $strtimes_totimestamps($e['from'], $e['to']);

                /*
                 $isoverlap = ($timefrom > $timefrom_comp && $timefrom < $timeto_comp) ||  ($timefrom_comp > $timefrom && $timefrom_comp < $timeto) || 
                   ( $timefrom == $timefrom_comp ||  $timeto  ==  $timeto_comp ) ;
                */
                $isoverlap = (($timefrom < $timeto_comp) && ($timeto > $timefrom_comp)) || 
                              ($timefrom == $timefrom_comp) ||  ($timeto == $timeto_comp) ;

                if($isoverlap){
                     //list($pen, $name) = array_map('trim', explode("-", $overtime['pen']));
                    array_push($myerrors, $overtime['name'] . '-' . $overtime['pen'] . ' : Times overlap with another OT from ' . $e['from'] . ' - ' . $e['to'] .  ' on this day (' . $e->form->creator . ' )' );
                    return null;
                }

            } 
           
            //check if time is the minimum recommended
            /* incomplete. grace time of 10 min for whole day. instead allow for each OT
            $normal_office_hours = $overtime['normal_office_hours'];
          //  dd( $normal_office_hours);
         
            $needed_mins_forthisOT = $calender_day->isHoliday ? 180 : 150;
            if( $request['overtime_slot'] === 'First' ){
                $needed_mins_forthisOT += $normal_office_hours*60* $calender_day->daylength_multiplier;
            }

            $mins_forthisOT = ceil(abs($timeto_comp - $timefrom_comp) / 60);
           
           // array_push($myerrors,$mins_forthisOT .'-' .$needed_mins_forthisOT );
       
            if( $mins_forthisOT <  $needed_mins_forthisOT){
                //check grace time by adding times for all OTs.
                $total_time_allotherOTs = $emp_ots->sum(function ($ot) use($strtimes_totimestamps) {
                    [$timefrom_thisOT, $timeto_thisOT] = $strtimes_totimestamps( $ot['from'],$ot['to']);
                    return ceil(abs($timeto_thisOT - $timefrom_thisOT) / 60);
                });
            }
            */
            //check if time is within punching time
            //todo get actual time from db, to prevent fronend tampering
            if($overtime['punching'] &&  $calender_day->punching == 'AEBAS'){
                $aadhaarid = $overtime['aadhaarid'];
                $pquery = Punching::where('date',$date);
                $pquery->when( $aadhaarid && strlen($aadhaarid) >= 8, function ($q)  use ($aadhaarid){
                    return $q->where('aadhaarid',$aadhaarid);
                });
                $punch = $pquery->first(); 
                // Log::info($punch);
                [$punchin, $punchout] = $strtimes_totimestamps( $punch['punch_in'], $punch['punch_out']);

                if( !$punchin || !$punchout || !$timefrom_comp || !$timeto_comp ){
                        array_push($myerrors, $overtime['name'] . ' -' . $overtime['pen'] . ' : Invalid times');
                        return null;
                }
                if(  $timefrom_comp <  $punchin || $timeto_comp > $punchout ){
                        array_push($myerrors, $overtime['name'] . ' -' . $overtime['pen'] . ' : Time not within punching time');
                        return null;
                }
            }


            {
                        
                return new Overtime([
                    'pen'           => $overtime['pen'],
                    'name'          => $overtime['name'],
                    'designation'   => $overtime['designation'],
                    'from'          => $overtime['from'],
                    'to'            => $overtime['to'], 
                    'worknature'    => $overtime['worknature'] ?? '',
                    'count'         => count( $overtime['slots']),
                    'rate'          => $rates[$overtime['designation']],
                    'punching'       => $overtime['punching'],
                    'punchin'       => $overtime['punchin'],
                    'punchout'       => $overtime['punchout'],
                    'punching_id'    => $overtime['punching_id'] ?? null,
                    'employee_id' => $overtime['employee_id'],
                    'slots' =>  implode(';',  $overtime['slots']) ,
                    ]);

            }
            

        });
    
        return $overtimes;
           
    }
   
    /**
     * Store a newly created Form in storage.
     *
     * @param  \App\Http\Requests\StoreFormsRequest  $request
     * @return \Illuminate\Http\Response
     */
    //public function store(StoreFormsRequest $request)
    public function store(Request $request)
    {
        
        if (! Gate::allows('my_form_create')) {
           // return abort(401);
           return response('Unauthorized.', 401);
        }

        
        
        
        $myerrors = [];

        $overtimes = $this->createovertimes( $request, $myerrors );

       
        if( count($myerrors) > 0) 
        {
            return response()
            ->json(['products_empty' => $myerrors], 422);
        }



        if($overtimes->isEmpty()) 
        {
            return response()->json(['products_empty' => ['One or more row is required.']
                    ], 422);
        }
      
        $formid = \DB::transaction(function() use ( $request, $overtimes)  {

           $form = Form::create( [
               
                'session' => $request['session'],
                'creator' => \Auth::user()->username,
                'owner'=> \Auth::user()->username,
                'duty_date'  => $request['duty_date'],
                'overtime_slot' => $request['overtime_slot'],
                'remarks' => $request['remarks'],
                'worknature'    => $request['worknature'],
            ]);

            $form->overtimes()->saveMany($overtimes);
          
            return $form->id;
        });

        /* no saving manual punch times
        try  {
        //using try catch because this can cause exception if we try to save a 2nd form during manual edit of punching times
           
            $date = Carbon::createFromFormat(config('app.date_format'), $request['duty_date'])->format('Y-m-d');
            //also save punchtime to punching table if this is manual entry day
            $calenderdate = Calender::where('date', $date)->first();

            if( $calenderdate?->punching == 'MANUALENTRY' ){
                             
                $collection = collect($overtimes);

                $punchtimes =  $collection->map( function($overtime) use ($date) {
                // date shoulbe in 'Y-m-d', because insert/createMany of laravel is undefined.
                //so we have to use Punching::insert which does not call our Model's setDateAttribute
                    return [
                      //  'session' => $request['session'],
                        'creator' => \Auth::user()->username,
                        'date'  => $date,
                        'pen'  => $overtime['pen'],
                        'aadhaarid'  => $overtime['aadhaarid'], //composite keys wont work if we give null. so something else
                        'name'  => $overtime['name'],
                        'punch_in'  => $overtime['punchin'],
                        'punch_out' => $overtime['punchout'],
                        
                    ];
                } );
                
                $punchtimes->filter( function ($overtime) {
                    return $overtime['aadhaarid'];
                });

                if($punchtimes->count()) {
                    Punching::insert($punchtimes->toArray());
                }

            }
        } catch(Exception $e){
            //do nothing. as this may be due to an already existing punching in db for the date and PEN composite key
        }
        */

       
        $request->session()->flash('message-success', 'Created form no:' . $formid ); 
        
        return response()->json([
           'created' => true,
           'id' => $formid
        ]);

    }


    /**
     * Show the form for editing Form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         if (! Gate::allows('my_form_edit')) {
            return abort(401);
        }       
        
        $form = Form::findOrFail($id);

        $issittingday = ($form->overtime_slot == 'Sittings');

        return $this->preparevariablesandGotoView($issittingday, $id ) ;
        
    }

    /**
     * Update Form in storage.
     *
     * @param  \App\Http\Requests\UpdateFormsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (! Gate::allows('my_form_edit')) {
            return abort(401);
        }

        $form = Form::findOrFail($id);

        if( ($form->owner != \Auth::user()->username) && !\Auth::user()->isAdmin())
        {
            return abort(401);
        }


       // $date = Carbon::createFromFormat(config('app.date_format'), $request['duty_date'])->format('Y-m-d');

        $myerrors = [];
        
        $overtimes = $this->createovertimes( $request, $myerrors, $id );

        if( count($myerrors) > 0) 
        {
            return response() ->json(['products_empty' => $myerrors ], 422);
        }


        if($overtimes->isEmpty()) {
            return response()->json([
                'products_empty' => ['One or more row is required.']
            ], 422);
        }
              

        $formid = \DB::transaction(function() use ($form, $request, $overtimes) {
            
            //no need to update creator and owner
            $form->update( [
                'session' => $request['session'],
                'duty_date'  => $request['duty_date'],
                'overtime_slot' => $request['overtime_slot'],
                'remarks' => $request['remarks'],
                'updated_at' => Carbon::now(),
                'worknature'    => $request['worknature'],

            ]);

            //see if user has made any changes

            $overtimes_old = Overtime::where('form_id', $form->id)->get();
            //same number or added new items
            if( $overtimes_old->count() && $overtimes->count()){

                //update same row indices
                $i=0;
                $same_rows = min($overtimes_old->count(), $overtimes->count());
                for (; $i < $same_rows; $i++) { 
                   Overtime::where('id', $overtimes_old[$i]['id'])
                            ->update($overtimes[$i]->toarray());
               
                }
                
                if($overtimes_old->count() < $overtimes->count()){
                    //update if a new row added
                    $overtimes = $overtimes->slice($i);
                    $form->overtimes()->saveMany($overtimes);
                } else if ( $overtimes_old->count() > $overtimes->count()) {
                    //remove rows removed
                    $idsremoved = $overtimes_old->slice($i)->pluck('id')->toarray();
                    Overtime::wherein('id', $idsremoved)
                            ->where('form_id', $form->id)->delete();
                }

                return $form->id;
            }
            

            //old code, where we delete everything and add. unreachable code
            Overtime::where('form_id', $form->id)->delete();

            $form->overtimes()->saveMany($overtimes);

            return $form->id;

        });

        /* NO updating manual time
        //update ot times if user is the one who created it in the first place
        //using try catch because this can cause exception if we try to save a 2nd form during manual edit of punching times
        //try  
        {

            $date = Carbon::createFromFormat(config('app.date_format'), $request['duty_date'])->format('Y-m-d');

            //also save punchtime to punching table
            $calenderdate = Calender::where('date',$date)->first();
            
            if( $calenderdate?->punching == 'MANUALENTRY' )
            {
                $collection = collect($overtimes);
// \Log::info($date );
                $punchtimes =  $collection->map( function($overtime) use ($date) {
                //date has  to be 'Y-m-d' here, because createMany of laravel is undefined.
                //so we have to use Punching::upsert which does not call our Model's setDateAttribute

                    return [
                     //   'session' => $request['session'],
                        'creator' => \Auth::user()->username,
                        'date'  => $date,
                        'pen'  => $overtime['pen'],
                        'name'  => $overtime['name'],
                        'aadhaarid'  => $overtime['aadhaarid'] ?? '-', //date-aadhaarid composite keys wont work if we give null. so something else
                        'punch_in'  => $overtime['punchin'],
                        'punch_out' => $overtime['punchout'],
                     //   'punching_id' => $overtime['punching_id'],
                        
                    ];
                } );
                // the second argument lists the column(s) that uniquely identify records within the associated table. The method's third and final argument is an array of the columns that should be updated if a matching record already exists in the database.
                //only allow punching time update if it was the original section whose data we saved.
                //this does not affect ot form as they have their own
                \App\Punching::upsert($punchtimes->toArray(), ['date', 'aadhaarid'], ['punch_in', 'punch_out' ]);
                //\App\Punching::upsert($punchtimes->toArray(), ['date', 'pen', 'aadhaarid'], ['punching_id','creator','punch_in', 'punch_out','aadhaarid' ]);
            }

        }
        // catch(Exception $e)
        {
            //do nothing. as this may be due to an already existing punching in db for the date and PEN composite key
        }
        */
        

        $request->session()->flash('message-success', 'Updated form-no: ' . $formid ); 


        return response()->json([
            'created' => true,
            'id' => $formid
         ]);

       // return redirect()->route('admin.my_forms2.index');
    }


    /**
     * Display Form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('my_form_access')) {
            return abort(401);
        } 



        //$overtimes = \App\Overtime::where('form_id', $id)->get();

        $form = Form::with(['created_by','owned_by', 'overtimes','overtimes.employee.categories','overtimes.employee.designation'])->findOrFail($id);
        $overtimes = $form->overtimes;
        $hasOnlySittings = true;
        $overtimes->transform(function($overtime) use ( $form, &$hasOnlySittings )
        {
            $tmp = strpos($overtime['pen'], '-');
            if(false === $tmp){
                $overtime['pen'] .=   '-' . $overtime['name'];
            }

            $overtime['category'] = $overtime?->employee?->categories?->category;
            $overtime['aadhaarid'] = $overtime?->employee?->aadhaarid;
            $overtime['punching'] = $overtime?->employee->categories?->punching &&
                                    $overtime?->employee->designation->punching && $overtime?->employee->punching;
            $overtime['normal_office_hours'] = $overtime?->employee?->designation?->normal_office_hours;

	        if($form->overtime_slot == 'Multi'){ 
                //this is for display only
                //make ot slots in the correct order
                $slots = [];
                if( str_contains($overtime->slots,'First')){
                    if( str_contains($form->day_type(),'S') ){
                        $slots[] =  'Sitting';
                        
                    } else {
                        $slots[] =  'First';
                        $hasOnlySittings = false;
                    }
                }
                else {
                    $hasOnlySittings = false; //something else. minimum one is ensured
                }

                if( str_contains($overtime->slots,'Second')) $slots[] = 'Second';
                if( str_contains($overtime->slots,'Third')) $slots[] = 'Third';
                if( str_contains($overtime->slots,'Additional')) $slots[] = 'Addl';
        
                $overtime['slots'] = implode(', ',$slots);
	        }
            else {
                $form->overtimes?->load('overtimesittings');
              //  $overtime['overtimesittings_'] =$overtime?->overtimesittings->pluck('date');

            }
            return $overtime;
        });



        $daytype = null;

        //find forwardable users.
        $loggedinusername = \Auth::user()->username;

        $forwardarray = null;

        

        if($loggedinusername == $form->owner && $form->owner != 'admin'){

            $routes = \Auth::user()->routing->forwardable_usernames();

            //now we need to fetch all the user displaynames

            $forwardarray = \App\User::whereIn( 'username',$routes )
                                      ->orderby('name','asc')->get(['username','name','displayname']);
            

            $forwardarray = $forwardarray->mapWithKeys(function ($item) {
                //if we our username is based on a name of person,
                //show name of person first
                //else show user title first

                //names of JS and above have PEN after pipe
                $name = $item->Title;
                // $pipe = strpos($name, '|');
                // if($pipe !== false){
                //     $name = substr($name, 0, $pipe );
                // }

                if( strpos($item['username'], '.') === false ){

                    //Formatting does not work with FF 10
                   //return [$item['username'] => ($item['displayname'] ?  '<strong>' . $item['displayname'] . '</strong>, ' : '') . $item['name']  ];

                     return [$item['username'] => ($item['displayname'] ?  $item['displayname'] . ', ' : '') . $name ];
                }
                else{

                    //this does not work with FF 10
                    //return [$item['username'] => '<strong>' . $item['name'] . '</strong>' . ($item['displayname'] ? (', ' . $item['displayname'] ) : '') ];

                  return [$item['username'] => $name . ($item['displayname'] ? (', ' . $item['displayname']  ) : '') ];


                }
            });

        }

        
       
        $cansubmittoaccounts = false;
        if(!\Auth::user()->isAdminorAudit()){
            //see if form has OTS other than sittings. if so, it can be submitted only by ds
            if( $hasOnlySittings ){
                $cansubmittoaccounts= \Auth::user()->routing->cansubmit_to_accounts("Sittings");
            } else {
                $cansubmittoaccounts= \Auth::user()->routing->cansubmit_to_accounts($form->overtime_slot);
            }
        }

    
        $descriptionofday = '';
        $needsposting = false;
        
        $dayhaspunching = true;

        if($form->overtime_slot == 'Multi'){
            $date = Carbon::createFromFormat(config('app.date_format'), $form->duty_date)->format('Y-m-d');
            $calender = Calender::where('date', $date )->first();
            $dayhaspunching =  $calender->punching !== 'NOPUNCHING';
            $daytype = $calender->day_type;
            $descriptionofday = $calender->description;
        } else{
            $dayhaspunching = false;
        }
      

        if($form->overtime_slot != 'Sittings' && $form->owner == $loggedinusername && $form->owner != 'admin'){
            $needsposting = \App\User::needsPostingOrder($form->creator); 
        }
       
        
        $submmittedby = $form->SubmitedbyNames;
        $createdby = $form->FirstSubmitedbyName;
        //if the form has still not been submitted, $createdby will be empty as FirstSubmitedbyName is null
            //in that case, get name from creator
        if($createdby == ''){
           $createdby =  $form->created_by->DispNameWithName;   
        }
      
        $canforward = $forwardarray && $forwardarray->count() ? true : false;
        
        $session = \App\Session::where('name', $form->session )->first();
        $romankla = $session->getRomanKLA();
        $sessionnumber_th = $session->session .  '<sup>' . $session->getOrdinalSuffix($session->session) . '</sup>';
        $malkla = $session->getMalayalamOrdinalSuffix($session->kla);
        $sessionnumber = $session->session;

        $klasession_for_JS = $sessionnumber_th . ' session of ' . $romankla . ' KLA';


        //$initival = $canforward ? $forwardarray->keys()->first() : '';
        $initival = $canforward ?  \Auth::user()->routing->last_forwarded_to  : '';

        JavaScript::put([
            'forwardarray' => $forwardarray,
            'formid' => $form->id,
            'initalvalue' => $initival,
            'remarks' => $form->remarks,
            'malkla' => $malkla,
            'sessionnumber' => $sessionnumber,
            'klasession_for_JS' => $klasession_for_JS,
            'dataentry_allowed' => $session->dataentry_allowed != 'No',
            'needsposting' => $needsposting,
            'session' => $session->name,
        ]);

        $prev=null;
        $next=null;
            

        return view('admin.my_forms2.show', compact('form', 
                    'overtimes','daytype','submmittedby',  'createdby', 'canforward' , 'cansubmittoaccounts', 'descriptionofday'
                    ,'prev','next', 'romankla', 'sessionnumber_th', 'sessionnumber','malkla',
                    'needsposting','dayhaspunching','hasOnlySittings'));
    }


    /**
     * Remove Form from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('my_form_delete')) {
            return abort(401);
        } 

        $form = Form::findOrFail($id);

        if( ($form->owner != \Auth::user()->username) && !\Auth::user()->isAdmin())
        {
            return abort(401);
        }


        
        
        Overtime::where('form_id', $form->id)->delete();

        $form->delete();

        return redirect()->route('admin.my_forms2.index');
    }


    public function create_sitting()
    {
        if (! Gate::allows('my_form_create')) {
            return abort(401);
        } 
          
        return $this->preparevariablesandGotoView(true, null);
       
    }
    
    private function getEmployeeType($emp) {
     
        $desig = strtolower($emp->designation->designation);
        $category =   strtolower($emp->categories?->category); 
        // Log::info($desig);
        // Log::info($category);

        $isPartime = str_contains($desig,"part time") || str_contains($desig,"parttime") || 
                     str_contains($category,"parttime")||
                     $emp->designation->normal_office_hours == 3; //ugly
        $isFulltime = str_contains($category,"fulltime")||
                      $emp->designation->normal_office_hours == 6;

        $isWatchnward = str_contains($category,"watch") ;
        $isNormal = !$isPartime && !$isFulltime && !$isWatchnward;

        return [$isPartime,$isFulltime,  $isWatchnward,  $isNormal];
    }

    //for sitting days 
    private function checkPunchingDataForEmp($calenderdays_in_range, $pen, $aadhaarid)
    {
        $sittingsWithTimeSatisfied = 0; 
        $emp = Employee::with(['designation','categories'])->where( 'pen', $pen)->first();
        $dateformatwithoutime = '!'.config('app.date_format');
        $sittingsWithUserDecision=0; 
        [$isPartime,$isFulltime,  $isWatchnward,  $isNormal] = $this->getEmployeeType($emp);
        $sittingsWith=0; 

        //need to check office if any exempt for fulltime/pt at cat non-gazetted / mla hostel 
        //we call this function only if overtime['punching] which should exclude watchnward

        foreach ($calenderdays_in_range as $day) {
            
            // Log::info($emp);
            // Log::info($day);
            // Log::info($isFulltime);
            // Log::info($isPartime);

            $date = Carbon::createFromFormat($dateformatwithoutime, $day->date)->format('Y-m-d');
             
            $query =  Punching::where('date',$date);
            $query->when(  $aadhaarid  && strlen($aadhaarid) >= 8, function ($q)  use ($aadhaarid){
                return $q->where('aadhaarid',$aadhaarid);
            });
            //->wherenotnull('punch_in') //prevent if only one column is available
           // ->wherenotnull('punch_out');
            $temp = $query->first(); 
            
            if(!$temp ){
               
              if( $day->punching !== 'AEBAS' ){
                $sittingsWithUserDecision++;
              }

              continue;
            }
            
            $strtimes_totimestamps = function ( $from, $to )  {
                $timefrom = strtotime($from);   $timeto = strtotime($to);
                return [ $timefrom,$timeto ];
            };

            if($temp['punch_in'] && $temp['punch_out'] ){
                             
                
                [$punch_in, $punch_out] = $strtimes_totimestamps( $temp['punch_in'], $temp['punch_out']);
                $othours_worked= ceil(abs($punch_out - $punch_in) / 60);
        
                //check time from
               
                if($isNormal){
                    [$fromReq, $toReq] = $strtimes_totimestamps( "08:05",  "17:25");
                    if( $punch_in <= $fromReq && $punch_out >= $toReq){
                        $sittingsWithTimeSatisfied++; 
                    }
                } else  if($isPartime){
                    [$fromReq, $toReq] = $strtimes_totimestamps( "06:05",  "11:25");
                    if( $punch_in <= $fromReq && $punch_out >= $toReq){
                        $sittingsWithTimeSatisfied++; 
                    }
                }  else  if($isFulltime){
                    [$fromReq, $toReq] = $strtimes_totimestamps( "06:05",  "16:00"); //its 4.25 actually. can enforce after checking
                    if( $punch_in <= $fromReq && $punch_out >= $toReq){
                        $sittingsWithTimeSatisfied++; 
                    }
                } 
                continue;
            }  else if( $day->punching == 'AEBAS' ) {
                continue; //leave/or not punched in/out
            }
            //user has punched once on non-aebas day
            $punchingtimesnotincorrect  = function ( $fromreq, $toreq, $in, $out )  {
                $timefromReq = strtotime($fromreq);   $timetoReq = strtotime($toreq);
                if( $in ){
                    $punchin = strtotime($in);  
                    if( $punchin > $timefromReq )   return false;
                }
                if( $out ){
                    $punchout = strtotime($out);  
                    if( $punchout < $timetoReq )   return false;
                }
                return true;
            };
            
            // NON AEBAS day.

            if($isNormal){
                if($punchingtimesnotincorrect( "08:05",  "17:25",  $temp['punch_in'], $temp['punch_out'])){
                    $sittingsWithUserDecision++; 
                }
            } else  if($isPartime){
                if($punchingtimesnotincorrect( "06:05",  "11:25",  $temp['punch_in'], $temp['punch_out'])){
                    $sittingsWithUserDecision++; 
                }
            } else  if($isFulltime){
                 if($punchingtimesnotincorrect( "06:05",  "16:00", $temp['punch_in'], $temp['punch_out'])){ //its 4.25 actually. 
                    $sittingsWithUserDecision++; 
                }
            } 
          
        }

        return [ $sittingsWithTimeSatisfied,$sittingsWithUserDecision ];

    }
    public function createovertimes_sitting( Request $request, &$myerrors, $formid=null)
    {
       // $date = Carbon::createFromFormat(config('app.date_format'), $request['duty_date'])->format('Y-m-d');
                
        $maxsittingdates = \App\Calender::with('session')
                                ->whereHas('session', function($query)  use ($request) { 
                                    $query->where('name', $request['session']);
                                  })                              
                                ->where('day_type','Sitting day')->orderby('date','asc')->get();

        $maxsittings = $maxsittingdates->count();
        $maxsittingdates = $maxsittingdates->pluck('date');
        
        $collection = collect($request->overtimes);
        $collection->transform(function($overtime) 
        {
            $tmp = strpos($overtime['pen'], '-');
            if(false !== $tmp){
                //note change name first before changing pen itself
                $overtime['name'] =  substr($overtime['pen'], $tmp+1 );
                $overtime['pen'] =   substr($overtime['pen'], 0, $tmp );
            }

            return $overtime;
        });

     //   Log::info($request);
        
        $designations = $collection->pluck('designation');
        $rates = \App\Designation::wherein ('designation', $designations )->pluck('rate','designation');

        ////////////////////////////////////

        $pens = $collection->pluck('pen');
        
      
        
       $pentoattendace = null;
       $pentodays = null;
       $attendance = null;
       $checksecretaryattendance = true;
       
       //when we passed in a pen of 'E11956', it was shown as error, probably because it was interpreted as a number.
      //so enclose every pen in quotes.
       
       // $placeholder = implode(', ', array_fill(0, count($pens), '?')); //this returns like  '?, ?'

        $query = \App\Overtime::with('form')
                              //->whereRaw("SUBSTRING_INDEX(`pen`, '-' ,1) in ($placeholder)",$pens )
                              ->wherein('pen',$pens )
                              ->whereHas('form', function($q)  use ($request,$formid) { 
                                $q->where('overtime_slot', 'Sittings')
                                      ->where('session', $request['session'])
                                      ->where('id', '!=', $formid); //skip this item if on update
                            });


        //we cannot use pluck, pluck seems to return only distinct 'count'
        $res = $query->get();
        /*foreach ($res as $e) {
         array_push($myerrors,  $e['pen'] . $e['from']);
}*/
  

        ////////////////////////////////

        //\Log::info(print_r($res, true));

        //Be warned that DateTime object created without explicitely providing the time portion will have the current time set instead of 00:00:00.
        //If you want to safely compare equality of a DateTime object without explicitly providing the time portion make use of the ! format character.

        $dateformatwithoutime = '!'.config('app.date_format');
       
         //check date overlap
        $sitting_start = Carbon::createFromFormat( $dateformatwithoutime, $maxsittingdates->first());
        $sitting_end = Carbon::createFromFormat($dateformatwithoutime, $maxsittingdates->last());

        

        $overtimes =$collection->transform(function($overtime) 
                                           use ($res, $request,$formid, &$myerrors,$maxsittings,
                                            $rates,$sitting_start, $sitting_end,$dateformatwithoutime,$maxsittingdates, 
                                            $checksecretaryattendance, $attendance) 
        {
            
            $pen = $overtime['pen'];
            $tmp = strpos($pen, '-');
            if(false !== $tmp){
               $pen = substr($pen, 0, $tmp);
            }

            
            $res_for_pen = $res->reject(function($element) use ($pen) {
                return strncasecmp($element['pen'], $pen, strlen($pen)) != 0;
            });
                               
            //note, res is a collection. not a query 
            $totalsittingexisting = $res_for_pen->sum('count');
            //$totalsittingexisting = $res->sum('count');

            // $days_already_entered = $q->all()->pluck(['from','to']);

            $totalwouldbe =  $totalsittingexisting + $overtime['count'];
           
            $name_for_err =  $overtime['pen'] . '-' .$overtime['name'];
            if($totalwouldbe > $maxsittings)
            {      
               if($totalsittingexisting){
                array_push($myerrors,   $name_for_err . ' : Already saved ' . $totalsittingexisting . '. + this (' .$overtime['count'] .') = ' . $totalwouldbe. '. (maximum possible: ' . $maxsittings .')' );
               } else {
                array_push($myerrors,   $name_for_err . ' : This = ' . $overtime['count'] . '. (maximum possible: ' . $maxsittings .')' );
               }
            
               return null;   
            }
          
            
                   
            //check date overlap
            $start_one = Carbon::createFromFormat($dateformatwithoutime, $overtime['from']);
            $end_one = Carbon::createFromFormat($dateformatwithoutime, $overtime['to']);

            $start_one_string = $start_one->format('d-m-Y');
            $end_one_string = $end_one->format('d-m-Y');
            $pos1 = $maxsittingdates->search($start_one_string);
            $pos2 = $maxsittingdates->search($end_one_string);

            //see if user has entered more days than date range
            
            $calenderdays_in_range =  \App\Calender::with('session')
                                    ->whereHas('session', function($query)  use ($request) { 
                                        $query->where('name', $request['session']);
                                    })                         
                                    ->where('date', '>=', $start_one)
                                    ->where('date', '<=', $end_one)
                                    ->where('day_type','Sitting day')->get();


            $sittingsinrange =  abs($pos2-$pos1)+1;
            if($pos1 === false ||  $pos2 === false){
               //user has entered a date that is not a sitting day, manually
                $sittingsinrange = $calenderdays_in_range->count();
            }
            
            if( $overtime['count'] >  $sittingsinrange ){

              array_push($myerrors,  $name_for_err . ' : From ' . $overtime['from'] . ' to ' . $overtime['to'] . ' there are only ' . $sittingsinrange .' sitting days.');
              return null;
            
            }
       
            if( $start_one < $sitting_start || $end_one > $sitting_end ) 
            {
                array_push($myerrors,  $name_for_err . ' : Select date range between ' . $sitting_start->format('d-m-Y') . ' and ' . $sitting_end->format('d-m-Y') . ' (' . $start_one->format('d-m-Y') . ',' . $end_one->format('d-m-Y') . ')' );
                    return null;
            }

            //check date overlap
            //if this is a supply, allow user to enter any date range. person might have incorrectly submitted the first time.
            {
                $emp = $res_for_pen->all();
                //$emp =  $res->all();
                foreach ($emp as $e) {

                    //if supply, disregard dateoverlap with previous entry made by this section
                    if(false !== stripos( $overtime['worknature'],'SUPPL') /*&& $e->form->isSameSectionCreator*/ ){
                        continue;
                    }

                    $start_two = Carbon::createFromFormat($dateformatwithoutime, $e['from']);
                    $end_two = Carbon::createFromFormat($dateformatwithoutime, $e['to']);

                    $isoverlap = ($start_one <= $end_two) && ($end_one >= $start_two);

                    if($isoverlap){
                        array_push($myerrors,  $name_for_err . ' : Dates overlap with another OT from ' . $e['from'] . ' - ' . $e['to'] . ' for '.$e['count'] . ' day(s) (' . $e->form->creator . ' )' );
                        return null;
                    }

                } //foreach
            }

            //new check punching times
            if( $overtime['punching'] == true ) {
                [ $sittingsWithTimeSatisfied,$sittingsWithUserDecision ] = $this->checkPunchingDataForEmp($calenderdays_in_range, $overtime['pen'], $overtime['aadhaarid']);
                //we need to also check time within 8 -> 5 but dont know how much luft. partime is ok
                if( $overtime['count'] > $sittingsWithTimeSatisfied + $sittingsWithUserDecision){
                    array_push($myerrors,  $name_for_err . ' : From ' . $overtime['from'] . ' to ' . $overtime['to'] . ' only ' . $sittingsWithTimeSatisfied .' days satisfy time per G.O');
                    return null;
                }
         
            } else{
                // Log::info("No punch for" . $overtime['name']);
            }
     

            {                
                
                return new Overtime([
                    'pen'           => $overtime['pen'],
                    'name'          => $overtime['name'],
                    'designation'   => $overtime['designation'],
                    'from'          => $overtime['from'],
                    'to'            => $overtime['to'], 
                    'worknature'    => $overtime['worknature'],
                    'count'         => $overtime['count'],
                    'rate'          => $rates[$overtime['designation']],
                    'punching'      => $overtime['punching'],
                    'employee_id'   => $overtime['employee_id'],
                    'slots'         => "", //not used
                    ]);
            }

        });
    
        return $overtimes;
           
    }
   
    public function store_sitting(Request $request)
    {
         if (! Gate::allows('my_form_create')) {
           // return abort(401);
           return response('Unauthorized.', 401);
        } 
                
        $myerrors = [];

        $overtimes = $this->createovertimes_sitting( $request, $myerrors );
      
        
        if( count($myerrors) > 0) 
        {
            return response()
            ->json(['products_empty' => $myerrors], 422);
        }


        if($overtimes->isEmpty()) 
        {
            return response()->json(['products_empty' => ['An unknown error in row creation.']
                    ], 422);
        }
                
         
        $formid = \DB::transaction(function() use ( $request, $overtimes) 
        {

            $form = Form::create( [
                'session' => $request['session'],
                'creator' => \Auth::user()->username,
                'owner'=> \Auth::user()->username,
                'date_from' => $request['date_from'],
                'date_to' => $request['date_to'],
                'overtime_slot' => 'Sittings',
                'remarks' => $request['remarks'],
            ]);
   

            $form->overtimes()->saveMany($overtimes);

            //save days
            $overtimesittingscollectionforeachpen = collect($request->overtimes)->mapWithKeys(function($ot){
                $newsits = collect($ot['overtimesittings'])->map(function($date){
                    return new OvertimeSitting( ['date' => $date ] ); 
                });
                return [$ot['pen'] => $newsits];
            });

            $form->overtimes()->each(function ($ot, $key) use ($overtimesittingscollectionforeachpen ) {
                $otsittingsforthispen = $overtimesittingscollectionforeachpen[$ot['pen'] . '-'. $ot['name']];
                $ot->overtimesittings()->saveMany( $otsittingsforthispen );
            });
            
          

            return $form->id;

        });

        $request->session()->flash('message-success', 'Created form-no: ' . $formid ); 
           
   

        return response()->json([
           'created' => true,
           'id' => $formid
        ]);



    }

    public function forward(Request $request, $id)
    {

        $form = Form::findOrFail($id);
    

        if( $form->owner != \Auth::user()->username)
        {
            return abort(401);
        }
        
        
      
        //if we are not the creator, add us to the submitted field
        //if (0 === strpos($loggedinusername, 'sn.')) 

        $submitted_by = $form->submitted_by;
        $submitted_names = $form->submitted_names;

        $submittedname =  trim(\Auth::user()->DispNameWithNameShort);


        //we do not want sections without display names to become 'approved', as it might show approved: Accounts D
        if( $form->creator != $form->owner ){
            $submitted_by .= ($submitted_by != '') ? ( ',' . $form->owner) : $form->owner;
           
            //$submitted_names .= ($submitted_names != '') ? ( '|' . $submittedname) : $submittedname;
        }
        else{
            // a section or under forwarding a draft 
            //note, display name cannot be obtained dynamically, as it can change in the future.
            if(\Auth::user()->displayname != ''){
                
               // $submitted_names .= ($submitted_names != '') ? ( '|' . $submittedname) : $submittedname;           
            }
        }
        
        $submitted_names .= ($submitted_names != '') ? ( '|' . $submittedname) : $submittedname;  


        //change owner
         $form->update( [
            'owner' => $request['owner'],
            'submitted_by' => $submitted_by,
            'submitted_names' => $submitted_names,
            'submitted_on' => Carbon::now(),
            'form_no' => null, //remove ignored status
        ]);

        $routing = \Auth::user()->routing;
        $routing->update( [ 'last_forwarded_to' => $request['owner'] ] );

        return response()->json([
           'result' => true,
           
        ]);

    }
    
    public function submittoaccounts(Request $request, $id)
    {
        $form = Form::findOrFail($id);
    

        if( $form->owner != \Auth::user()->username)
        {
            return abort(401);
        }
      
        //if we are not the creator, add us to the submitted field
        //if (0 === strpos($loggedinusername, 'sn.'))
        
        $submitted_by = $form->submitted_by;
        $submitted_by .= ($submitted_by != '') ? ( ',' . $form->owner) : $form->owner;

        //note, display name cannot be obtained dynamically, as it can change in the future.
        $submittedname =  trim(\Auth::user()->DispNameWithNameShort);
        $submitted_names = $form->submitted_names;
        $submitted_names .= ($submitted_names != '') ? ( '|' . $submittedname) : $submittedname;


        $maxform_no = \App\Form::whereSession($form->session)->max('form_no');
        if($maxform_no < 0){
           $maxform_no = 0; // form no field to -1 for rejected
        }


        //change owner
         $form->update( [
            'owner' => $request['owner'],
            'submitted_by' => $submitted_by,
            'submitted_names' => $submitted_names,
            'submitted_on' => Carbon::now(),
            'form_no' => $maxform_no+1,
        ]);


        return response()->json([
           'result' => true,
           
        ]);

    }

    public function sendback(Request $request, $id)
    {
        $form = Form::findOrFail($id);
    

        if(!\Auth::user()->isAdmin())
        {
            if( $form->owner != \Auth::user()->username)
            {
                return abort(401);
            }
        }
      

        //change owner
         $form->update( [
            'owner' => $form->creator,
            'submitted_by' => null,
            'submitted_names' => null,
            'submitted_on' => null,
            'form_no' => null,
            'remarks' => $request['remarks'],

        ]);


        return response()->json([
           'result' => true,
           
        ]);

    }

    public function sendonelevelback(Request $request, $id)
    {
        $form = Form::findOrFail($id);
    
        if(!\Auth::user()->isAdmin())
        {
            if( $form->owner != \Auth::user()->username)
            {
                return abort(401);
            }
        }
        
        $strTemp = trim($form->submitted_by);

        if($strTemp == '' || $strTemp == NULL) //NULL means just only one level sent. 
        {
            return $this->sendback($request, $id);

        }

        $submittedby = explode(",", trim($strTemp,", ") );

        if( count($submittedby) == 0 )
        {
             return response()->json([
               'result' => false,
               
            ]);
        }      

        $newowner = end($submittedby);
        array_pop($submittedby);// If array is empty, array_pop returns NULL
       
        $newsubmittedby = null;
        if(count($submittedby) > 0) {
            $newsubmittedby = implode(",",$submittedby);
        }

        
        $submittednames = explode("|", trim($form->submitted_names,"| ") );
        array_pop($submittednames);
        $newsubmittednames = implode("|",$submittednames);

        


        //change owner
        
         $form->update( [
            'owner' => $newowner,
            'submitted_by' => $newsubmittedby,
            'submitted_names' => $newsubmittednames,
            //'submitted_on' => null,
            'form_no' => null,
            'remarks' => $request['remarks'],

        ]);

        return response()->json([
           'result' => true,
           
        ]);

    }


    public function ignore(Request $request, $id)
    {
        $form = Form::findOrFail($id);
    

        if( $form->owner != \Auth::user()->username)
        {
            return abort(401);
        }
      

        if($form->form_no >= 0){

            if($form->form_no == 0){
                $form->form_no = 1; // so we will set form_no to -1
            }

            $form->update( [
                'form_no' => -$form->form_no,
                'remarks' => $request['remarks'],
            ]);

        } else  if($form->form_no < 0) {

            if(\Auth::user()->isAdmin()){

            //as the origanl form no might be occupied by now, we cannot just negate it. submit as new
            $maxform_no = \App\Form::whereSession($form->session)->max('form_no');
            if($maxform_no < 0){
               $maxform_no = 0;
            }

            $form->update( [
                'form_no' => $maxform_no+1,
           
            ]);

            } else {
                $form->update( [
                'form_no' => null,
           
            ]);
            }

        }

        return response()->json([
           'result' => true,
           
        ]);

    }

    public function update_sitting(Request $request, $id)
    {
        if (! Gate::allows('my_form_edit')) {
            return abort(401);
        } 

        $form = Form::findOrFail($id);

    

        if( $form->owner != \Auth::user()->username  && !\Auth::user()->isAdmin())
        {
            return abort(401);
        }
       
        $myerrors = [];
        
        $overtimes = $this->createovertimes_sitting( $request, $myerrors, $id );


        if( count($myerrors) > 0) 
        {
            return response() ->json(['products_empty' => $myerrors ], 422);
        }


        if($overtimes->isEmpty()) {
            return response()->json([
                'products_empty' => ['One or more row is required.']
            ], 422);
        }
      
       
        $formid = \DB::transaction(function()   use ($form, $request, $overtimes) {

           

            //no need to update creator and owner
            $form->update( [
                       
                'remarks' => $request['remarks'],
                'updated_at' => Carbon::now(),

            ]);
            
            $overtimesittingscollectionforeachpen = collect($request->overtimes)->mapWithKeys(function($ot){
                $newsits = collect($ot['overtimesittings'])->map(function($date){
                    return new OvertimeSitting( ['date' => $date ] ); 
                });
                return [$ot['pen'] => $newsits];
            });

            //see if user has made any changes

            $overtimes_old = Overtime::where('form_id', $form->id)->get();
            //same number or added new items
            if( $overtimes_old->count() && $overtimes->count()){

                //remove all overtimesittings
                $idsold = $overtimes_old->pluck('id')->toarray();
                OvertimeSitting::wherein('overtime_id', $idsold)->delete();

                //update same row indices
                $i=0;
                $same_rows = min($overtimes_old->count(), $overtimes->count());
                for (; $i < $same_rows; $i++) { 
                   $newot =  Overtime::findOrFail( $overtimes_old[$i]['id']);
                   $newot->update($overtimes[$i]->toarray());
                   $otsittingsforthispen = $overtimesittingscollectionforeachpen[$newot['pen'] . '-'. $newot['name']];
                   $newot->overtimesittings()->saveMany( $otsittingsforthispen );
                }
                
                if($overtimes_old->count() < $overtimes->count()){
                    //update if a new row added
                    $overtimes = $overtimes->slice($i);
                    $form->overtimes()->saveMany($overtimes);
                    $form->overtimes()->each(function ($ot) use ($overtimesittingscollectionforeachpen ) {
                        $otsittingsforthispen = $overtimesittingscollectionforeachpen[$ot['pen'] . '-'. $ot['name']];
                        $ot->overtimesittings()->saveMany( $otsittingsforthispen );
                    });

                } else if ( $overtimes_old->count() > $overtimes->count()) {
                    //remove rows removed
                    $idsremoved = $overtimes_old->slice($i)->pluck('id')->toarray();
                    Overtime::wherein('id', $idsremoved)
                            ->where('form_id', $form->id)->delete();
                }

                return $form->id;
            }
            
            //old code, where we delete everything and add. unreachable code

        
            Overtime::where('form_id', $form->id)->delete();
            $form->overtimes()->saveMany($overtimes);

            return $form->id;
        });
        
        $request->session()->flash('message-success', 'Updated form-no: ' . $formid ); 

        return response()->json([
            'created' => true,
            'id' => $formid
         ]);
    

        //return redirect()->route('admin.my_forms2.index');
    }

    public function getpdf(Request $request)
    {

        $str_session = null;                 
        $str_status = null;
        $str_overtime_slot = null;
        $str_datefilter = null;
        $str_namefilter = null;
        $str_desigfilter = null;
        $str_idfilter = null;
        $str_created_by = null;
        
        $session = $request->query('session');
        $overtime_slot = $request->query('overtime_slot');
        $status =  $request->query('status');
        $datefilter =  $request->query('datefilter');
        $namefilter =  $request->query('namefilter');
        $desigfilter =  $request->query('desigfilter');
        $idfilter   =  $request->query('idfilter');
        $createdby =  $request->query('created_by');

        $forms = \App\Form::with(['created_by','owned_by','overtimes'])
                       ->filterStatus('Submitted')
                       ->where('creator', '<>','admin')
                       ->where('form_no', '>=', 0);
                      


        if ($request->filled('created_by')){ 
                           
            if($createdby != 'all'){
                //$forms = $forms->where( 'creator', $createdby);
                 $forms->where( 'creator', 'like', '%'.$createdby); //accomodate de.
                                         
                $str_created_by = '&created_by='.$createdby;
            }
            
        }
         
       
        if ($request->filled('session')){
             $forms = $forms->where('session',$session);
        }

        $str_session = '&session='.$session;

                      

        if ($request->filled('idfilter'))
        {
            $forms = $forms->where('id',$idfilter);
                   
            $str_idfilter = '&idfilter='.$idfilter;
            
        }


        if ($request->filled('overtime_slot')){
            if($overtime_slot == 'Non-Sittings'){
                $forms = $forms->where( 'overtime_slot' , 'Multi' );    
            }
            else{
                $forms = $forms->whereOvertimeSlot($overtime_slot);
            }

            $str_overtime_slot = '&overtime_slot='.$overtime_slot;
        }

        if ($request->filled('datefilter')){
            
            $forms = $forms->filterDate( $datefilter );

            $str_datefilter = '&datefilter='.$datefilter;
        }

        if ($request->filled('namefilter')){
                    
            $forms = $forms->wherehas( 'overtimes', function($q) use ($namefilter){
               $q->where('pen','like', '%' . $namefilter.'%' );
            }); 
                
            $str_namefilter = '&namefilter='. $namefilter;
        }
    
        if ($request->filled('desigfilter')){
                        
             $forms = $forms->wherehas( 'overtimes', function($q) use ($desigfilter){
                
                if( strpos($desigfilter, '^') === 0  )
                {
                   $q->where('designation', 'like',  substr($desigfilter,1).'%' );
                 
                }
                elseif( strpos($desigfilter, '=') === 0  )
                {
                    $q->where('designation',  'like', substr($desigfilter,1) );
                 
                }
                else
                {
                    $q->where('designation', 'like', '%' . $desigfilter.'%' );
                 
                }
             }); 
                 
             $str_desigfilter = '&desigfilter=' . $desigfilter;
         }
     
        $forms = $forms->orderBy('id', 'asc')->get();

       
        $htmlview = $request->query('viewall') != 'viewallpdf';
     
        $combined = null;
        $index = 0;

        

  

        foreach ($forms as $form) 
        {
            $index += 1;
            $id = $form->id;       

            $overtimes = \App\Overtime::where('form_id', $id)->get();

            $overtimes->transform(function($overtime) 
            {
                $tmp = strpos($overtime['pen'], '-');
                if(false === $tmp){
                    $overtime['pen'] .=   '-' . $overtime['name'];
                }

                return $overtime;
            });


           // $form = Form::with(['created_by','owned_by'])->findOrFail($id);

            $daytype = null;

         
            $forwardarray = null;
                
            $cansubmittoaccounts = false;
            $descriptionofday = '';
                       
            if($form->overtime_slot != 'Sittings'){
                $date = Carbon::createFromFormat(config('app.date_format'), $form->duty_date)->format('Y-m-d');
     
                 $calender = Calender::where('date', $date )->first();
                $daytype = $calender->day_type;

            $descriptionofday = $calender->description;

            }
            
            $submmittedby = $form->SubmitedbyNames;
            $createdby = $form->FirstSubmitedbyName;

            //if the form has still not been submitted, $createdby will be empty as FirstSubmitedbyName is null
            //in that case, get name from creator

            if($createdby == ''){
                $createdby = $form->created_by->Title;
            }
          
            $canforward = false;
                  
            $initival =  '';
/*
            JavaScript::put([
                'forwardarray' => $forwardarray,
                'formid' => $form->id,
                'initalvalue' => $initival,
                'remarks' => $form->remarks,
                
            ]);*/

            $prev=null;
            $next=null;
            $added_bies = null;

            $session = \App\Session::where('name', $form->session )->first();
            $romankla = $session->getRomanKLA();
            $sessionnumber_th = $session->session .  '<sup>' . $session->getOrdinalSuffix($session->session) . '</sup>';
            // $malkla = $session->getMalayalamOrdinalSuffix($session->kla);
            //$sessionnumber = $session->session;


            $view =  view('admin.my_forms2.showpdf', compact( 'index', 'form', 
                        'overtimes','daytype','submmittedby', 'createdby',  'canforward' , 'cansubmittoaccounts','descriptionofday'
                        ,'prev','next','added_bies', 'romankla', 'sessionnumber_th'));


            $combined .= $view ;

            if($htmlview){
                $combined .=  "<p style='page-break-after: always;'>&nbsp;</p>";
            }

        }

       $s = "<!DOCTYPE html><html><head><link href='" . url('/') . "/adminlte/bootstrap/css/bootstrap.min.css' rel='stylesheet'></head>";

        if(!$htmlview){ //css is ignord by Dompdf
            $s = "<!DOCTYPE html><html>";

        }

/* for chrome, append <style>
@media print {@page {size: landscape;}}</style>
*/
        $s .= $combined . "</html>";

        if(!$htmlview){
            
          return PDF::loadHTML($combined)->setPaper('a4', 'landscape')->stream();

        }
        else {
           return $s;
           // dd($combined);
        }


    }

}
