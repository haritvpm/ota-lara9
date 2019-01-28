<?php
namespace App\Http\Controllers\Admin;

use App\Form;
use App\Overtime;
use App\Calender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Input;
//use Laracasts\Utilities\JavaScript\JavaScriptFacade;
use JavaScript;
use Carbon\Carbon;
use Auth;

class PA2MLAFormsController extends Controller
{
    public function index()
    {
        if (! Gate::allows('pa2mlaform_access')) {
            return abort(401);
        }

        //user can login and see forms even after data entry is disabled
        //$session_array = \App\Session::whereDataentryAllowed('Yes')->pluck('name');
        $session_array = \App\Session::whereshowInDatatable('Yes')->latest()->pluck('name');

        $forms = Form::with(['created_by','owned_by','overtimes'])
                     ->withCount('overtimes')   
                     ->whereIn('session',$session_array)
                     ->whereCreator(\Auth::user()->username);
        
        // FILTERS
       
        $str_session = null;            
        $str_datefilter = null;
        $str_namefilter = null;
        $str_idfilter = null;
              
      
        $session = Input::get('session');
        $datefilter =  Input::get('datefilter');
        $namefilter =  Input::get('namefilter');
       
        $idfilter   =  Input::get('idfilter');
        

        if (Input::filled('session')){
            $forms->where('session',$session);
            $str_session = '&session='.$session;
        }
        else{
           // $session = $session_array[0];
        }
                



        if (Input::filled('idfilter'))
        {
           $forms = $forms->where('id',$idfilter);
                   
            $str_idfilter = '&idfilter='.$idfilter;
            
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
         

        $sort =  Input::filled('sort') ? Input::get('sort') : 'id'; // if user type in the url a column that doesnt exist app will default to id
        $order = Input::get('order') === 'asc' ? 'asc' : 'desc'; // default desc
                
        $forms = $forms->orderBy($sort, $order)->paginate(15)
                                               ->appends(Input::except('page'));
         
        //this inverts sorting order for next click                                       
        $querystr = '&order='.(Input::get('order') == 'asc' || null ? 'desc' : 'asc').$str_datefilter.$str_namefilter.$str_idfilter;
             
        return view('admin.pa2mlaforms.index',compact('forms','querystr', 'session_array','session' ));
    }


    public function preparevariablesandGotoView( $id=null )
    {
        $enum_overtime_slot = Form::$enum_overtime_slot;

        $q = \App\Session::with('calender')->whereDataentryAllowed('Yes')->latest();

        $session_array = $q->get();

        $sessions = $session_array->pluck('name');

        $latest_session = $sessions->first();
         
        $calenderdaysmap = [];
        $calenderdays2 = array();
     
        foreach ($session_array as $session) {
    
            $daysall = $session->calender();
           
            $days = $daysall->orderby('date','asc')->get(['date','day_type']);
                     
            foreach ($days as $day) {
              
                $calenderdaysmap[$day['date']] = $day['day_type'];
                $calenderdays2[$session->name][] = $day['date'];    
            }
        }
      

        $temp = \App\Employee::with('designation')->wherehas( 'designation', function($q) { 
                        $q->where('designation','like',"%Personal Assistant to MLA%")
                        ->orWhere('category','like',"%Admin Data Entry%");

                            })->orderby('name','asc')->get();

        /*$pa2mlas = $temp->map(function ($name, $key) {
                        return $name . '-' . $key ;
                     });*/

        $combined = $temp->mapWithKeys(function ($item) {

            $val = $item->designation->designation;

            if($item->desig_display != ''){
                $val = $item->desig_display;            
              
            }

            if( 'Relieved' == $item->category ){
                $val .= ' (RELIEVED)';

            }

            return [ $item->pen . '-' . $item->name =>  $val ];

            
        });

        $pa2mlas = $combined->keys();
         
       
        $designations = ['Personal Assistant to MLA','Office Attendant'];
        //note the desig 'Office Attendant' is used in pa2mla.js file
        
        $data["calenderdaysmap"] = json_encode($calenderdaysmap);
        $data["designations"] = json_encode($designations);
        $data["calenderdays2"] = json_encode($calenderdays2);
        $data["pa2mlas"] = json_encode($pa2mlas->values());
        $data["pen_names_to_desig"] = json_encode($combined);


        //$presets = \App\Preset::where('user_id',\Auth::user()->id)->pluck('name');
       
        JavaScript::put([
            'latest_session' => $latest_session,
            'old_slotselected' => old('overtime_slot') ? old('overtime_slot') : '',
            'old_calenderdayselected' => old('duty_date') ? old('duty_date') : '',
            //'presets' => $presets,
           // 'pen_names_to_desig' => $combined,
            
        ]);
    
        
        if($id)
        {
            $form = Form::with(['created_by','overtimes'])->findOrFail($id);

            $form->overtimes->transform(function ($item) {
                    if($item['name'] != null){
                        $item['pen'] = $item['pen'] . '-' . $item['name'];
                    }
                    return $item;
                                   
            });
                                
            return view('admin.pa2mlaforms.edit', compact('form', 'data','sessions' ));
        }
        else
        {
            return view('admin.pa2mlaforms.create', compact('data','sessions') );
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
        
        $form = Form::findOrFail($id);

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
  

        
        JavaScript::put([
           
            'formid' => $form->id,
           
            'remarks' => $form->remarks,
            
        ]);


        return view('admin.pa2mlaforms.show', compact('form', 
                    'overtimes'));
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
        $form = Form::findOrFail($id);
        
        Overtime::where('form_id', $form->id)->delete();

        $form->delete();

        return redirect()->route('admin.pa2mlaforms.index');
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
       // $date = Carbon::createFromFormat(config('app.date_format'), $request['duty_date'])->format('Y-m-d');
        

        //this is total days
        $maxdays = \App\Calender::with('session')
                                ->whereHas('session', function($query)  use ($request) { 
                                    $query->where('name', $request['session']);
                                  })                              
                                ->count();
        $maxsittingactual = \App\Calender::with('session')
                                ->whereHas('session', function($query)  use ($request) { 
                                    $query->where('name', $request['session']);
                                  })                              
                                ->where('day_type','Sitting day')
                                ->count();

        
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
        

        //$rates = null;
        $rates = \App\Designation::wherein ('designation', ['Personal Assistant to MLA','Office Attendant'] )->pluck('rate','designation');
/*
        $pens = $collection->pluck('pen');
        

        $query = \App\Overtime::with('form')
                              ->wherein('pen', $pens)
                              ->whereHas('form', function($q)  use ($request,$formid) { 
                                $q->where('overtime_slot', 'Sittings')
                                      ->where('session', $request['session'])
                                      ->where('id', '!=', $formid); //skip this item if on update
                            });


        //we cannot use pluck, pluck seems to return only distinct 'count'
        $res = $query->get(['count', 'pen']);*/

        //\Log::info(print_r($res, true));


        $overtimes =$collection->transform(function($overtime) 
                                           use (/*$res,*/$request,$formid, &$myerrors,$maxdays,$rates,$maxsittingactual) 
        {
            $pen = $overtime['pen'];
            $tmp = strpos($pen, '-');
            if(false !== $tmp){
                $pen = substr($pen, 0, $tmp);
            }


            $query = \App\Overtime::with('form')
                          ->where('pen', $pen)
                          ->whereHas('form', function($q)  use ($request,$formid) { 
                            $q->where('overtime_slot', 'Sittings')
                                  ->where('session', $request['session'])
                                  ->where('id', '!=', $formid) //skip this item if on update
                                  ->whereCreator(\Auth::user()->username); //allow assistant and PA2mla in same session. arunlal
                        });

            if(($emp = \App\Employee::where('pen', $pen)->first())){

                if( stristr($emp['desig_display'], 'Attendant') || stristr($emp['desig_display'], 'Chairman') )
                {
                    if( $overtime['count'] > $maxsittingactual){
                         array_push($myerrors, $overtime['pen'] . '-' .$overtime['name'] . ' : PA to Chairman/OA can have max ' . $maxsittingactual . ' OTs');
                   
                    }
                }
            }


            //we cannot use pluck, pluck seems to return only distinct 'count'
            $res = $query->get(['count', 'pen']);
                               
            //note, res is a collection. not a query 
            //$totalsittingexisting = $res->where('pen', $overtime['pen'])->sum('count');
            $totalsittingexisting = $res->sum('count');

            // $days_already_entered = $q->all()->pluck(['from','to']);

            $totalwouldbe =  $overtime['count'];

            if( $totalsittingexisting > 0  )
            {
              
                array_push($myerrors, $overtime['pen'] . '-' .$overtime['name'] . ' : Already submitted');
            }
            else                     
            if($totalwouldbe > $maxdays)
            {
             
               array_push($myerrors, $overtime['pen'] . '-' .$overtime['name'] . ' : Exceeds the maximum of ' . $maxdays );
               
            }
            else
           {                
                
                return new Overtime([
                    'pen'           => $overtime['pen'],
                    'name'          => $overtime['name'],
                    'designation'   => $overtime['designation'],
                    'from'          => '', //canot be empty
                    'to'            => '',
                    'worknature'    => $overtime['worknature'],
                    'count'         => $overtime['count'],
                    'rate'          => $rates[$overtime['designation']],
                    
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


            $maxform_no = \App\Form::whereSession($request['session'])->max('form_no');
            if($maxform_no < 0){
               $maxform_no = 0; //plan to use form no field to -1 for rejected
            }

            $form = Form::create( [
                'session' => $request['session'],
                'creator' => \Auth::user()->username,
                'owner'=> \Auth::user()->username,
                'date_from' => $request['date_from'],
                'date_to' => $request['date_to'],
                'overtime_slot' => 'Sittings',
                'remarks' => $request['remarks'],
                'submitted_by' => \Auth::user()->username,
                'submitted_on' => Carbon::now(),
                'form_no' => $maxform_no+1,
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
    
    public function update(Request $request, $id)
    {
        /* if (! Gate::allows('form_edit')) {
            return abort(401);
        } */

        $form = Form::findOrFail($id);
    

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

}
