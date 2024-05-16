<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Category;
use Carbon\Carbon;


use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreEmployeesRequest;
use App\Http\Requests\Admin\UpdateEmployeesRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use \SpreadsheetReader;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class EmployeesController extends Controller
{
    /**
     * Display a listing of Employee.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        if (! Gate::allows('employee_access')) {
            return abort(401);
        }

      

        $session_latest =  \App\Session::latest()->first()->name;

        if (request()->ajax()) {
            
           // $query = Employee::query();
           //imp: 'select' is needed else id of empl row will change to relation id, after sorting a relationship column. 
           //this will affect view/edit/delete
            $query = Employee::with(['designation', 'categories'])->select('employees.*'); 

            $ShowRelievedEmployees = \App\Setting::where('name', 'ShowRelievedEmployees')->value('value');

            if($ShowRelievedEmployees){
               $query->where('category', '<>', 'Relieved');
            }


            if(\Auth::user()->isAdminorITAdmin())
            {

            }
            else
            {
                //$query =  $query->where('added_by',\Auth::user()->username) ;
                $query =  $query->where('pen', 'like', 'TMP%') ; //users with no PEN
            }
                            
                  
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('designation_designation', function ($row) {
                return $row->designation ? $row->designation->designation : '';
            });
          //  $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            //$table->addColumn('search', '&nbsp;');

            $table->editColumn('actions', function ($row)  {
                $gateKey  = 'employee_';
                $routeKey = 'admin.employees';

                return view( 'actionsTemplate', compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('name', function($row){
                return $row->srismt.'. '.$row->name;
            });

            // $table->editColumn('search', function($row) use ($session_latest){


            //     $url = route('admin.searches.index', ['namefilter' => $row->pen, 'session' => $session_latest, 
            //       'created_by' => '', 'status' => '']);

            //     return '<a href=' . $url . '><i class="fas fa-fw  fa-search"></i></a>' ;

               
            // });

           // $table->rawColumns(['actions']);
            // $table->rawColumns(['actions', 'search']);
            $table->rawColumns(['actions', 'designation', 'punching']);

            $table->editColumn('categories.category', function ($row) {
                return $row->categories ? $row->categories->category : '';
            });
            $table->editColumn('punching', function ($row) {
                //return $row->punching ? 'PUNCHING_YES' : 'PUNCHING_NO';
               return '<input type="checkbox" disabled ' . ($row->punching ? 'checked' : null) . '>';
            });

            return $table->make(true);
        }

        $designations = \App\Designation::orderby('designation','asc')
                    ->get(['designation'])->pluck('designation');

        $data["designations"] = json_encode($designations);

        $empwithnocategory = '';
        $empswithduplicatedesigdisplay = [];
        if(\Auth::user()->isAdminorITAdmin())
        {
           $empwithnocategory = Employee::select('pen')->where('categories_id',null)
           //->where('category', '<>', 'Relieved')
           ->pluck('pen')->implode(',');
        

            //show all employees with redundant desig display
            $empswithduplicatedesigdisplay =  Employee::with('designation')->get()
                                        ->filter(function ($e) {
                                            return 0 == strcasecmp($e->designation->designation, $e->desig_display);
                                        })->pluck('pen')->implode(',');;
        }

        
 //    


       
        return view('admin.employees.index', compact('data','empwithnocategory','empswithduplicatedesigdisplay'));
    }

    /*
    public function index()
    {
        if (! Gate::allows('employee_access')) {
            return abort(401);
        }

        $str_namefilter = null;
        $str_addedby = null;
        $str_type = null;
       
        $namefilter =  $request->query('namefilter');
        $added_by =  $request->query('added_by');
        $type =  $request->query('type');



        $query = Employee::query();
        $query->with("designation"); 
        $query->with("categories");

        if(\Auth::user()->isAdmin())
        {

        }
        else
        {
            $query =  $query->where('added_by',\Auth::user()->username) ;
        }


        if ($request->filled('added_by')){ 
                           
            if($added_by != 'all'){
                $query = $query->where( 'added_by', $added_by);
                                         
                $str_addedby = '&added_by='.$added_by;
            }

            
        }
        
        if ($request->filled('type')){
           
            if($type != 'all'){
                $query = $query->where('category',$type);
                                                     
                $str_type = '&type='. $type;
            }
        }

        if ($request->filled('namefilter')){
           
         
            $query = $query->where(function ($q) use($namefilter) {
                $q->where('pen','like', '%' . $namefilter.'%' )
                 ->orwhere('name','like', '%' . $namefilter.'%' );
                });
                
                           
            $str_namefilter = '&namefilter='. $namefilter;
        }



        
        $employees = $query->orderby('id','desc')->paginate(100)
                                               ->appends($request->except('page'));

        return view('admin.employees.index', compact('employees'));
    }
    */
    //before punching
    public function ajaxfindold($search)
    {
       
      $temp =  Employee::with('designation')
                ->wherehas( 'designation', function($q){
                    $q->wherenotin('designation', ['Personal Assistant to MLA']);
                })
             ->where('category','<>','Staff - Admin Data Entry')
             ->where('category','<>','Relieved')  
             ->where('pen','not like','TMP%') 
             ->Where(function ($query) use ($search) {
                $query->where('pen', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%');
            })->orderby('name','asc')->get()->take(30);
            //})->orderby('name','asc')->pluck('name','pen')->take(100);
                        


        $combined = $temp->mapWithKeys(function ($item) {
            return [ $item->pen . '-' . $item->name => $item->designation->designation];
        });
               

        return [
            'pen_names' => $combined->keys(),
            'pen_names_to_desig' => $combined
        ];
        
    }


    public function ajaxfind($search)
    {
       
      $temp =  Employee::with(['designation', 'categories'])
                ->wherehas( 'designation', function($q){
                    $q->wherenotin('designation', ['Personal Assistant to MLA']);
                })
             ->where('category','<>','Staff - Admin Data Entry')
             ->where('category','<>','Relieved')  
             ->where('pen','not like','TMP%') 
             ->Where(function ($query) use ($search) {
                $query->where('pen', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%');
            })->orderby('name','asc')->get()->take(30);
            //})->orderby('name','asc')->pluck('name','pen')->take(100);
                        

        /* 
        category = mla hostel non-gazetted (office hours = 7), in that desig part time has oh (3)
        //so office hours of desig takes precedence

        category = personal staff (punching = false), in that desig assistant  has punching (true)
        //so punching has to be enabled in both categiry and designation
*/

        $combined = $temp->mapWithKeys(function ($item) {
        //    return [ $item->pen . '-' . $item->name => $item->designation->designation];
            return [ $item->pen . '-' . $item->name => 
                [
                    'desig' =>  $item->designation->designation,
                    'desig_normal_office_hours' =>  $item->designation->normal_office_hours,
                  //  'desig_punching' =>  $item->designation->punching,
                  //  'category_punching' => $item->categories->punching,
                    'punching' =>   ($item?->categories?->punching ?? true) && ($item->designation->punching ?? true) && $item->punching &&  \Auth::user()->hasPunching(),
                    'category' =>  $item?->categories?->category,
                    'employee_id' =>  $item->id,
                    'aadhaarid' =>  $item->aadhaarid,

                ]
            ];
        });

     //   $penname_to_category = $temp->mapWithKeys(function ($item) {
      //      return [ $item->pen . '-' . $item->name => $item->designation->designation];
      //  });
               

        return [
            'pen_names' => $combined->keys(),
            'pen_names_to_desig' => $combined
        ];
        
    }

    public function ajaxfindexactpen($search)
    {
       
      $temp =  Employee::with('designation')
                ->wherehas( 'designation', function($q){
                    $q->wherenotin('designation', ['Personal Assistant to MLA']);
                })
             ->where('category','<>','Staff - Admin Data Entry')
             ->where('category','<>','Relieved')   
             ->where('pen','not like','TMP%') 
             ->Where(function ($query) use ($search) {
                $query->where('pen',  $search );
                
            })->orderby('name','asc')->get()->take(1);
                     
        $combined = $temp->mapWithKeys(function ($item) {
            return [ $item->pen . '-' . $item->name => $item->designation->designation];
        });

        //find similar desig


        
        $designations = \App\Designation::where( 'rate', $temp->first()->designation->rate )->orderby('designation','asc')->pluck('designation');
        
        return [
            'pen_names' => $combined->keys(),
            'pen_names_to_desig' => $combined,
            'designations' => $designations,
        ];
        
    }

    public function ajaxfindexactpenforattendace($search)
    {/*
       
      $temp =  Employee::with('designation')
                ->wherehas( 'designation', function($q){
                    $q->wherenotin('designation', ['Personal Assistant to MLA']);
                })
             ->where('category','<>','Staff - Admin Data Entry') 
             ->Where(function ($query) use ($search) {
                $query->where('pen',  $search )
                    ->orwhere('id',  $search );
                
            })->orderby('name','asc')->get()->take(10);
                     
        $combined = $temp->mapWithKeys(function ($item) {
            return [ $item->pen . '-' . $item->name => $item->designation->designation];
        });
        
        return [
            'pen_names' => $combined->keys(),
            'pen_names_to_desig' => $combined,

        ];
        */
    }

    /**
     * Show the form for creating new Employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($temppen = false)
    {
        if (! Gate::allows('employee_create')) {
            return abort(401);
        }
        
        $designations = \App\Designation::orderby('designation','asc')->get()->pluck('designation', 'id')->prepend(trans('quickadmin.qa_please_select'), '');


        $enum_category =  \Auth::user()->isAdmin() ? Employee::$enum_category_admin 
                                                   :   Employee::$enum_category;

        $categories = \App\Category::get()->pluck('category', 'id')->prepend(trans('quickadmin.qa_please_select'), ''); 

        $enum_srismt = Employee::$enum_srismt;
        
        
        return view('admin.employees.create', compact( 'enum_srismt', 'enum_category', 'designations', 'categories', 'temppen'));
    }
    public function create_temppen()
    {
      return $this->create(true);
      //return  redirect()->action('Admin\EmployeesController@create', true);
    }

    /**
     * Store a newly created Employee in storage.
     *
     * @param  \App\Http\Requests\StoreEmployeesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeesRequest $request)
    {
        if (! Gate::allows('employee_create')) {
            return abort(401);
        }

        $employee = new Employee ($request->all());

        //admins does not save added by
        {
            $employee['added_by'] = \Auth::user()->username;
        }
        
        $employee->save();
        $temp = false;
        if( strncasecmp($employee->pen,"TMPPEN", 6 ) == 0){
            $employee->update([
              'pen' => "TMP". $employee->id,
            ]);       
            $temp = true;   
        }
        if(!$temp){
          return redirect()->route('admin.employees.index');
        } else {
          \Session::flash('message-success', 'Added employee with temp ID: ' . $employee->id );
          return redirect()->route('admin.employees.index');
        }
    }


    /**
     * Show the form for editing Employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('employee_edit')) {
            return abort(401);
        }
        
        $designations = \App\Designation::orderby('designation','asc')->get()->pluck('designation', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $enum_category = \Auth::user()->isAdmin() ? Employee::$enum_category_admin 
                                                  : Employee::$enum_category;
        $categories = \App\Category::get()->pluck('category', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $employee = Employee::findOrFail($id);
 
        $enum_srismt = Employee::$enum_srismt;

        if(!\Auth::user()->isAdmin()){
           // if( $employee->added_by != \Auth::user()->username) //prevent employee name edit during a session at least until we make checks independent of names
            {
             //   return abort(401);
            }
        }

        return view('admin.employees.edit', compact( 'enum_srismt', 'employee', 'enum_category', 'designations', 'categories'));
    }

    /**
     * Update Employee in storage.
     *
     * @param  \App\Http\Requests\UpdateEmployeesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeesRequest $request, $id)
    {
        if (! Gate::allows('employee_edit')) {
            return abort(401);
        }
        $employee = Employee::findOrFail($id);
        $employee->update($request->all());



        return redirect()->route('admin.employees.index');
    }

    public function updatedesig(Request $request)
    {
      if (! Gate::allows('employee_edit')) {
            return abort(401);
        }

      $employee = Employee::with('designation')->wherepen($request['emppen'])->first();

      $newdesig = \App\Designation::where( 'designation', $request['empdesig'] )->first();

      if($employee != null && $newdesig != null){
        //check if current designation rate and new rate are same
        
        if($newdesig->rate == $employee->designation->rate || 
        \Auth::user()->isAdmin()){
          //ok, same
          if($employee->designation->id != $newdesig->id){
            $employee->update(
              [ 
                'designation_id' => $newdesig->id,
              ]
            );

           \Session::flash('message-info', $employee->pen . '-' . $employee->name .  ': Changed designation to ' . $request['empdesig']);
          }
          else{
              \Session::flash('message-info', 'Same. Nothing to change');
          }

          return redirect()->route('admin.employees.index');

        } else {
           return redirect()->route('admin.employees.index')->withErrors(['OTA rates are different. Please contact Accounts D to make this change']);
        }

      }

      return redirect()->route('admin.employees.index')->withErrors(['Employee not found'])->withInput();

    }
    /**
     * Display Employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('employee_view')) {
            return abort(401);
        }
        $employee = Employee::findOrFail($id);

        return view('admin.employees.show', compact('employee'));
    }


    /**
     * Remove Employee from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('employee_delete')) {
            return abort(401);
        }

        $employee = Employee::findOrFail($id);

        if(!\Auth::user()->isAdmin()){
          return abort(401);  //no deleting employee
        }
        if( $employee->added_by != \Auth::user()->username)
        {
            return abort(401);
        }

        $employee->delete();

        return redirect()->route('admin.employees.index');
    }

    /**
     * Delete all selected Employee at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('employee_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Employee::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    public function download_emp()
    {        

        $employees = \App\Employee::with(['designation', 'categories'])->orderby('id','desc')->get();

        $filename =  'sectt_employees-'.  date('Y-m-d') . '.csv';
        
        $csvExporter = new \Laracsv\Export();

        //$csvExporter->build($employees, [ "id", "pen","srismt","name", "designation.designation", "designation_id","desig_display",'categories_id', 'categories.category', "added_by","category" ]);
        
        $csvExporter->build($employees, [ "pen","srismt","name", "designation.designation", "desig_display",  'categories.category' ]);


        $csvExporter->download($filename);

    }


    public function clearold()
    {        
        if(!\Auth::user()->isAdmin()){
            return abort(401);
        }

        $date_ago = Carbon::today()->subYears(1);
        $merged = \App\Overtime::distinct()->get(['pen'])->pluck('pen');
        

        $merged->transform(function ($pen) {
                $tmp = strpos($pen, '-');
                if(false !== $tmp){
                                       
                    $pen =   substr($pen, 0, $tmp );
                }

                return $pen;
                });

       // $dump = $request->query('delbtn') != 'del';
        
        //if($dump)
        {
          $emp = \App\Employee::wherenotin('pen', $merged)
                //->whereDate('created_at', '<', $date_ago->toDateString())
                //->whereDate('updated_at', '<', $date_ago->toDateString())
                ->orderby('id','desc')->pluck('name','pen');

          dd($emp);
            
        } 


    }

    public function sparksync(Request $request)
    {
       if(!\Auth::user()->isAdmin()){
            return abort(401);
       }

       $inputview = 0;
       $sectt = $request->query('sectt');
      // $hostel = $request->query('hostel');
       $myerrors = array();
       $added = array();
       $modified = array();
       $notinpdf = array();
       $ignoreditems = array();

       if (!$request->filled('sectt') /*|| !$request->filled('hostel')*/){ 

          return view('admin.employees.sparksync', compact('inputview', 'myerrors', 'added', 'modified', 'notinpdf', 'ignoreditems'));

        }
        
        $inputview = 1;

        $pensinpdf = array();
                
        $arrsectt = explode( PHP_EOL, $request->sectt);
      //  $arrhoz = explode( PHP_EOL, $request->hostel);

        $designations = \App\Designation::all()->pluck('id','designation');

        //ignore mlas and personal staff
        //dont mess with part time as it might change them to full time on modifi
        $ignoreddesigs = array('Personal', 'Speaker', 'Time');
        $categories = \App\Category::all()->pluck('id','category');
         

        foreach ($arrsectt as &$value){
            
            $pdfrow = explode( ',', $value);

            $pen = trim($pdfrow[1]);
            $name = trim($pdfrow[2]);
            $desig = trim($pdfrow[3]);
            $desig_display = trim($pdfrow[4]);
            $category = trim($pdfrow[5]);


          //  $cat_id =  $category != '' ? $categories[$category] : -1;


            $penname = $pen . " - " . $name;

            array_push($pensinpdf,$pen);

            //see if this designation is valid
            if(!$designations->has($desig)){
              array_push($myerrors, array('Desig not found' , '' ,$desig, $pen, $name ));
           
              continue;
            }

            
            $emp =  Employee::with('designation')
                    ->where('pen',  $pen )->first();

            if($emp){

/*
                $olddesigdisplay = $emp->desig_display;
                $oldcat = $emp->categories_id;

                if ( 0 != strcasecmp($olddesigdisplay,$desig_display) || 
                     ($oldcat != $cat_id && $cat_id != -1) ){
                       
                    $emp->update([
                      'desig_display' => $desig_display,
                      'categories_id' => $cat_id,

                      ]);

                    if($oldcat != $cat_id && $cat_id != -1){
                      array_push($modified, array('Modified Category' , $penname , $oldcat . " -> " . $cat_id . '(' . $category . ')'));
                    } 

                    if ( 0 != strcasecmp($olddesigdisplay,$desig_display)){
                      
                      array_push($modified, array('Modified DesigDisp' , $penname , $olddesigdisplay . " -> " . $desig_display));
                    }

                }
*/

                              
                $olddesig = $emp->designation->designation;
                if ( 0 == strcasecmp($olddesig,$desig)){
                  continue;
                }


                $ignored = false;
                foreach ($ignoreddesigs as $d) {
                  if(false !== stripos($olddesig, $d) || 
                     false !== stripos($desig, $d)) {
                    $ignored = true;
                    }            
                }

                if($ignored ){
                     array_push($ignoreditems, array('Ignored Change', $penname, $olddesig . " -> " . $desig));
                  continue;
                }

               
                $emp->update([
                  'designation_id' => $designations[$desig],
                  ]);

                array_push($modified, array('Modified Desig' , $penname , $olddesig . " -> " . $desig));
                

            } else {

               //do not allow new PA to MLA. we have to enter it manually. admin entry

                $ignored = false;
                foreach ($ignoreddesigs as $d) {
                  if(false !== stripos($desig, $d)) {
                    $ignored = true;
                    }            
                }

                if($ignored ){
                     array_push($ignoreditems, array('Ignored New Emp' , $penname, $desig));
                  continue;
                }

              $employee = new Employee ([
                  'pen' => $pen,
                  'name' => $name,
                  'srismt' => 'Sri',
                  'designation_id' => $designations[$desig],
                  'added_by' => 'admin',
                ]);
             
            //  $employee->save(); no issues, but add manually

            //   array_push($added, array('New Emp Added' , $penname , $desig));
            }
        }

        //For the pens NOT in spark pdf, set to relieved.

        if(count($pensinpdf)){

          $emprelievedpens =  Employee::wherenotin('pen',  $pensinpdf )
               ->where('category', '<>', 'Relieved')
               ->where ('pen', 'not like', '%E%') //ignore employment exchange like IT CHM
               ->pluck('pen');
          
          if($emprelievedpens->count()){

            Employee::whereIn('pen', $emprelievedpens)->update([
              'category' => 'Relieved',
            ]);


            $emprelieved = Employee::with('designation')->whereIn('pen', $emprelievedpens)->get();

            $emprelieved->map(function ($item, $key) use (&$notinpdf) {
                array_push($notinpdf, array('Set Relieved' , $item['pen'] . '-' . $item['name'] , $item->designation->designation));
 
            });

          }

          //also make sure that the emp who are in pdf are not set relieved.

          $empnotrelieved = Employee::wherein('pen',  $pensinpdf )
               ->where('category', '=', 'Relieved')
               ->get();

          $empnotrelieved->map(function ($item, $key) use (&$myerrors)  {
                array_push($myerrors, array('Change to Not Relieved' , $item['pen'] . '-' . $item['name'] , $item->designation->designation));
 
            });

        }


        //\Session::flash('message-success', 'ok');



        return view('admin.employees.sparksync', compact('inputview', 'myerrors', 'added', 'modified', 'notinpdf', 'ignoreditems'));        

       
        
    }


    public function findinvalidpen()
    {        
        if(!\Auth::user()->isAdmin()){
            return abort(401);
        }

        
        $date_ago = Carbon::today()->subYears(1);
         
        
        
        $emp_withinvalidpens = \App\Employee::/*whereDate('created_at', '>', $date_ago->toDateString())
                ->whereDate('updated_at', '>', $date_ago->toDateString())
                ->*/where(function($query) {
                     $query->whereRaw('LENGTH(pen) < 6')
                            ->orwhereRaw('LENGTH(pen) > 7')
                            ->orWhere('pen','LIKE','% %')
                            ->orWhere('pen','LIKE','%:%');
                })
                ->orderby('id','desc')->pluck('name','pen');

      

        $session_latest =  \App\Session::latest()->first()->name;

             
        
        $collection = new Collection();

        //find OT forms with the above invalid pen
        foreach($emp_withinvalidpens as $pen => $name)  {

            $ots = \App\Overtime::with('form')->whereHas('form', function($query) use  ($session_latest) {
                $query->where('session', $session_latest);
              })->where('pen','LIKE', $pen)
                ->get()->pluck('form.form_no');

            $collection->push( array('pen' => $pen, 'name' => $name, 'form_nos' => $ots) );
        }

        //Also find any overtimes with invalid pen already submitted, but before editing the PEN by admin
        $invalidpens = $emp_withinvalidpens->keys()->all();
       
        $ots_withinvalidpen = \App\Overtime::with('form')->whereHas('form', function($query) use  ($session_latest) {
            $query->where('session', $session_latest);
          })
          ->wherenotin('pen',$invalidpens) //exclude already found
          ->where(function($query) {
            $query->whereRaw('LENGTH(pen) < 6')
                   ->orwhereRaw('LENGTH(pen) > 7')
                   ->orWhere('pen','LIKE','% %')
                   ->orWhere('pen','LIKE','%:%');
            })
            ->get();

        foreach($ots_withinvalidpen as $ot)  {
              $collection->push( array('pen' => $ot->pen, 'name' => $ot->name, 'form_nos' => $ot->form->form_no) );
        }

//        dd($collection);

        $filename =  $session_latest .' invalid pens-'.  date('Y-m-d') . '.csv';
    
        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($collection, [ "pen", "name","form_nos" ]);

        $csvExporter->download($filename);
            
        


    }

    //we used staff.csv as a local file with ot processor.exe.
    //now moving this online
    public function staffCategorySync(Request $request)
    {
 
       
  
        $mapCategories_to_id = \App\Category::All()->mapWithKeys(function($cat){
            return [$cat->category => $cat->id];
        });
       
        $staffcsv_pen_to_categoryId = [];
        $pen_to_desig_display = [];


        $file = $request->file('file');
        $fileContents = file($file->getPathname());

        foreach ($fileContents as $line) {
            $data = str_getcsv($line);
            $pen = str_replace('"', "", trim($data[0]));
            // $desig = $data[3];
            $desig_display = $data[4];
            if(strlen($pen) >=5){
                $category = str_replace('"', "", trim($data[5]));
                $staffcsv_pen_to_categoryId[$pen] = $mapCategories_to_id[$category];
                $staffcsvpen_to_desig_display[$pen] = $desig_display;
            }
        }
        //dd($staffcsv_pen_to_categoryId);


/*
            Employee::where('pen')->update([
                'categories_id' => 
                ,
                'desig_display' => $desig_display,
                // Add more fields as needed
            ]);*/

            $emps = Employee::select('id', 'pen')->get();

            foreach ($emps->chunk(1000) as $chunk) {
               $cases = [];
               $ids = [];
               $params_cat = [];
               $params_desig_display = [];
            
               foreach ($chunk as $emp) {
                    if (array_key_exists($emp->pen, $staffcsv_pen_to_categoryId)){
                        $cases[] = "WHEN '{$emp->pen}' then ?";
                        $params_cat[] = $staffcsv_pen_to_categoryId[$emp->pen];
                        $params_desig_display[] = $staffcsvpen_to_desig_display[$emp->pen];
                        $ids[] = $emp->id;
                    }
               }
            
               $ids = implode(',', $ids);
               $cases = implode(' ', $cases);
            
               if (!empty($ids)) {
                   \DB::update("UPDATE employees SET `categories_id` = CASE `pen` {$cases} END WHERE `id` in ({$ids})", $params_cat);
                   
                   \DB::update("UPDATE employees SET `desig_display` = CASE `pen` {$cases} END WHERE `id` in ({$ids})", $params_desig_display);
                   
               }
            }


         //if desig displa is same as designaion, set desig display to empty
         $emps = Employee::with('designation') ->get();
         $empswithduplicatedesigdisplay =   $emps->filter(function ($e) {
                                         return 0==strcasecmp($e->designation->designation, $e->desig_display);
                                     })->pluck('id');
 
         if($empswithduplicatedesigdisplay->count()){
             Employee::wherein('id',$empswithduplicatedesigdisplay )
                  ->update([
                  'desig_display' => '',]);
         }
 
      

        return redirect()->back()->with('success', 'CSV file imported successfully.');
    }

    
    // public function findwithnoaadhaarid()
    // {        
    //     if(!\Auth::user()->isAdmin()){
    //         return abort(401);
    //     }
       
        
    //     $emp_withnoaadhaarid= Employee::wherenull('aadhaarid')
    //             ->where('category', '<>', 'Relieved')
    //             ->orderby('id','desc')->get();

    //     $filename =  'emp_withnoaadhaarids-'.  date('Y-m-d') . '.csv';
    
    //     $csvExporter = new \Laracsv\Export();

    //     $csvExporter->build($emp_withnoaadhaarid, [ "pen", "name" ]);

    //     $csvExporter->download($filename);

    // }

    
    public function parseAadhaarCsvImport(Request $request)
    {
        try {
            $file = $request->file('csv_file');
            $request->validate([
                'csv_file' => 'mimes:csv,txt',
            ]);

            $path      = $file->path();
            $filename = $file->getClientOriginalName();
     
           // $hasHeader = $request->input('header', false) ? true : false;

            $reader  = new SpreadsheetReader($path);
            $headers = $reader->current();
        


            $fields = array('aadhaarid' => array_search("emp_id",($headers)),
                            'pen' =>  array_search("org_emp_code",($headers)), 
                            'name' =>  array_search("emp_name",($headers)),
                            'designation'=> array_search("designation",($headers)),
                            'section'=> array_search("division",($headers)),
                            );
            $update = [];
                      
            foreach ($reader as $key => $row) {
                //  $out->writeln( '------' . $key );
                if (/*$hasHeader &&*/ $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {
                    if (isset($row[$k])) {
                        
                        //if( $header == 'pen' )
                        //    $tmp[$header] = str_replace(' ', '',  $row[$k]);
                       // else
                            $tmp[$header] = $row[$k];
                    }
                }

                

                if (count($tmp) == count($fields) ) {
                  $update[] = $tmp;
                } else {
                    dd($row);
                }
            }

            File::delete($path);

            //find if employee exists
            
            $pens = array_column($update, 'pen');
            $pens = array_unique($pens); 
            
               
            $empls_not_found = [];
            $updated =0;
            foreach ($update as &$update_item) {
                
                $emp =  Employee::where('pen', $update_item['pen'])->first();
                if($emp){

                    if(!$emp->aadhaarid || $emp->aadhaarid == ''){
                        $updated++;
                        $emp->update( ['aadhaarid' => $update_item['aadhaarid'] ] );
                    }
                }
                else {
                    $empls_not_found[] = $update_item;
                }  
            }
                     
       
            $rows  = count($update);
           
            File::delete($path);
            $notfound = count($empls_not_found);
            \Session::flash('message-success', "updated  {$updated} / {$rows}" );
            if($notfound){
                \Session::flash('message-danger', "Manually map PEN to attendanceId for the {$notfound} employees shown in table below" );
            }
            
            return redirect()->route('admin.employees.index')->with( ['empls_not_found' => $empls_not_found] );

        } catch (\Exception $ex) {
            throw $ex;
        }
        
    }

   

}
