<?php

namespace App\Http\Controllers\Admin;

use App\PunchingForm;
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

       
    $temp =  Punching::                      
             where('date',$date)  
             ->where('pen',$pen) 
             ->first(); 
    
    if($temp){
        Log::info($temp);
       
   
      return [
            'punchin' => $temp['punch_in'],
            'punchout' => $temp['punch_out']
           
        ];
    } else return [];
        
    }


    public function index(Request $request)
    {
        if (! Gate::allows('my_form_access')) {
            return abort(401);
        }
        
   
       $begintime = microtime(true);

        //user can login and see forms even after data entry is disabled
        //$session_array = \App\Session::whereDataentryAllowed('Yes')->pluck('name');

        
        $session_array = \App\Session::whereshowInDatatable('Yes')->latest()->pluck('name');

     
        $forms = PunchingForm::with(['created_by'])
                     ->whereIn('session',$session_array)
                     ->CreatedByLoggedInUser()
                     //->where('creator','<>','admin') //exclude PA2MLA forms created by admin
                     ;


        
        // FILTERS
        $str_session = null;                 
        $str_status = null;
        
        $str_datefilter = null;
        $str_namefilter = null;
        $str_desigfilter = null;
        $str_idfilter = null;
        $str_created_by = null;
        
        $str_remarksfilter=null;
        $str_submittedbyfilter=null;

        $session = $request->query('session');
        $status =  $request->query('status');
        $datefilter =  $request->query('datefilter');
        $namefilter =  $request->query('namefilter');
        $desigfilter =  $request->query('desigfilter');
        $idfilter   =  $request->query('idfilter');
        $createdby =  $request->query('created_by');
        $worknaturefilter = $request->query('worknaturefilter');
        $remarksfilter = $request->query('remarksfilter');

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
       
        $sort =  $request->filled('sort') ? $request->query('sort') : 'updated_at'; // if user type in the url a column that doesnt exist app will default to id
        $order = $request->query('order') === 'asc' ? 'asc' : 'desc'; // default desc
                
        $forms = $forms->orderBy($sort, $order)->distinct()->paginate(10)
                                               ->appends($request->except('page'));
 

                                  
      
    
        
        $timetaken = round(microtime(true) - $begintime,4);

        return view('admin.punchings.index',compact('forms', 'session_array','session', 'timetaken' ));
    }



    public function preparevariablesandGotoView( $id=null )
    {
       
        $q = \App\Session::with('calender')->whereDataentryAllowed('Yes'); 
        
           
        $q = $q->latest();

        $session_array = $q->get();

        
        $sessions = $session_array->pluck('name');

        $latest_session = $sessions->first();
         
        $calenderdaysmap = [];
        $calenderdays2 = array();
     
        foreach ($session_array as $session) {
    
            $daysall = $session->calender()->orderby('date','asc');
                       
          
            $daysall->where('date', '<=', date('Y-m-d'));
            $days = $daysall->get(['date','day_type']);
           

            
            foreach ($days as $day) {
              
                $calenderdaysmap[$day['date']] = $day['day_type'];

                $calenderdays2[$session->name][] = $day['date'];    
            }
        }

       
      //hard corded. ugly i know. 
        $ispartimefulltime = 0;
        $iswatchnward = 0;
        $isspeakeroffice = 0;


        if($id)
        {
           $form = PunchingForm::findOrFail($id);
           
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

       
        
       
        JavaScript::put([
            'latest_session' => $latest_session,
        
            'old_calenderdayselected' => old('duty_date') ? old('duty_date') : '',
        
            'ispartimefulltime' => $ispartimefulltime,
            'iswatchnward' => $iswatchnward,
            'isspeakeroffice' => $isspeakeroffice,
        
        
            
        ]);
    
        $collapse_sidebar = true;
       
        { // forms
            if($id)
            {
                $form = PunchingForm::with(['created_by'])->findOrFail($id);

                
                                    
                return view('admin.punchings.edit', compact('form', 'data','sessions', 'collapse_sidebar' ));
            }
            else
            {
                return view('admin.punchings.create', compact('data','sessions', 'collapse_sidebar') );
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
    
        return $this->preparevariablesandGotoView( null);
       
    }
    public function create_copy($id)
    {
        if (! Gate::allows('my_form_edit')) {
            return abort(401);
        }  
        
        //$form = PunchingForm::findOrFail($id);
      
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

           $form = PunchingForm::create( [
               
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

       
        $request->session()->flash('message-success', 'Success: created form no:' . $formid ); 
        
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
        
        $form = PunchingForm::findOrFail($id);

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

        $form = PunchingForm::findOrFail($id);

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

        $request->session()->flash('message-success', 'Success: updated form-no: ' . $formid ); 


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

        $form = PunchingForm::with(['created_by','owned_by', 'overtimes'])->findOrFail($id);
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
       
        $descriptionofday = '';
        $needsposting = false;
        if($form->overtime_slot != 'Sittings'   && $form->owner == $loggedinusername && $form->owner != 'admin'){
            $date = Carbon::createFromFormat(config('app.date_format'), $form->duty_date)->format('Y-m-d');

 
            $calender = Calender::where('date', $date )->first();
            $daytype = $calender->day_type;

            $descriptionofday = $calender->description;

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

        ]);

        $prev=null;
        $next=null;
        /* if(\Auth::user()->isAdminorAudit() && $form->owner=='admin')
        {
            $prev = PunchingForm::where('id', '<', $form->id)
                        ->where('owner','admin')
                        ->where('creator','<>','admin')//no pa2admin
                        ->where('session',$form->session)
                        ->when(\Auth::user()->isAudit(),
                            function($q){
                                return $q->where('form_no','>=', 0);
                           })
                        ->max('id');

            $next = PunchingForm::where('id', '>', $form->id)
                        ->where('owner','admin')
                        ->where('creator','<>','admin') //no pa2admin
                        ->where('session',$form->session)
                        ->when(\Auth::user()->isAudit(),
                            function($q){
                                return $q->where('form_no','>=', 0);
                           })
                        ->min('id');

        } */


      

        return view('admin.my_forms.show', compact('form', 
                    'overtimes','daytype','submmittedby',  'createdby', 'canforward' , 'cansubmittoaccounts', 'descriptionofday'
                    ,'prev','next', 'romankla', 'sessionnumber_th', 'sessionnumber','malkla',
                    'needsposting'));
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

        $form = PunchingForm::findOrFail($id);

        if( ($form->owner != \Auth::user()->username) && !\Auth::user()->isAdmin())
        {
            return abort(401);
        }


        
        
        Overtime::where('form_id', $form->id)->delete();

        $form->delete();

        return redirect()->route('admin.my_forms.index');
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
        
        $checksecretaryattendance = false;
        if( \Config::get('custom.check_attendance')) {
            $form = $formid ? PunchingForm::find($formid) : null; //if updating a form, get creator field
            if( \App\User::needsPostingOrder( $form ? $form->creator : \Auth::user()->username) ){
                $checksecretaryattendance = true;
            }
        }
        
        $pentoattendace = null;
        $pentodays = null;
        $attendance = null;

        if($checksecretaryattendance){
            
            $employee_ids = \App\Employee::wherein('pen', $pens->toArray())->pluck('id');
            
            $attendance = \App\Attendance::with('session', 'employee')
                                ->whereHas('session', function($query)  use ($request) { 
                                    $query->where('name', $request['session']);})
                                ->wherein('employee_id', $employee_ids->toArray() )
                                ->get();

            //$pensinattendance = $attendance->pluck( 'employee.pen' );
            /*$pensnotinattendance = $pens->diff($pensinattendance);
            if($pensnotinattendance->count())
            {
                array_push($myerrors,  'Attendance not found for:' . $pensnotinattendance->implode(',') );
                return null;
            }*/
        }



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

             
            
            if($checksecretaryattendance){


                if( ! $attendance->contains('employee.pen', $overtime['pen'] ) ){
                    array_push($myerrors,  $overtime['pen'] . '-' .$overtime['name'] . ' : Attendance not found.' );

                }
                else {
                    //there can be multiple entries for a pen in attendace due to section changes during session
                    $total_ot_asper_secretary = $attendance->where( 'employee.pen', $overtime['pen']  )->sum('total');
                    
                    if($totalwouldbe > $total_ot_asper_secretary)
                    {      
                        
                        if($totalsittingexisting){
                            array_push($myerrors,  $overtime['pen'] . '-' .$overtime['name'] . ' : Exceeds attendance as per O/S. Already saved ' . $totalsittingexisting . '. + this (' .$overtime['count'] .') = ' . $totalwouldbe. '. (max possible: ' . $total_ot_asper_secretary .')' );
                        } else {
                            array_push($myerrors,  $overtime['pen'] . '-' .$overtime['name'] . ' : Exceeds attendance as per O/S - ' .$overtime['count'] . '. (max possible: ' . $total_ot_asper_secretary .')' );
                        }
                                    
                        return null;   
                    }
                }
            }

            if($totalwouldbe > $maxsittings)
            {      
               if($totalsittingexisting){
                array_push($myerrors,  $overtime['pen'] . '-' .$overtime['name'] . ' : Already saved ' . $totalsittingexisting . '. + this (' .$overtime['count'] .') = ' . $totalwouldbe. '. (maximum possible: ' . $maxsittings .')' );
               } else {
                array_push($myerrors,  $overtime['pen'] . '-' .$overtime['name'] . ' : This = ' . $overtime['count'] . '. (maximum possible: ' . $maxsittings .')' );
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

            if( !$checksecretaryattendance && $overtime['count'] < $sittingsinrange  ){

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

                        $hasdatestring = preg_match('/\d{1,2}[-\/\\.]+\d{1,2}/', $colleave) > 0;

                        if(!$hasdatestring){
                         $allleavesentered = false;          
                        }
                   }

                   if($coma_items < $leaves){
                     $allleavesentered = false;
                   }

                   /*
                   //but if user has entered a range, it is ok
                   if($hasnums && $leaves > 9){ //should have digits. 
                    if(FALSE !== stripos($colleave, "to") ||
                       //FALSE !== stripos($colleave, "-") || // this can occur in dates itself.
                       FALSE !== stripos($colleave, "and") ||
                      // FALSE !== stripos($colleave, "&") ||
                       FALSE !== stripos($colleave, "from") ){
                    
                        $allleavesentered = true;
                    }
                   }*/ 

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

            $form = PunchingForm::create( [
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

        $request->session()->flash('message-success', 'Success: created form-no: ' . $formid ); 
           
   

        return response()->json([
           'created' => true,
           'id' => $formid
        ]);



    }

    public function forward(Request $request, $id)
    {

        $form = PunchingForm::findOrFail($id);
    

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
        $form = PunchingForm::findOrFail($id);
    

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


        $maxform_no = \App\PunchingForm::whereSession($form->session)->max('form_no');
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

    
    public function update_sitting(Request $request, $id)
    {
        if (! Gate::allows('my_form_edit')) {
            return abort(401);
        } 

        $form = PunchingForm::findOrFail($id);

    

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
        
        $request->session()->flash('message-success', 'Success: updated form-no: ' . $formid ); 

        return response()->json([
            'created' => true,
            'id' => $formid
         ]);
    

        //return redirect()->route('admin.my_forms.index');
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

        $forms = \App\PunchingForm::with(['created_by','owned_by','overtimes'])
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
                $forms = $forms->where( 'overtime_slot' , '!=', 'Sittings' );    
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


           // $form = PunchingForm::with(['created_by','owned_by'])->findOrFail($id);

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
