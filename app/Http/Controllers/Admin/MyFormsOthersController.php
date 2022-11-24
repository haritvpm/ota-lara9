<?php
namespace App\Http\Controllers\Admin;

use App\FormOther;
use App\OvertimeOther;
use App\Calender;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
//use Laracasts\Utilities\JavaScript\JavaScriptFacade;
use JavaScript;
use Carbon\Carbon;
use Auth;
use PDF;

class MyFormsOthersController extends Controller
{
    public function index()
    {
        if (! Gate::allows('my_form_others_access')) {
            return abort(401);
        }


        //user can login and see forms even after data entry is disabled
        //$session_array = \App\Session::whereDataentryAllowed('Yes')->pluck('name');
        $session_array = \App\Session::whereshowInDatatable('Yes')->latest()->pluck('name');


        $date_ago = Carbon::today()->subMonths(3);

        $session_array_todel = \App\Session::where('dataentry_allowed', 'No')
                            ->whereDate('created_at', '<', $date_ago->toDateString())
                            ->whereDate('updated_at', '<', $date_ago->toDateString())
                            ->oldest()->pluck('name');

        $forms = FormOther::with(['created_by','owned_by','overtimes'])
                      ->withCount('overtimes')   
                     ->whereIn('session',$session_array)
                     ->CreatedOrOwnedOrApprovedByLoggedInUser()
                     ->where('creator','<>','admin'); //exclude any forms created by admin
        
        // FILTERS
        $str_session = null;                 
        $str_status = null;
        $str_overtime_slot = null;
        $str_datefilter = null;
        $str_namefilter = null;
        $str_desigfilter = null;
        $str_idfilter = null;
        
        $session = $request->query('session');
        $overtime_slot = $request->query('overtime_slot');
        $status =  $request->query('status');
        $datefilter =  $request->query('datefilter');
        $namefilter =  $request->query('namefilter');
        $desigfilter =  $request->query('desigfilter');
        $idfilter   =  $request->query('idfilter');
        

        if ($request->filled('session')){
             
             // $session = $session_array[0];
        
            $forms->where('session',$session);

            $str_session = '&session='.$session;
        }


        if ($request->filled('status'))
        {
           
            
        }else{
            $status =  'Draft';

        }

        $forms = $forms->filterStatus($status);
                   
        $str_status = '&status='.$status;


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
                $q->where('designation','like', '%' . $desigfilter.'%' );
             }); 
                 
             $str_desigfilter = '&desigfilter=' . $desigfilter;
        }
     

        $sort =  $request->filled('sort') ? $request->query('sort') : 'id'; // if user type in the url a column that doesnt exist app will default to id
        $order = $request->query('order') === 'asc' ? 'asc' : 'desc'; // default desc
                
        $forms = $forms->orderBy($sort, $order)->paginate(15)
                                               ->appends($request->except('page'));
         
        //this inverts sorting order for next click                                       
        $querystr = '&order='.($request->query('order') == 'asc' || null ? 'desc' : 'asc').$str_status.$str_overtime_slot.$str_datefilter.$str_namefilter.$str_desigfilter.$str_idfilter;
            
        $to_approve = 0; 
        //sections and admins have nothing to approve
        if (0 === strpos( auth()->user()->username  , 'od.') || auth()->user()->isAdmin()) {
           // It starts with 'http'
            $to_approve = -1;

        }

        return view('admin.my_forms_others.index',
                    compact('forms','querystr', 'to_approve', 'session_array','session','session_array_todel' ));
    }



    public function preparevariablesandGotoView( $issitting, $id=null )
    {
        $enum_overtime_slot = FormOther::$enum_overtime_slot;

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
      

      //  $designations = \App\DesignationsOther::all()->sortby('designation')->pluck('designation');
       // dd($calenderdaysmap);

        $data["calenderdaysmap"] = json_encode($calenderdaysmap);
       // $data["designations"] = json_encode($designations);
        $data["calenderdays2"] = json_encode($calenderdays2);

        $presets = \App\Preset::where('user_id',\Auth::user()->id)->pluck('name');
       

        $SThirdOT = \App\Setting::where('name', \Auth::user()->username .'-SThirdOT')->value('value');
        $NSSecondOT = \App\Setting::where('name', \Auth::user()->username .'-NSSecondOT')->value('value');
        $NSThirdOT = \App\Setting::where('name', \Auth::user()->username .'-NSThirdOT')->value('value');
        $SThirdOT = $SThirdOT == null ? 1 : (int)$SThirdOT;
        $NSSecondOT = $NSSecondOT == null ? 1 : (int)$NSSecondOT;
        $NSThirdOT = $NSThirdOT == null ? 1 : (int)$NSThirdOT;


        JavaScript::put([
            'latest_session' => $latest_session,
            'old_slotselected' => old('overtime_slot') ? old('overtime_slot') : '',
            'old_calenderdayselected' => old('duty_date') ? old('duty_date') : '',
            'presets' => $presets,
            'SThirdOT' => $SThirdOT,
            'NSSecondOT' => $NSSecondOT,
            'NSThirdOT' => $NSThirdOT,
            
        ]);
    
        $collapse_sidebar = true;

        if(!$issitting){
            if($id)
            {
                $form = FormOther::with(['created_by','overtimes'])->findOrFail($id);
                                    
                return view('admin.my_forms_others.edit', compact('form', 'data','sessions','enum_overtime_slot', 'collapse_sidebar'  ));
            }
            else
            {
                return view('admin.my_forms_others.create', compact('data','sessions','enum_overtime_slot', 'collapse_sidebar'  ) );
            }
        }
        else{
            if($id)
            {
                $form = FormOther::with(['created_by','overtimes'])->findOrFail($id);
                                    
                return view('admin.my_forms_others.edit_sitting', compact('form', 'data','sessions', 'collapse_sidebar'  ));
            }
            else
            {
                return view('admin.my_forms_others.create_sitting', compact('data','sessions', 'collapse_sidebar' ) );
            }
        }
       

                
    }

    /**
     * Show the form for creating new FormOther.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('my_form_others_create')) {
            return abort(401);
        } 
            

        return $this->preparevariablesandGotoView( false, null);
       
    }

    public function createovertimes( Request $request, &$myerrors, $formid=null)
    {
        $date = Carbon::createFromFormat(config('app.date_format'), $request['duty_date'])->format('Y-m-d');
           

        $collection = collect($request->overtimes);


       // $designations = $collection->pluck('designation');

        $rates = null;
        //$rates = \App\DesignationsOther::wherein ('designation', $designations )->pluck('rate','designation');
              
        $pens = $collection->pluck('pen');
        $pens->transform(function ($item, $key) {
            return  substr($item,0, strpos($item,'-'));
        });
        
       // $placeholder = implode(', ', array_fill(0, count($pens), '?')); //returns '?, ?'

        //find if the same employee has occupied this slot on this day
        $res =  \App\OvertimeOther::with('form')
                    //->whereRaw("SUBSTRING_INDEX(`pen`, '-' ,1) in ($placeholder)",$pens )
                    ->wherein('designation',$pens ) //designation stores pen
                    ->whereHas('form', function($query)  use ($request,$date,$formid) { 
                          $query->where('duty_date', $date)
                                //->where('overtime_slot', $request['overtime_slot']) //POL - no more than one OT per day
                                ->where('overtime_slot','<>', 'Sittings')
                                ->where('session', $request['session'])
                                ->where('id', '!=', $formid); //skip this item if on update
                    })->get();

        /*if( count($emp) > 0){
             array_push($myerrors, 'An entry already exists for ' . $request['overtime_slot'] . ' OT on this day for '    .  $emp->implode(','));
             return null;
        }*/



        //$designations = $collection->pluck('designation');

        $rates = null;
        //$rates = \App\DesignationsOther::wherein ('designation', $designations )->pluck('rate','designation');
     
        $overtimes = $collection->transform(function($overtime)  use ($res,$request,$date, &$myerrors,$formid,$rates) 
        {

            $pen =   substr( $overtime['pen'],0, strpos( $overtime['pen'],'-' ) );   

            
            $emp = $res->reject(function($element) use ($pen) {
            
                return strncasecmp($element['pen'], $pen, strlen($pen)) != 0;
            });

            $res_for_thisslot = $emp->reject(function($element) use ($request) {
                return stripos($element->form->overtime_slot, $request['overtime_slot']) === false;
            });
            
            $empslot = $res_for_thisslot->map(function ($item, $key) {
                            return $item['pen'] ;
                        });

            if( count($empslot) > 0){
                 array_push($myerrors, 'Already entered ' . $request['overtime_slot'] . ' OT on this day for '  .  $empslot->implode(','));
                 return null;
            }

            if( $emp->count() >= 3  )
            {
               array_push($myerrors, $overtime['pen'] . ' : 3 OTs already entered for the day');

            
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

                $isoverlap = (($timefrom < $timeto_comp) && ($timeto > $timefrom_comp)) || 
                    ($timefrom == $timefrom_comp) || ($timeto == $timeto_comp) ;

                if($isoverlap){
                    
                 array_push($myerrors, $overtime['pen'] . ' : Times overlap with another OT from ' . $e['from'] . ' - ' . $e['to'] . ' (' . $e->form->overtime_slot . ' OT) on this day ');
                    return null;
                }

            }

            //store pen in the designation field, so we can access employee data thru that
            {
                return new OvertimeOther([
                    'pen'           => $overtime['pen'],
                    'designation'   => $pen,//$overtime['designation'],
                    'from'          => $overtime['from'],
                    'to'            => $overtime['to'], 
                    'worknature'    => $overtime['worknature'],
                    'count'         => '1',
                    'rate'          => '0',//$rates[$overtime['designation']],
                    
                    ]);
            }
            

        });
    
        return $overtimes;
           
    }
   
    /**
     * Store a newly created FormOther in storage.
     *
     * @param  \App\Http\Requests\StoreFormsRequest  $request
     * @return \Illuminate\Http\Response
     */
    //public function store(StoreFormsRequest $request)
    public function store(Request $request)
    {
        if (! Gate::allows('my_form_others_create')) {
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
      
        $formid =  \DB::transaction(function() use ( $request, $overtimes)  {

           $form = FormOther::create( [
               
                'session' => $request['session'],
                'creator' => \Auth::user()->username,
                'owner'=> \Auth::user()->username,
                'duty_date'  => $request['duty_date'],
                'overtime_slot' => $request['overtime_slot'],
                'remarks' => $request['remarks'],
            ]);
          

            $form->overtimes()->saveMany($overtimes);

            return $form->id ;
        });

             

       \Session::flash('message-success', 'Success: created form no:' . $formid ); 

        return response()->json([
           'created' => true,
           'id' => $formid
        ]);

    }


    /**
     * Show the form for editing FormOther.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('my_form_others_edit')) {
            return abort(401);
        }      
        
        $form = FormOther::findOrFail($id);

        $issittingday = ($form->overtime_slot == 'Sittings');

        return $this->preparevariablesandGotoView($issittingday, $id ) ;
        
    }

    /**
     * Update FormOther in storage.
     *
     * @param  \App\Http\Requests\UpdateFormsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         if (! Gate::allows('my_form_others_edit')) {
            return abort(401);
        }

        $form = FormOther::findOrFail($id);

        if( $form->owner != \Auth::user()->username)
        {
            return abort(401);
        }


        $date = Carbon::createFromFormat(config('app.date_format'), $request['duty_date'])->format('Y-m-d');

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
      
       

       $formid =  \DB::transaction(function() use ($form, $request, $overtimes) {

            
            //no need to update creator and owner
            $form->update( [
                // 'session' => $request['session'],
                // 'duty_date'  => $request['duty_date'],
                // 'overtime_slot' => $request['overtime_slot'],
                'remarks' => $request['remarks'],
                'updated_at' => Carbon::now(),
            ]);


             //see if user has made any changes

            $overtimes_old = OvertimeOther::where('form_id', $form->id)->get();
            //same number or added new items
            if( $overtimes_old->count() && $overtimes->count()){

                //update same row indices
                $i=0;
                $same_rows = min($overtimes_old->count(), $overtimes->count());
                for (; $i < $same_rows; $i++) { 
                   OvertimeOther::where('id', $overtimes_old[$i]['id'])
                            ->update($overtimes[$i]->toarray());
               
                }
                
                if($overtimes_old->count() < $overtimes->count()){
                    //update if a new row added
                    $overtimes = $overtimes->slice($i);
                    $form->overtimes()->saveMany($overtimes);
                } else if ( $overtimes_old->count() > $overtimes->count()) {
                    //remove rows removed
                    $idsremoved = $overtimes_old->slice($i)->pluck('id')->toarray();
                    OvertimeOther::wherein('id', $idsremoved)
                            ->where('form_id', $form->id)->delete();
                }

                return $form->id;
            }
            

            //old code, where we delete everything and add. unreachable code


            OvertimeOther::where('form_id', $form->id)->delete();
        
            $form->overtimes()->saveMany($overtimes);

            return $form->id;

        });

        \Session::flash('message-success', 'Success: updated form-no: ' . $formid ); 

        return response()->json([
            'created' => true,
            'id' => $formid
         ]);

       // return redirect()->route('admin.my_forms_others.index');
    }


    /**
     * Display FormOther.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('my_form_others_access')) {
            return abort(401);
        }

        $overtimes = \App\OvertimeOther::with("employeesother")->where('form_id', $id)->get();


        $form = FormOther::with(['created_by','owned_by'])->findOrFail($id);
       

        $daytype = null;

        //find forwardable users.
        $loggedinusername = \Auth::user()->username;

/*
        $forwardarray = null;

        if($loggedinusername == $form->owner){

            $routes = collect();

            //if we are a section, we will have a route
            if (0 === strpos($loggedinusername, 'od.')) {
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


        $cansubmittoaccounts =  !Auth::user()->isAdmin() ;// false;

     
        $descriptionofday = '';   
        
        
        if($form->overtime_slot != 'Sittings'){
            $date = Carbon::createFromFormat(config('app.date_format'), $form->duty_date)->format('Y-m-d');
 
            $calender = Calender::where('date', $date )->first();
            $daytype = $calender->day_type;

            $descriptionofday = $calender->description;

            
        }
      

        
        $submmittedby = $form->SubmitedbyNames;
      
        //$canforward = $forwardarray && $forwardarray->count() ? true : false;
        $canforward = false;

        //$initival = $canforward ? $forwardarray->keys()->first() : '';

        JavaScript::put([
            //'forwardarray' => $forwardarray,
            'formid' => $form->id,
           // 'initalvalue' => $initival,
            'remarks' => $form->remarks,
            
        ]);


        $session = \App\Session::where('name', $form->session )->first();
        $romankla = $session->getRomanKLA();
        $sessionnumber = $session->session .  '<sup>' . $session->getOrdinalSuffix($session->session) . '</sup>';

        return view('admin.my_forms_others.show', compact('form', 
                    'overtimes','daytype','submmittedby', 'canforward' , 'cansubmittoaccounts', 'descriptionofday', 'romankla', 'sessionnumber'));
    }


    /**
     * Remove FormOther from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('my_form_others_edit')) {
            return abort(401);
        } 
        $form = FormOther::findOrFail($id);
        
        OvertimeOther::where('form_id', $form->id)->delete();

        $form->delete();

        return redirect()->route('admin.my_forms_others.index');
    }


    public function create_sitting()
    {
        if (! Gate::allows('my_form_others_create')) {
            return abort(401);
        } 
          
        return $this->preparevariablesandGotoView(true, null);
       
    }

    public function createovertimes_sitting( Request $request, &$myerrors, $formid=null)
    {
                      
        $maxsittings = \App\Calender::with('session')
                                ->whereHas('session', function($query)  use ($request) { 
                                    $query->where('name', $request['session']);
                                  })                              
                                ->where('day_type','Sitting day')->count();
        
        $collection = collect($request->overtimes);
        
        $rates = null;
        
        $pens = $collection->pluck('pen');
        $pens->transform(function ($item, $key) {
            return  substr($item,0, strpos($item,'-'));
        });


        $query = \App\OvertimeOther::with('form')
                              ->wherein('designation',$pens ) //designation stores pen
                              ->whereHas('form', function($q)  use ($request,$formid) { 
                                $q->where('overtime_slot', 'Sittings')
                                      ->where('session', $request['session'])
                                      ->where('id', '!=', $formid); //skip this item if on update
                            });


        //we cannot use pluck, pluck seems to return only distinct 'count'
        //$res = $query->get(['count', 'pen']);
        $res = $query->get();                      

        //\Log::info(print_r($res, true));


        $overtimes =$collection->transform(function($overtime) 
                                           use ($res, $request,$formid, &$myerrors,$maxsittings,$rates) 
        {
            //$pen = substr($overtime['pen'], 0, strpos($overtime['pen'], '-')+1); //+1 to include '-'
            $pen_actual =   substr( $overtime['pen'],0, strpos( $overtime['pen'],'-' ) ); 

             /*$query = \App\OvertimeOther::with('form')
                              ->where('pen', 'like' , $pen . '%')
                              ->whereHas('form', function($q)  use ($request,$formid) { 
                                $q->where('overtime_slot', 'Sittings')
                                      ->where('session', $request['session'])
                                      ->where('id', '!=', $formid); //skip this item if on update
                            });

            //we cannot use pluck, pluck seems to return only distinct 'count'
            $res = $query->get(['count', 'pen']);*/

            $res_for_pen = $res->reject(function($element) use ($pen_actual) {
                return strncasecmp($element['pen'], $pen_actual, strlen($pen_actual)) != 0;
            });
                               
            //note, res is a collection. not a query 
            //$totalsittingexisting = $res->where('pen', $overtime['pen'])->sum('count');
            $totalsittingexisting = $res_for_pen->sum('count');
            //$totalsittingexisting = $res->sum('count');

            // $days_already_entered = $q->all()->pluck(['from','to']);

            $totalwouldbe =  $totalsittingexisting + $overtime['count'];

                                 
            if($totalwouldbe > $maxsittings)
            {
              
               array_push($myerrors, $overtime['pen'] . ' : Existing sitting days = ' . $totalsittingexisting . ', Plus this, exceeds the maximum of ' . $maxsittings );
               
            }
            else
            {                
                //$pen_actual =   substr( $overtime['pen'],0, strpos( $overtime['pen'],'-' ) );
                return new OvertimeOther([
                    'pen'           => $overtime['pen'],
                    'designation'   => $pen_actual,//$overtime['designation'],
                    'from'          => $overtime['from'],
                    'to'            => $overtime['to'], 
                    'worknature'    => $overtime['worknature'],
                    'count'         => $overtime['count'],
                    'rate'          => '0',//$rates[$overtime['designation']],
                    
                    ]);
            }

        });
    
        return $overtimes;
           
    }
   
    public function store_sitting(Request $request)
    {
        if (! Gate::allows('my_form_others_create')) {
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
                
         
        $formid = \DB::transaction(function() use ( $request, $overtimes)     {

            $form = FormOther::create( [
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

        $form = FormOther::findOrFail($id);
    

        if( $form->owner != \Auth::user()->username)
        {
            return abort(401);
        }
        
        
      
        //if we are not the creator, add us to the submitted field
        //if (0 === strpos($loggedinusername, 'sn.')) 

        $submitted_by = $form->submitted_by;

        if( $form->creator != $form->owner ){
            $submitted_by .= ($submitted_by != '') ? ( ',' . $form->owner) : $form->owner;
        }


        //change owner
         $form->update( [
            'owner' => $request['owner'],
            'submitted_by' => $submitted_by,
            'submitted_on' => Carbon::now(),
        ]);


        return response()->json([
           'result' => true,
           
        ]);

    }
    
    public function submittoaccounts(Request $request, $id)
    {
        $form = FormOther::findOrFail($id);
    

        if( $form->owner != \Auth::user()->username)
        {
            return abort(401);
        }
      
        //if we are not the creator, add us to the submitted field
        //if (0 === strpos($loggedinusername, 'sn.')) 

        $submitted_by = $form->submitted_by;
        $submitted_by .= ($submitted_by != '') ? ( ',' . $form->owner) : $form->owner;

        $maxform_no = \App\FormOther::whereSession($form->session)->max('form_no');
        if($maxform_no < 0){
           $maxform_no = 0; //plan to use form no field to -1 for rejected
        }

        //change owner
         $form->update( [
            'owner' => $request['owner'],
            'submitted_by' => $submitted_by,
            'submitted_on' => Carbon::now(),
            'form_no' => $maxform_no+1,
        ]);


        return response()->json([
           'result' => true,
           
        ]);

    }

    public function sendback(Request $request, $id)
    {
        $form = FormOther::findOrFail($id);
    

        if( $form->owner != \Auth::user()->username)
        {
            return abort(401);
        }
      

        //change owner
         $form->update( [
            'owner' => $form->creator,
            'submitted_by' => null,
            'submitted_on' => null,
            'remarks' => $request['remarks'],

        ]);


        return response()->json([
           'result' => true,
           
        ]);

    }

    public function update_sitting(Request $request, $id)
    {
        /* if (! Gate::allows('form_edit')) {
            return abort(401);
        } */

        $form = FormOther::findOrFail($id);

    

        if( $form->owner != \Auth::user()->username)
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

            $overtimes_old = OvertimeOther::where('form_id', $form->id)->get();
            //same number or added new items
            if( $overtimes_old->count() && $overtimes->count()){

                //update same row indices
                $i=0;
                $same_rows = min($overtimes_old->count(), $overtimes->count());
                for (; $i < $same_rows; $i++) { 
                   OvertimeOther::where('id', $overtimes_old[$i]['id'])
                            ->update($overtimes[$i]->toarray());
               
                }
                
                if($overtimes_old->count() < $overtimes->count()){
                    //update if a new row added
                    $overtimes = $overtimes->slice($i);
                    $form->overtimes()->saveMany($overtimes);
                } else if ( $overtimes_old->count() > $overtimes->count()) {
                    //remove rows removed
                    $idsremoved = $overtimes_old->slice($i)->pluck('id')->toarray();
                    OvertimeOther::wherein('id', $idsremoved)
                            ->where('form_id', $form->id)->delete();
                }

                return $form->id;
            }
            
            //old code, where we delete everything and add. unreachable code

            OvertimeOther::where('form_id', $form->id)->delete();

            $form->overtimes()->saveMany($overtimes);

            return $form->id;
        });
        
        \Session::flash('message-success', 'Success: updated form-no: ' . $formid ); 

        return response()->json([
            'created' => true,
            'id' => $formid
         ]);
    

        //return redirect()->route('admin.my_forms_others.index');
    }



    public function getpdf(Request $request)
    {
        if (! Gate::allows('my_form_others_access')) {
            return abort(401);
        }

        //user can login and see forms even after data entry is disabled
        //$session_array = \App\Session::whereDataentryAllowed('Yes')->pluck('name');
        $session_array = \App\Session::whereshowInDatatable('Yes')->latest()->pluck('name');

        $forms = FormOther::with(['created_by','owned_by','overtimes'])
                     ->whereIn('session',$session_array)
                     ->CreatedOrOwnedOrApprovedByLoggedInUser()
                     ->where('creator','<>','admin'); //exclude PA2MLA forms created by admin
        
        // FILTERS
        $str_session = null;                 
        $str_status = null;
        $str_overtime_slot = null;
        $str_datefilter = null;
        $str_namefilter = null;
        $str_desigfilter = null;
        $str_idfilter = null;
        
        $session = $request->query('session');
        $overtime_slot = $request->query('overtime_slot');
        $status =  $request->query('status');
        $datefilter =  $request->query('datefilter');
        $namefilter =  $request->query('namefilter');
        $desigfilter =  $request->query('desigfilter');
        $idfilter   =  $request->query('idfilter');
        

        if ($request->filled('session')){
             
        }
        else{
            $session = $session_array[0];
        }

        $forms = $forms->where('session',$session);



        if ($request->filled('status'))
        {
           $forms = $forms->filterStatus($status);
                   
           $str_status = '&status='.$status;
            
        }
/*
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
                $q->where('designation','like', '%' . $desigfilter.'%' );
             }); 
                 
             $str_desigfilter = '&desigfilter=' . $desigfilter;
        }*/
     
     
        $sort =  $request->filled('sort') ? $request->query('sort') : 'id'; // if user type in the url a column that doesnt exist app will default to id
        $order = $request->query('order') === 'asc' ? 'asc' : 'desc'; // default desc
                
        $forms = $forms->orderBy($sort, $order)->get();;
         
        
        $combined = null;
        $index = 0;
        
        $htmlview = $request->query('viewall') != 'viewallpdf';

        $totalforms = count($forms);

        $session = null;
        $romankla = null;
        $sessionnumber = null;

        date_default_timezone_set('Asia/Kolkata');
        set_time_limit(100);


        $printdate = date('d/m/Y', time());


        $loggedinusername = \Auth::user()->username;

        $cansubmittoaccounts =  false;
        $canforward = false;

        foreach ($forms as $form) 
        {
            $index += 1;
            $id = $form->id;    

            $rowsinprint = $form->overtimes()->count() + 5; 
            //5 for "signature" text

            //18 - rows in first page in pdf
            //23 rows in other pages
            $pagesreq = (int)ceil(($rowsinprint-18)/23) + 1 ; 

            if($htmlview){ //
                $pagesreq = (int)ceil(($rowsinprint-15)/20) + 1 ;
            } 



            $overtimes = \App\OvertimeOther::with("employeesother")->where('form_id', $id)->get();


            $daytype = null;

                       
            if($form->overtime_slot != 'Sittings'){
                $date = Carbon::createFromFormat(config('app.date_format'), $form->duty_date)->format('Y-m-d');
     
                $daytype = Calender::where('date', $date )->first()->day_type;
            }
            
            $submmittedby = $form->SubmitedbyNames;
          
            /*
            JavaScript::put([
                //'forwardarray' => $forwardarray,
                'formid' => $form->id,
               // 'initalvalue' => $initival,
                'remarks' => $form->remarks,
                
            ]);*/

            if($index == 1){
                $session = \App\Session::where('name', $form->session )->first();
                $romankla = $session->getRomanKLA();
                $sessionnumber = $session->session .  '<sup>' . $session->getOrdinalSuffix($session->session) . '</sup>';
                
            }

            $view = null;

            if($htmlview){
                $view = view('admin.my_forms_others.showhtml', compact('index','form', 
                        'overtimes','daytype','submmittedby', 'canforward' , 'cansubmittoaccounts', 'romankla', 'sessionnumber', 'htmlview', 'printdate'));
            } else {
                $view = view('admin.my_forms_others.showpdf', compact('index','form', 
                        'overtimes','daytype','submmittedby', 'canforward' , 'cansubmittoaccounts', 'romankla', 'sessionnumber', 'htmlview', 'printdate'));

            }

               if($index == 5){
               // break;
               }

            //if($htmlview)
            {

                //remove multiple body tags of each view

                if($index == 1){
                    $view = str_replace("</body>","",$view);
                }
                else 
                if($totalforms == $index){
                    $view =str_replace("<body>","",$view);   
                } else {
                    $view =str_replace("</body>","",$view);   
                    $view =str_replace("<body>","",$view); 
                }

                //if($htmlview)
                {
                 if($totalforms != $index){
                    $view .=  "<div class=\"page-break\"></div>";

                    //double side printing, make sure next form is at odd page
                    //dd($pagesreq%2==1);
                   
                    if($pagesreq%2==1){
                        $view .=  "<div class=\"page-break\">&nbsp;</div>";
                    }

                }}
            } 

            $combined .= $view ;
           
        }

       $s = "<!DOCTYPE html><html><head><title>Other Department</title><link href='" .  url('/') . "/adminlte/bootstrap/css/bootstrap.min.css' rel='stylesheet'></head>";

       if(!$htmlview){ //css is ignord by Dompdf
            $s = "<!DOCTYPE html><html><head><title>Other Department</title>

<style type=\"text/css\">

table {
    table-layout: auto;
    border-collapse: collapse;
    width: 100%;
}
table td {
    
    white-space: nowrap;
}

.table2 td {
  border: 1px solid #ddd;
}
.table2 th {
    ;border: 1px solid #ddd;
     
}

.table > tbody > tr > td {
     vertical-align: middle;
     
}

</style>

<style type=\"text/css\">
@media print {
  a[href]:after {
    content: none !important;
  }
 
 
}

th{
    font-weight: normal;
     padding: 1px;
     white-space: nowrap
}
</style>

<style>
.page-break {
    page-break-after: always;
}
.page-break-must {
    page-break-before: always;
}

.nopage-break {
    page-break-before: avoid;
}


.monospacefont{
    font-family: monospace;
 }
</style>
            </head>";
        }

       $s .= $combined . "</html>";

        if(!$htmlview){
           return ($s);
           //return PDF::loadHTML($s)->setPaper('a4', 'landscape')->stream()->header('Content-Type','application/pdf');
            /*$pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($s);
            return $pdf->stream()->header('Content-Type','application/pdf');
            //return $pdf->download('otherdept.pdf');*/
        }
        else {

           return $s;
           
        }
    }

    public function clearold()
    {
        if (! Gate::allows('my_form_others_access')) {
            return abort(401);
        }

        $session = $request->query('session2del');

        $forms = FormOther::where('session',$session)->get();

        $count = $forms->count();

        FormOther::where('session',$session)->delete();

        \Session::flash('message-success', 'Deleted ' . $count . ' forms' ); 



        return redirect()->route('admin.my_forms_others.index');


    }

}
