<?php

namespace App\Http\Controllers\Admin;

use App\Form;
use App\Overtime;
use App\Calender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
//use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Input;
//use Laracasts\Utilities\JavaScript\JavaScriptFacade;
use JavaScript;
use Carbon\Carbon;
use Auth;
use PDF;


class MyFormsController extends Controller
{
    public function index()
    {
        if (! Gate::allows('my_form_access')) {
            return abort(401);
        }

        $begintime = microtime(true);


        //user can login and see forms even after data entry is disabled
        //$session_array = \App\Session::whereDataentryAllowed('Yes')->pluck('name');

        
        $session_array = \App\Session::whereshowInDatatable('Yes')->latest()->pluck('name');

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
        $str_worknaturefilter=null;
        $str_remarksfilter=null;
        $str_submittedbyfilter=null;

        $session = Input::get('session');
        $overtime_slot = Input::get('overtime_slot');
        $status =  Input::get('status');
        $datefilter =  Input::get('datefilter');
        $namefilter =  Input::get('namefilter');
        $desigfilter =  Input::get('desigfilter');
        $idfilter   =  Input::get('idfilter');
        $createdby =  Input::get('created_by');
        $worknaturefilter = Input::get('worknaturefilter');
        $remarksfilter = Input::get('remarksfilter');

        $submittedbyfilter = Input::get('submittedbyfilter');


        if (Input::filled('created_by')){ 
                           
            if($createdby != 'all'){
                //$forms->where( 'creator', $createdby);
                $forms->where( 'creator', 'like', '%'.$createdby); //accomodate de.
                                         
                $str_created_by = '&created_by='.$createdby;
            }

            
        }
         

        //undersec should be able to view any new forms from prev sessions if there are no session filter
        
        if (Input::filled('session')){
             
            $forms->where('session',$session);
            $str_session = '&session='.$session;
 
        }
        else{
           // $session = $session_array[0];
        }

        //tab takes care of status. 
        
        if (!Input::filled('status')){
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
                         


        if (Input::filled('idfilter'))
        {
            $forms->where(function($query) use ($idfilter)
                    {
                      $query->where('id',$idfilter)
                           ->orwhere('form_no', $idfilter);
                    });
                   
            $str_idfilter = '&idfilter='.$idfilter;
            
        }


        if (Input::filled('overtime_slot')){
            if($overtime_slot == 'Non-Sittings'){
                $forms->where( 'overtime_slot' , '!=', 'Sittings' );    
            }
            else
            if($overtime_slot == 'Withheld'){
                $forms->where( 'form_no' , '<=', 0 );    
            }
            else
            {
                $forms->whereOvertimeSlot($overtime_slot);
            }

            $str_overtime_slot = '&overtime_slot='.$overtime_slot;
        }

        
        if (Input::filled('datefilter')){
            
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

        if (Input::filled('submittedbyfilter')){
                      
            $forms->where( 'submitted_by', 'like', '%' . $submittedbyfilter.'%' );
                             
            $str_submittedbyfilter = '&submittedbyfilter='. $submittedbyfilter;
        }
 

        if (Input::filled('namefilter') || Input::filled('desigfilter')){
            $forms->with('overtimes');
        }

        if (Input::filled('namefilter')){
                    
            $forms->wherehas( 'overtimes', function($q) use ($namefilter){
               $q->where('pen','like', '%' . $namefilter.'%' )
                ->orwhere('name','like', '%' . $namefilter.'%' );
            }); 
                
            $str_namefilter = '&namefilter='. $namefilter;
        }

        if (Input::filled('worknaturefilter')){
                      
            $forms->wherehas( 'overtimes', function($q) use ($worknaturefilter){
               $q->where('worknature','like', '%' . $worknaturefilter.'%' );
            });                 
            $str_worknaturefilter = '&worknaturefilter='. $worknaturefilter;
        }
         if (Input::filled('remarksfilter')){
                      
            if($remarksfilter == 'nonempty'){
                $forms->where( 'remarks', '<>', '' );
            } else {
                $forms->where( 'remarks', 'like', '%'.$remarksfilter.'%' );
            }
                       
            $str_remarksfilter = '&remarksfilter='. $remarksfilter;
        }

        if (Input::filled('desigfilter')){
                        
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
     

        $sort =  Input::filled('sort') ? Input::get('sort') : 'updated_at'; // if user type in the url a column that doesnt exist app will default to id
        $order = Input::get('order') === 'asc' ? 'asc' : 'desc'; // default desc
                
        $forms = $forms->orderBy($sort, $order)->distinct()->paginate(10)
                                               ->appends(Input::except('page'));
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
        $querystr = '&order='.(Input::get('order') == 'asc' || null ? 'desc' : 'asc').$str_session.$str_status.$str_overtime_slot.$str_datefilter.$str_namefilter.$str_desigfilter.$str_idfilter.$str_created_by.$str_worknaturefilter.$str_submittedbyfilter;

        $added_bies = \App\User::SimpleUsers()
                                 ->where('username','not like','de.%')
                                 ->orderBy('name','asc')
                                ->get(['username','name'])->pluck('name','username');
       
        $added_bies->put( 'de.sn.protocol' , 'Protocol');
        
        JavaScript::put([
           'adminoraudit' => auth()->user()->isAdminorAudit(),
          
            
        ]);

        $timetaken = round(microtime(true) - $begintime,4);

        return view('admin.my_forms.index',compact('forms','querystr', 'to_approve',  'pending_approval', 'session_array','session','added_bies', 'timetaken' ));
    }



    public function preparevariablesandGotoView( $issitting, $id=null, $id_to_copy = null )
    {
        $enum_overtime_slot = Form::$enum_overtime_slot;

        $q = \App\Session::with('calender')->whereDataentryAllowed('Yes')->latest();

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
        $calenderdays2 = array();
     
        foreach ($session_array as $session) {
    
            $daysall = $session->calender()->orderby('date','asc');
                       
            if(!$issitting)
            {
                $daysall->where('date', '<=', date('Y-m-d'));
                $days = $daysall->get(['date','day_type']);
            }
            else{
                $days = $daysall->where( 'day_type','Sitting day')->get(['date','day_type']);
            }

            
            foreach ($days as $day) {
              
                $calenderdaysmap[$day['date']] = $day['day_type'];



                $calenderdays2[$session->name][] = $day['date'];    
            }
        }

       
      //hard corded. ugly i know. 
        $ispartimefulltime = 0;
        $iswatchnward = 0;
        $isspeakeroffice = 0;


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

        if( false !== strpos(  \Auth::user()->username, 'oo.') ){
            $isspeakeroffice = 1; //dyspkr and sec too
        }

        //amhostel and sn.mae and sn.amresspkr has parttimes too
        
        if( false !== strpos( \Auth::user()->username, 'health' ) || 
            false !== strpos( \Auth::user()->username, 'agri') || 
            false !== strpos( \Auth::user()->username, 'sn.am') || 
            false !== strpos( \Auth::user()->username, 'sn.ma')
             ){

            $ispartimefulltime = 1;            
        }

        $designations = \App\Designation::orderby('designation','asc')
                    ->get(['designation'])->pluck('designation');

        $data["calenderdaysmap"] = json_encode($calenderdaysmap);
        $data["designations"] = json_encode($designations);
        $data["calenderdays2"] = json_encode($calenderdays2);


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
            $formtocopy = Form::with(['created_by','overtimes'])->findOrFail($id_to_copy);
            
            $autoloadpens = $formtocopy->overtimes()->get();
            
            $autoloadpens = $autoloadpens->mapWithKeys(function ($item) {
                if($item['name'] == null)
                    return [$item['pen'] => $item['designation']];
                else {
                    return [$item['pen'] .'-' . $item['name'] =>  $item['designation']];
                }
            });
            
        }
       
        JavaScript::put([
            'latest_session' => $latest_session,
            'old_slotselected' => old('overtime_slot') ? old('overtime_slot') : '',
            'old_calenderdayselected' => old('duty_date') ? old('duty_date') : '',
            'presets' => $presets,
            'ispartimefulltime' => $ispartimefulltime,
            'iswatchnward' => $iswatchnward,
            'isspeakeroffice' => $isspeakeroffice,
            'autoloadpens' => $autoloadpens,
            'presets_default' => $presets_default,
            
        ]);
    
        $collapse_sidebar = true;
        if(!$issitting){
            if($id)
            {
                $form = Form::with(['created_by','overtimes'])->findOrFail($id);

                $form->overtimes->transform(function ($item) {
                    if($item['name'] != null){
                        $item['pen'] = $item['pen'] . '-' . $item['name'];
                    }
                    return $item;
                                   
                });

                                    
                return view('admin.my_forms.edit', compact('form', 'data','sessions','enum_overtime_slot', 'collapse_sidebar' ));
            }
            else
            {
                return view('admin.my_forms.create', compact('data','sessions','enum_overtime_slot', 'collapse_sidebar' ) );
            }
        }
        else{ //sitting forms
            if($id)
            {
                $form = Form::with(['created_by','overtimes'])->findOrFail($id);

                $form->overtimes->transform(function ($item) {
                    if($item['name'] != null){
                        $item['pen'] = $item['pen'] . '-' . $item['name'];
                    }
                    return $item;
                                   
                });

                                    
                return view('admin.my_forms.edit_sitting', compact('form', 'data','sessions', 'collapse_sidebar' ));
            }
            else
            {
                return view('admin.my_forms.create_sitting', compact('data','sessions', 'collapse_sidebar') );
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
                                //->where('overtime_slot', $request['overtime_slot'])
                                ->where('overtime_slot', '<>' ,'Sittings')
                                ->where('session', $request['session'])
                                ->where('id', '!=', $formid); //skip this item if on update
                    })->get();
    
        
        ///////////////////////////////

        $designations = $collection->pluck('designation');

        $rates = \App\Designation::wherein ('designation', $designations )->pluck('rate','designation');
     
        $overtimes = $collection->transform(function($overtime)  
                                            use ($res, $request,$date, &$myerrors,$formid,$rates) 
        {
            $pen = $overtime['pen'];
            $tmp = strpos($pen, '-');
            if(false !== $tmp){
                $pen = substr($pen, 0, $tmp);
            }


            $emp = $res->reject(function($element) use ($pen) {
                //return strpos($element['pen'], $pen) === false;
                return strncasecmp($element['pen'], $pen, strlen($pen)) != 0;
            });

            //find if the same employee has occupied this slot on this day

            /*

            $emp =  \App\Overtime::with('form')
                        ->where('pen', 'like' , $pen . '%')
                        ->whereHas('form', function($query)  use ($request,$date,$formid) { 
                              $query->where('session', $request['session'])
                                    ->where('duty_date', $date)
                                    //->where('overtime_slot', $request['overtime_slot'])
                                    
                                    ->where('id', '!=', $formid); //skip this item if on update
                        })->get();
            */


            
            /*$res_for_thisslot = $emp->reject(function($element) use ($request) {
                return stripos($element->form->overtime_slot, $request['overtime_slot']) === false;
            });*/

            ///////////
            $res_for_thisslot = $emp->reject(function($element) use ($request) {
                return stripos($element->form->overtime_slot, $request['overtime_slot']) === false;
            });
            //////////////
                        
            $empslot = $res_for_thisslot->map(function ($item, $key) {
                            return $item['pen'] . '-' . $item['name'] ;
                        });
                                               
        
            if( count($empslot) > 0){
                 array_push($myerrors, 'Already entered ' . $request['overtime_slot'] . ' OT on this day for '  .  $empslot->implode(','));
                 return null;
            }

            //this is to prevent DS and above getting more than 3 OTs including additional OT
/*
            $emp =  \App\Overtime::with('form')
                    ->where('pen', 'like' , $pen . '%')
                    ->whereHas('form', function($query)  use ($request,$date,$formid) { 
                        $query->where('duty_date', $date)
                              ->where('session', $request['session'])
                              ->where('id', '!=', $formid); //skip this item if on update;
                    })->get();
            */
                  
                        
            if( $emp->count() >= 3  )
            {
                //list($pen, $name) = array_map('trim', explode("-", $overtime['pen']));
                array_push($myerrors, $overtime['name'] . ' -' . $overtime['pen'] . ' : 3 OTs already entered for the day');

                //return null;

            }

            $timefrom_comp = strtotime($overtime['from']);
            $timeto_comp = strtotime($overtime['to']);

            if($timeto_comp <= $timefrom_comp){
                    $timeto_comp += 24*60*60;
                }


            foreach ($emp as $e) {
                $timefrom = strtotime($e['from']);
                $timeto = strtotime(  $e['to']);
                if($timeto <= $timefrom){
                    $timeto += 24*60*60;
                }

/*
                 $isoverlap = ($timefrom > $timefrom_comp && $timefrom < $timeto_comp) ||
                   ($timefrom_comp > $timefrom && $timefrom_comp < $timeto) || 
                   ( $timefrom == $timefrom_comp || 
                    $timeto  ==  $timeto_comp )
                   ;
                   */
                    $isoverlap = (($timefrom < $timeto_comp) && ($timeto > $timefrom_comp)) || 
                    ($timefrom == $timefrom_comp) || 
                    ($timeto == $timeto_comp) ;

                   if($isoverlap){
                     //list($pen, $name) = array_map('trim', explode("-", $overtime['pen']));
                array_push($myerrors, $overtime['name'] . '-' . $overtime['pen'] . ' : Times overlap with another OT from ' . $e['from'] . ' - ' . $e['to'] . ' (' . $e->form->overtime_slot . ' OT) on this day (' . $e->form->creator . ' )' );
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
                    'worknature'    => $overtime['worknature'],
                    'count'         => '1',
                    'rate'          => $rates[$overtime['designation']],
                    
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
            ]);

           

            $form->overtimes()->saveMany($overtimes);

            return $form->id;
        });

       

       \Session::flash('message-success', 'Success: created form no:' . $formid ); 

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

        \Session::flash('message-success', 'Success: updated form-no: ' . $formid ); 


        return response()->json([
            'created' => true,
            'id' => $formid
         ]);

       // return redirect()->route('admin.my_forms.index');
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

        $form = Form::with(['created_by','owned_by', 'overtimes'])->findOrFail($id);
        $overtimes = $form->overtimes;

        $overtimes->transform(function($overtime) 
        {
            $tmp = strpos($overtime['pen'], '-');
            if(false === $tmp){
                $overtime['pen'] .=   '-' . $overtime['name'];
            }

            return $overtime;
        });



        $daytype = null;

        //find forwardable users.
        $loggedinusername = \Auth::user()->username;

        $forwardarray = null;

        /*
        if($loggedinusername == $form->owner){

            $routes = collect();

            //if we are a section, we will have a route
            if (\Auth::user()->isDataEntryLevel()) {
                $route = \Auth::user()->routing->route;
                            
                $split = explode(',', $route);
                foreach ($split as $val) {
                    $val = trim($val);
                    if($val != '')  $routes->push( $val);    
                }
                
            }
            else{
                
                //if logged in user is under sec and was forwarded this
                //or  if creator is under sec  

                //get all routes that has this user's name in it

                $route = \App\Routing::where('route','like','%'.$loggedinusername.'%')->pluck('route');
                foreach ($route as $r) {
                    //find the rest of the string where this username starts
                    $relevantroute = explode($loggedinusername,$r)[1];
                    $split = explode(',', $relevantroute);
                    foreach ($split as $val) {
                        $val = trim($val);
                        if($val != '')  $routes->push( $val);    
                    }
                }
                
            }
            
            $routes = $routes->unique();

            //now we need to fetch all the user displaynames

            $forwardarray = \App\User::whereIn( 'username',$routes )->get(['username','name','displayname']);

            

            $forwardarray = $forwardarray->mapWithKeys(function ($item) {
                return [$item['username'] => ($item['displayname'] ? $item['displayname'] . ', ' : '') .$item['name']];
            });

        }
        */

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
            $cansubmittoaccounts= \Auth::user()->routing->cansubmit_to_accounts($form->overtime_slot);
        }

    
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

        ]);

