<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Employee;
use App\Calender;

use App\Attendance;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAttendancesRequest;
use App\Http\Requests\Admin\UpdateAttendancesRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class AttendancesController extends Controller
{
    /**
     * Display a listing of Attendance.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! Gate::allows('attendance_access')) {
            return abort(401);
        }

        /*
        if (request()->ajax()) {
            $query = Attendance::query();
            $query->with("session");
            $query->with("employee");
            $template = 'actionsTemplate';
            
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey  = 'attendance_';
                $routeKey = 'admin.attendances';

                return view($template, compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('session.name', function ($row) {
                return $row->session ? $row->session->name : '';
            });
            $table->editColumn('employee.name', function ($row) {
                return $row->employee ? $row->employee->name : '';
            });
            $table->editColumn('dates_absent', function ($row) {
                return $row->dates_absent ? $row->dates_absent : '';
            });

            
            return $table->make(true);
        }
        */

        $q = \App\Session::with('calender')->whereDataentryAllowed('Yes')->latest();

        $session_array = $q->get();
            
        $calenderdaysmap = [];
        $calenderdays2 = array();
     
        foreach ($session_array as $session) {
    
            $daysall = $session->calender()->orderby('date','asc');
                       
            $days = $daysall->where( 'day_type','Sitting day')->get(['date','day_type']);
            
            foreach ($days as $day) {
              
                $calenderdaysmap[$day['date']] = $session->name;

                $calenderdays2[$session->name][] = $day['date'];    
            }
        }

        $data["calenderdaysmap"] = json_encode($calenderdaysmap);
        $data["calenderdays2"] = json_encode($calenderdays2);
        $sessions = $session_array->pluck('name');

        //view for a PEN
        $absents = null;
        $data_dates = array();
        $data_names = array();
        $data_desigs = array();
        $sessionname = $request->query('session');
        $namefilter =  $request->query('namefilter');
        $datefilter =  $request->query('datefilter');

       
           
        $temp =  Employee::with('designation')
                ->wherehas( 'designation', function($q){
                    $q->wherenotin('designation', ['Personal Assistant to MLA']);
                })
             ->where('category','<>','Staff - Admin Data Entry');

        if ($request->filled('session') && $request->filled('namefilter')){
                  
            if (!ctype_digit($namefilter)) {

                 $temp->Where(function ($query) use ($namefilter) {
                    $query->where('name', 'like',  "%".$namefilter."%" );
                    
                });
            } else {

                    $temp->Where(function ($query) use ($namefilter) {
                    $query->where('pen',  $namefilter )
                        ->orwhere('id',  $namefilter );
                    
                });
             }

        }
            
        
        if ($request->filled('session')){

            $temp = $temp->orderby('name','asc')->get()/*->take(50)*/;
            
            $ids = $temp->pluck('id');

            $session = \App\Session::where('name',$sessionname)->get()->first();

            $absents = Attendance::with("employee")
                                ->where('session_id', $session->id)
                                ->wherein('employee_id', $ids);

            if($request->filled('datefilter')){
                $date = Carbon::createFromFormat('d-m-Y', $datefilter);

                $absents->wheredate('date_absent',$date->toDateString());
            }   
            
            $absents = $absents->orderby('date_absent','asc')
                        ->get();

           

            foreach ($absents as $key => $value) {
                $date = Carbon::createFromFormat('Y-m-d', $value->date_absent)->format('M-d');

                if(array_key_exists($value->employee_id,$data_dates)){
                 
                 $data_dates[$value->employee_id] .=  $date . ', ';
                             
                }
                else {
                
                 $data_dates[$value->employee_id] =  $date . ', ';
                 $data_names[$value->employee_id] =  $value->employee->pen . '-'. $value->employee->name ;
                 $data_desigs[$value->employee_id] =  $value->employee->designation->designation;
                }
            }

            $desig_sort_order = explode(",",\App\Preset::
                where('name','default_designation_sortorder')
                ->first()->pens);

            

            uksort($data_desigs, function ($a,$b) use ($desig_sort_order, $data_desigs,$data_names){
            
              $PosA=array_search( "'" . $data_desigs[$a] . "'", $desig_sort_order);
              $PosB=array_search( "'" . $data_desigs[$b] . "'", $desig_sort_order);

              if ($PosA==$PosB){
                $namea = substr($data_names[$a], strpos($data_names[$a], '-'));
                $nameb = substr($data_names[$b], strpos($data_names[$b], '-'));

                return strcasecmp($namea, $nameb);
              }
                else{
                    return ($PosA > $PosB ? 1 : -1);
                }
            });
        }
          
       
        return view('admin.attendances.index', compact('data', 'sessions','data_dates', 'data_names', 'data_desigs'));
        

    }

    public function download()
    {        

       if( $request->filled('session'))
       {
        
        $absents = null;
        
        $sessionname = $request->query('session');
      
           
        $temp =  Employee::with('designation')
                ->wherehas( 'designation', function($q){
                    $q->wherenotin('designation', ['Personal Assistant to MLA']);
                })
             ->where('category','<>','Staff - Admin Data Entry')->get();
            
            $ids = $temp->pluck('id');

            $session = \App\Session::where('name',$sessionname)->get()->first();

            $absents = Attendance::with("employee")
                                ->where('session_id', $session->id)
                                ->wherein('employee_id', $ids)
                                ->get();
/*
            $absents_consolidated = collect();

            foreach ($absents as $value) {
                if(array_key_exists($value['employee_id'], $absents_consolidated)){
                    $absents_consolidated[$value['employee_id']]['date_absent'] .= $value['date_absent'];
                    $absents_consolidated[$value['employee_id']]['count'] += 1;
                } else {
                    $absents_consolidated[$value['employee_id']] = collect();
                    $absents_consolidated[$value['employee_id']]['date_absent'] = $value['date_absent'];
                    $absents_consolidated[$value['employee_id']]['count'] = 1;
                    $absents_consolidated[$value['employee_id']]['employee_id'] = $value['employee_id'];
                }

            }*/
            
            $filename =  $sessionname . '-absentees'.  date('Y-m-d') . '.csv';
            
            $csvExporter = new \Laracsv\Export();


            $csvExporter->build($absents, [ 'date_absent', 'employee_id', 'employee.pen' ]);

            $csvExporter->download($filename);


       }


    }


    /**
     * Show the form for creating new Attendance.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('attendance_create')) {
            return abort(401);
        }
        
        $sessions = \App\Session::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $employees = \App\Employee::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.attendances.create', compact('sessions', 'employees'));
    }

    /**
     * Store a newly created Attendance in storage.
     *
     * @param  \App\Http\Requests\StoreAttendancesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttendancesRequest $request)
    {
        if (! Gate::allows('attendance_create')) {
            return abort(401);
        }
        $attendance = Attendance::create($request->all());



        return redirect()->route('admin.attendances.index');
    }


    /**
     * Show the form for editing Attendance.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('attendance_edit')) {
            return abort(401);
        }
        
        $sessions = \App\Session::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $employees = \App\Employee::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $attendance = Attendance::findOrFail($id);

        return view('admin.attendances.edit', compact('attendance', 'sessions', 'employees'));
    }

    /**
     * Update Attendance in storage.
     *
     * @param  \App\Http\Requests\UpdateAttendancesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttendancesRequest $request, $id)
    {
        if (! Gate::allows('attendance_edit')) {
            return abort(401);
        }
        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());



        return redirect()->route('admin.attendances.index');
    }


    /**
     * Display Attendance.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('attendance_view')) {
            return abort(401);
        }
        $attendance = Attendance::findOrFail($id);

        return view('admin.attendances.show', compact('attendance'));
    }


    /**
     * Remove Attendance from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('attendance_delete')) {
            return abort(401);
        }
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('admin.attendances.index');
    }

    /**
     * Delete all selected Attendance at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('attendance_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Attendance::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }
    public function ajaxfindexactpenforattendace($param)
    {
       
        $arr = explode( "|", $param);
        $search = $arr[0];
        $temp =  Employee::with('designation')
                ->wherehas( 'designation', function($q){
                    $q->wherenotin('designation', ['Personal Assistant to MLA']);
                })
             ->where('category','<>','Staff - Admin Data Entry');

        if (!ctype_digit($search)) {

             $temp->Where(function ($query) use ($search) {
                $query->where('name', 'like',  "%".$search."%" );
                
            });
        } else {

                $temp->Where(function ($query) use ($search) {
                $query->where('pen',  $search )
                    ->orwhere('id',  $search );
                
            });
         }
        

        $temp = $temp->orderby('name','asc')->get()/*->take(50)*/;

        $date = Carbon::createFromFormat(config('app.date_format'), $arr[1]);

        $sittingday = Calender::whereDate('date',$date->toDateString())->first();


        $ids = $temp->pluck('id');

        $absents = Attendance::where('session_id', $sittingday->session->id)
                            ->whereDate('date_absent',$date->toDateString())
                            ->wherein('employee_id', $ids)
                            ->pluck("employee_id");


        $combined = $temp->mapWithKeys(function ($item) {
            return [ $item->pen . '-' . $item->name => $item->designation->designation];
        });

        $absent = $temp->mapWithKeys(function ($item) use ($absents) {
            if($absents->search($item->id) ===false)
                return [ $item->pen . '-' . $item->name => false];
            else
                return [ $item->pen . '-' . $item->name => true];
        });



        
        return [
            'pen_names' => $combined->keys(),
            'pen_names_to_desig' => $combined,
            'pen_names_to_absent' => $absent,

        ];
        
    }
    public function ajaxupdateattendance($param)
    {

      if (! Gate::allows('attendance_edit')) {
            return abort(401);
        }

        $markedabsent = false;
        $arr = explode( "|", $param);
        $search = substr($arr[0], 0,strpos($arr[0], '-'));

        $date = Carbon::createFromFormat(config('app.date_format'), $arr[1]);
        $sittingday = Calender::whereDate('date',$date->toDateString())->first();

        $temp =  Employee::where('pen',  $search )->first();
        if($temp){
            
            $att = Attendance::where('employee_id', $temp->id)
                              ->where('session_id', $sittingday->session->id )
                              ->whereDate('date_absent',$date->toDateString())->get();

            if($att->count()){
                $att->first()->delete();
                $markedabsent = false;
            } else {
                Attendance::create([
                    'date_absent' => $date,
                    'session_id' => $sittingday->session->id,
                    'employee_id' => $temp->id,

                ]);
                $markedabsent = true;
            }


        $log = [//'session' => $sittingday->session->name, 
                'date_absent' => $date->toDateString(),
                'emp' => $arr[0],
                'desig' => $temp->designation->designation,
                'absent' => $markedabsent ? 'YES' : 'NO',

            ];

        $orderLog = new Logger('attendance');
        $orderLog->pushHandler(new StreamHandler(storage_path('logs/attendance' . $sittingday->session->name . '.log' )), Logger::INFO);
        $orderLog->info('', $log);
               
        
        return [
            'res' => true,
            'absent' => $markedabsent ,
            'name' => $arr[0],
            'desig' => $temp->designation->designation,

            

        ];

        } else {

            return [  'res' => false ];


        }

    }

}
