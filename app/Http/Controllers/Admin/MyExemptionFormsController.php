<?php
namespace App\Http\Controllers\Admin;

use App\Exemptionform;
use App\Exemption;
use App\Calender;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
//use Laracasts\Utilities\JavaScript\JavaScriptFacade;
use JavaScript;
use Carbon\Carbon;
use Auth;

class MyExemptionFormsController extends Controller
{
    const SUBMISSION_DAYS_BEFORESESSION = 0;      // 


    public function index(Request $request)
    {
        if (! Gate::allows('myexemptionform_access')) {
            return abort(401);
        }

       
        $session_array = \App\Session::whereshowInDatatable('Yes')->latest()->pluck('name');

        $forms = Exemptionform::with(['created_by','owned_by','exemptions'])
                     ->whereIn('session',$session_array)
                     ->CreatedOrOwnedOrApprovedByLoggedInUser();
        
        // FILTERS
       
        $str_session = null;            
      
        $str_namefilter = null;
        $str_idfilter = null;
        $str_status = null;      
      
        $session = $request->query('session');
        $status =  $request->query('status');
        $namefilter =  $request->query('namefilter');
       
        $idfilter   =  $request->query('idfilter');
        

        if ($request->filled('session')){
             
        }
        else{
            $session = $session_array[0];
        }

        $forms = $forms->where('session',$session);

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
           $forms = $forms->where('id',$idfilter);
                   
            $str_idfilter = '&idfilter='.$idfilter;
            
        }


        if ($request->filled('namefilter')){
           
         
            $forms = $forms->wherehas( 'exemptions', function($q) use ($namefilter){
               $q->where('pen','like', '%' . $namefilter.'%' );
            }); 
                
            $str_namefilter = '&namefilter='. $namefilter;
        }
         
        $to_approve = 0; 
        //sections and admins have nothing to approve
        if ( auth()->user()->isDataEntryLevel() || auth()->user()->isAdminorAudit() || auth()->user()->isServices()) {
           // It starts with 'http'
            $to_approve = -1;
        }

        $pending_approval = 0;
        if ( auth()->user()->isFinalLevel() ) {
           // It starts with 'http'
            $pending_approval = -1;
        }
        

        $sort =  $request->filled('sort') ? $request->query('sort') : 'id'; // if user type in the url a column that doesnt exist app will default to id
        $order = $request->query('order') === 'asc' ? 'asc' : 'desc'; // default desc
                
        $forms = $forms->orderBy($sort, $order)->paginate(15)
                                               ->appends($request->except('page'));
         
        //this inverts sorting order for next click                                       
        $querystr = '&order='.($request->query('order') == 'asc' || null ? 'desc' : 'asc').$str_namefilter.$str_idfilter;
             