        $prev=null;
        $next=null;
        if(\Auth::user()->isAdminorAudit() && $form->owner=='admin'){
            $prev = Form::where('id', '<', $form->id)
                        ->where('owner','admin')
                        ->where('creator','<>','admin')//no pa2admin
                        ->where('session',$form->session)
                        ->when(\Auth::user()->isAudit(),
                            function($q){
                                return $q->where('form_no','>=', 0);
                           })
                        ->max('id');

            $next = Form::where('id', '>', $form->id)
                        ->where('owner','admin')
                        ->where('creator','<>','admin') //no pa2admin
                        ->where('session',$form->session)
                        ->when(\Auth::user()->isAudit(),
                            function($q){
                                return $q->where('form_no','>=', 0);
                           })
                        ->min('id');

        }


      

        return view('admin.my_forms.show', compact('form', 
                    'overtimes','daytype','submmittedby',  'createdby', 'canforward' , 'cansubmittoaccounts', 'descriptionofday'
                    ,'prev','next', 'romankla', 'sessionnumber_th', 'sessionnumber','malkla'));
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

        return redirect()->route('admin.my_forms.index');
    }


    public function create_sitting()
    {
        if (! Gate::allows('my_form_create')) {
            return abort(401);
        } 
          
        return $this->preparevariablesandGotoView(true, null);
       
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

        
        $designations = $collection->pluck('designation');
        $rates = \App\Designation::wherein ('designation', $designations )->pluck('rate','designation');

        ////////////////////////////////////

        $pens = $collection->pluck('pen');
        /*
        $pens->transform(function ($item, $key) {
            $tmp = strpos($item, '-');
            if(false !== $tmp){
                return  substr($item,0, $tmp);
            }
            return $item;
        });*/

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
                                            $rates,$sitting_start, $sitting_end,$dateformatwithoutime,$maxsittingdates) 
        {
             
             $pen = $overtime['pen'];
             $tmp = strpos($pen, '-');
             if(false !== $tmp){
                $pen = substr($pen, 0, $tmp);
             }

             /*
             $query = \App\Overtime::with('form')
                              ->where('pen', 'like' , $pen . '%')
                              ->whereHas('form', function($q)  use ($request,$formid) { 
                                $q->where('overtime_slot', 'Sittings')
                                      ->where('session', $request['session'])
                                      ->where('id', '!=', $formid); //skip this item if on update
                            });

            //we cannot use pluck, pluck seems to return only distinct 'count'
            $res = $query->get();
            */

            $res_for_pen = $res->reject(function($element) use ($pen) {
                return strncasecmp($element['pen'], $pen, strlen($pen)) != 0;
            });
                               
            //note, res is a collection. not a query 
            $totalsittingexisting = $res_for_pen->sum('count');
            //$totalsittingexisting = $res->sum('count');

            // $days_already_entered = $q->all()->pluck(['from','to']);

            $totalwouldbe =  $totalsittingexisting + $overtime['count'];

                                             
            if($totalwouldbe > $maxsittings)
            {      
               array_push($myerrors,  $overtime['pen'] . '-' .$overtime['name'] . ' : Already saved sitting days = ' . $totalsittingexisting . '. + this (' .$overtime['count'] .') = ' . $totalwouldbe. '. (maximum possible: ' . $maxsittings .')' );
            
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
            
            $sittingsinrange =  abs($pos2-$pos1)+1;
            if($pos1 === false ||  $pos2 === false){
              //  $sittingsinrange =  $start_one->diffInDays($end_one)+1;

                //user has entered a date that is not a sitting day, manually

                $sittingsinrange = \App\Calender::with('session')
                                ->whereHas('session', function($query)  use ($request) { 
                                    $query->where('name', $request['session']);
                                  })                         
                                  ->where('date', '>=', $start_one)
                                  ->where('date', '<=', $end_one)
                                ->where('day_type','Sitting day')->count();


            }
            
            if( $overtime['count'] >  $sittingsinrange ){

              array_push($myerrors, $overtime['pen'] . '-' .$overtime['name'] . ' : From ' . $overtime['from'] . ' to ' . $overtime['to'] . ' there are only ' . $sittingsinrange .' sitting days.');
              return null;
            
            }

            //if the user has not entered all the sittings days within the period, make sure he enter leaves.

            if( $overtime['count'] < $sittingsinrange){

                $leaves = $sittingsinrange-$overtime['count'];
                $allleavesentered = true;
                $colleave = trim($overtime['worknature']);

                if(false !== stripos($colleave,'SUPPL') || 
                   \Auth::user()->username == 'sn.watchnward' || 
                    false !== stripos(\Auth::user()->username,'oo.sec') || 
                    false !== stripos(\Auth::user()->username,'oo.dyspkr') || 
                    false !== stripos(\Auth::user()->username,'oo.spkr') ){ 
                    //if supply, disregard leaves
                    $allleavesentered = true;
                }
                else if( $colleave == ''){
                    $allleavesentered = false;
                } 
                else {

                   $coma_items = count(explode(',', $colleave));

                   //$numextracted = preg_replace('/[^0-9]/', '', $colleave);
                   $hasnums = preg_match('/\d/', $colleave) > 0;

                   if( $coma_items == 1 && $leaves == 1){ //one leave and user might have entered '1'
                        if( $colleave == '1' || //comparing to string '1', not number 1. number cast converts '1/12' to 1
                        !$hasnums ){
                            $allleavesentered = false;       
                        }
                   }

                   if($coma_items < $leaves){
                     $allleavesentered = false;
                   }

                   //but if user has entered a range, it is ok
                   if($hasnums){ //should have digits
                    if(FALSE !== stripos($colleave, "to") ||
                       //FALSE !== stripos($colleave, "-") || // this can occur in dates itself.
                       FALSE !== stripos($colleave, "and") ||
                       FALSE !== stripos($colleave, "&") ||
                       FALSE !== stripos($colleave, "from") ){
                    
                        $allleavesentered = true;
                    }
                   } 

                }

                if(!$allleavesentered){

                  array_push($myerrors, $overtime['pen'] . '-' .$overtime['name'] . ' : Enter the '. $leaves . ' leave/late coming date(s) (See Note below).');
                  return null;
                
                }
            }

           
 
            if( $start_one < $sitting_start || $end_one > $sitting_end ) 
            {

                array_push($myerrors, $overtime['pen'] . '-' .$overtime['name'] . ' : Select date range between ' . $sitting_start->format('d-m-Y') . ' and ' . $sitting_end->format('d-m-Y') . ' (' . $start_one->format('d-m-Y') . ',' . $end_one->format('d-m-Y') . ')' );
                    return null;
            }

            
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
                       
                        array_push($myerrors, $overtime['pen'] . '-' .$overtime['name'] . ' : Dates overlap with another OT from ' . $e['from'] . ' - ' . $e['to'] . ' for '.$e['count'] . ' day(s) (' . $e->form->creator . ' )' );
                        return null;
                    }

                } //foreach
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

            return $form->id;

        });

        \Session::flash('message-success', 'Success: created form-no: ' . $formid ); 
           
   

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
        
        \Session::flash('message-success', 'Success: updated form-no: ' . $formid ); 

        return response()->json([
            'created' => true,
            'id' => $formid
         ]);
    

        //return redirect()->route('admin.my_forms.index');
    }

    public function getpdf()
    {

        $str_session = null;                 
        $str_status = null;
        $str_overtime_slot = null;
        $str_datefilter = null;
        $str_namefilter = null;
        $str_desigfilter = null;
        $str_idfilter = null;
        $str_created_by = null;
        
        $session = Input::get('session');
        $overtime_slot = Input::get('overtime_slot');
        $status =  Input::get('status');
        $datefilter =  Input::get('datefilter');
        $namefilter =  Input::get('namefilter');
        $desigfilter =  Input::get('desigfilter');
        $idfilter   =  Input::get('idfilter');
        $createdby =  Input::get('created_by');

        $forms = \App\Form::with(['created_by','owned_by','overtimes'])
                       ->filterStatus('Submitted')
                       ->where('creator', '<>','admin')
                       ->where('form_no', '>=', 0);
                      


        if (Input::filled('created_by')){ 
                           
            if($createdby != 'all'){
                //$forms = $forms->where( 'creator', $createdby);
                 $forms->where( 'creator', 'like', '%'.$createdby); //accomodate de.
                                         
                $str_created_by = '&created_by='.$createdby;
            }
            
        }
         
       
        if (Input::filled('session')){
             $forms = $forms->where('session',$session);
        }

        $str_session = '&session='.$session;

                      

        if (Input::filled('idfilter'))
        {
            $forms = $forms->where('id',$idfilter);
                   
            $str_idfilter = '&idfilter='.$idfilter;
            
        }


        if (Input::filled('overtime_slot')){
            if($overtime_slot == 'Non-Sittings'){
                $forms = $forms->where( 'overtime_slot' , '!=', 'Sittings' );    
            }
            else{
                $forms = $forms->whereOvertimeSlot($overtime_slot);
            }

            $str_overtime_slot = '&overtime_slot='.$overtime_slot;
        }

        if (Input::filled('datefilter')){
            
            $forms = $forms->filterDate( $datefilter );

            $str_datefilter = '&datefilter='.$datefilter;
        }

        if (Input::filled('namefilter')){
                    
            $forms = $forms->wherehas( 'overtimes', function($q) use ($namefilter){
               $q->where('pen','like', '%' . $namefilter.'%' );
            }); 
                
            $str_namefilter = '&namefilter='. $namefilter;
        }
    
        if (Input::filled('desigfilter')){
                        
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

       
        $htmlview = Input::get('viewall') != 'viewallpdf';
     
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


            $view =  view('admin.my_forms.showpdf', compact( 'index', 'form', 
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