        return view('admin.myexemptionforms.index',compact('forms','querystr', 'session_array','session', 'to_approve',  'pending_approval' ));
    }


    public function preparevariablesandGotoView( $id=null )
    {
        
        $q = \App\Session::with('calender')->whereExemptionEntry('Yes')->latest();

        $session_array = $q->get();

        $sessions = $session_array->pluck('name');

        $latest_session = $sessions->first();
         
        $calenderdaysmap = [];
        $calenderdays2 = array();
     
        foreach ($session_array as $session) {
    
            $daysall = $session->calender()->orderby('date','asc');
                       
            
            $days = $daysall->where( 'day_type','Sitting day')->get(['date','day_type']);
            
            
            if($days->count()){
                $mindate = $session->calender()->where( 'day_type','Sitting day')->min('date');
                $mindatecarbon = Carbon::createFromFormat( 'Y-m-d' ,$mindate);
                
                //$newYear =  Carbon::createFromDate(2018, 1, 20);
                $interval = Carbon::now()->diff($mindatecarbon); //diff always returns a positive num

                               
                if( (int)($interval->format("%r%a")) <= MyExemptionFormsController::SUBMISSION_DAYS_BEFORESESSION ) //postive to corect sign
                {
                    
                    $sessions->forget($sessions->search($session->name));
                    continue;
                }
            }

            foreach ($days as $day) {
             
                $calenderdaysmap[$day['date']] = $day['day_type'];
                $calenderdays2[$session->name][] = $day['date'];    
            }
        }
      
        
       
       
        $designations = \App\Designation::orderby('designation','asc')
                    ->get(['designation'])->pluck('designation');

        $data["calenderdaysmap"] = json_encode($calenderdaysmap);
        $data["designations"] = json_encode($designations);
        $data["calenderdays2"] = json_encode($calenderdays2);
       
        
       
        JavaScript::put([
            'latest_session' => $latest_session,
                      
            
            
        ]);
    
        
        if($id)
        {
            $form = Exemptionform::with(['created_by','exemptions'])->findOrFail($id);

            $form->exemptions->transform(function ($item) {
                    if($item['name'] != null){
                        $item['pen'] = $item['pen'] . '-' . $item['name'];
                    }
                    return $item;
                                   
            });
                                
            return view('admin.myexemptionforms.edit', compact('form', 'data','sessions' ));
        }
        else
        {
            return view('admin.myexemptionforms.create', compact('data','sessions') );
        }
        

                
    }
    

    /**
     * Show the form for editing Form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /* if (! Gate::allows('form_edit')) {
            return abort(401);
        }    */     
        
        $form = Exemptionform::findOrFail($id);

        return $this->preparevariablesandGotoView($id ) ;
        
    }


    /**
     * Display Form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       /*  if (! Gate::allows('form_view')) {
            return abort(401);
        } */
        
        $form = Exemptionform::with(['created_by','owned_by', 'exemptions'])->findOrFail($id);

        $overtimes =  $form->exemptions;

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

            $forwardarray = \App\User::whereIn( 'username',$routes )->orderby('name','asc')->get(['username','name','displayname']);
            

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
            $cansubmittoaccounts= \Auth::user()->routing->cansubmit_to_accounts('Sittings');
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


        $initival = $canforward ?  \Auth::user()->routing->last_forwarded_to  : '';

        //disable if date over
        $mindate = $session->calender()->where( 'day_type','Sitting day')->min('date');
        $mindatecarbon = Carbon::createFromFormat( 'Y-m-d' ,$mindate);
                
        $interval = Carbon::now()->diff($mindatecarbon); //diff always returns a positive num
        $dataentry_allowed = true;
        if($session->exemption_entry != 'No') {
            if( (int)$interval->format("%r%a") <= MyExemptionFormsController::SUBMISSION_DAYS_BEFORESESSION) //postive to corect sign
            {
                $dataentry_allowed = false;
            }
        } else {
            $dataentry_allowed = false;
        }
        
        JavaScript::put([
            'forwardarray' => $forwardarray,
            'formid' => $form->id,
            'initalvalue' => $initival,
            'remarks' => $form->remarks,
            'malkla' => $malkla,
            'sessionnumber' => $sessionnumber,
            'klasession_for_JS' => $klasession_for_JS,
            'dataentry_allowed' => $dataentry_allowed,
            
        ]);


        return view('admin.myexemptionforms.show', compact('form', 
                    'overtimes','submmittedby',  'createdby', 'canforward' , 'cansubmittoaccounts', 'romankla', 'sessionnumber_th', 'sessionnumber','malkla'));
    }


    /**
     * Remove Form from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       /*  if (! Gate::allows('form_delete')) {
            return abort(401);
        } */
        $form = Exemptionform::findOrFail($id);
        
        Exemption::where('exemptionform_id', $form->id)->delete();

        $form->delete();

        return redirect()->route('admin.myexemptionforms.index');
    }


    public function create()
    {
       /*  if (! Gate::allows('form_create')) {
            return abort(401);
        } */
          
        return $this->preparevariablesandGotoView(null);
       
    }

    public function createovertimes( Request $request, &$myerrors, $formid=null)
    {
              
        
        $collection = collect($request->exemptions);
        
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
    


        $overtimes =$collection->transform(function($overtime) 
                                           use ($request,$formid, &$myerrors) 
        {
            $pen = $overtime['pen'];
            $tmp = strpos($pen, '-');
             if(false !== $tmp){
                $pen = substr($pen, 0, $tmp);
             }

            $query = \App\Exemption::with('form')
                          ->where('pen', 'like' , $pen . '%')
                          ->whereHas('form', function($q)  use ($request,$formid) { 
                            $q->where('session', $request['session'])
                                  ->where('id', '!=', $formid); //skip this item if on update
                        });

            //we cannot use pluck, pluck seems to return only distinct 'count'
            $res = $query->count();
                                                               
            if($res > 0)
            {
             
               array_push($myerrors, $overtime['pen']. '-' .$overtime['name'] . ' : Already applied for exemption' );
               
            }
            else
           {                
                
                return new Exemption([
                    'pen'           => $overtime['pen'],
                    'name'          => $overtime['name'],
                    'designation'   => $overtime['designation'],
                    'worknature'    => $overtime['worknature'],
                                       
                    ]);
                
            }

        });
    
        return $overtimes;
           
    }
   
    public function store(Request $request)
    {
       /*  if (! Gate::allows('form_create')) {
           // return abort(401);
           return response('Unauthorized.', 401);
        } */
                
        $myerrors = [];

        $overtimes = $this->createovertimes( $request, $myerrors );
               
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

/*
            $maxform_no = \App\Exemptionform::whereSession($request['session'])->max('form_no');
            if($maxform_no < 0){
               $maxform_no = 0; //plan to use form no field to -1 for rejected
            }*/

            $form = Exemptionform::create( [
                'session' => $request['session'],
                'creator' => \Auth::user()->username,
                'owner'=> \Auth::user()->username,
                
                'remarks' => $request['remarks'],
                
            ]);
   

            $form->exemptions()->saveMany($overtimes);

            return $form->id;
        });

        \Session::flash('message-success', 'Success: created form-no: ' . $formid ); 
            
      

        return response()->json([
           'created' => true,
           'id' => $formid
        ]);



    }
    
    public function update(Request $request, $id)
    {
        /* if (! Gate::allows('form_edit')) {
            return abort(401);
        } */

        $form = Exemptionform::findOrFail($id);
    

        if( $form->owner != \Auth::user()->username)
        {
            return abort(401);
        }
       
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
      

       

        $formid = \DB::transaction(function()   use ($form, $request, $overtimes) {

            

            //no need to update creator and owner
            $form->update( [
                       
                'remarks' => $request['remarks'],

            ]);

            //see if user has made any changes

            $overtimes_old = Exemption::where('exemptionform_id', $form->id)->get();
            //same number or added new items
            if( $overtimes_old->count() && $overtimes->count()){

                //update same row indices
                $i=0;
                $same_rows = min($overtimes_old->count(), $overtimes->count());
                for (; $i < $same_rows; $i++) { 
                   Exemption::where('id', $overtimes_old[$i]['id'])
                            ->update($overtimes[$i]->toarray());
               
                }
                
                if($overtimes_old->count() < $overtimes->count()){
                    //update if a new row added
                    $overtimes = $overtimes->slice($i);
                    $form->exemptions()->saveMany($overtimes);
                } else if ( $overtimes_old->count() > $overtimes->count()) {
                    //remove rows removed
                    $idsremoved = $overtimes_old->slice($i)->pluck('id')->toarray();
                    Exemption::wherein('id', $idsremoved)
                            ->where('exemptionform_id', $form->id)->delete();
                }

                return $form->id;
            }
        
            Exemption::where('exemptionform_id', $form->id)->delete();

            $form->exemptions()->saveMany($overtimes);

            return $form->id;
        });
        
        \Session::flash('message-success', 'Success: updated form-no: ' . $formid ); 

        return response()->json([
            'created' => true,
            'id' => $formid
         ]);
    

        //return redirect()->route('admin.my_forms.index');
    }

    public function forward(Request $request, $id)
    {

        $form = Exemptionform::findOrFail($id);
    

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
        $form = Exemptionform::findOrFail($id);
    

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

    public function download_emp()
    {
        
        $desig_sort_order = \App\Preset::
                  
                where('name','default_designation_sortorder')
                ->first()->pens;

        $desig_sort_order =trim($desig_sort_order,",");

        

        $session = $request->query('session');
            
        //save calender
        $data = \App\Exemption::with('form')
                     ->wherehas( 'form', function($q) use ($session){
                           $q->where('session',$session)
                            ->where('form_no', '>=', 0)
                             ->where('owner', 'admin'); //submitted to us;
                            
                     })->orderbyraw("field (designation, " . $desig_sort_order . ")" )->get();

        $sortorder = array();
       
       

        $filename =  $session . '-exemption'.  '.csv';
        
        $csvExporterCalender = new \Laracsv\Export();


        $csvExporterCalender->beforeEach(function ($exemption) {
           
            $exemption->pen = substr($exemption->pen, strpos($exemption->pen, '-')+1);

            
        });

        $csvExporterCalender->build($data,['pen'=>'name', 'designation','worknature' => 'reason'
                            ], false);

        $csvExporterCalender->download($filename);


    }


}
