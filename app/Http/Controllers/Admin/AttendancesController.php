<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Employee;
use App\Calender;

use App\Attendance;
// use App\Http\Controllers\Traits\CsvImportTrait;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAttendancesRequest;
use App\Http\Requests\Admin\UpdateAttendancesRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use \SpreadsheetReader;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AttendancesController extends Controller
{
    // use CsvImportTrait;
    
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
      

        $q = \App\Session::whereDataentryAllowed('Yes')->latest();

        $session_array = $q->get();
                 
        $sessions = $session_array->pluck('name');

        //view for a PEN
              
        $sessionname = $request->query('session', $sessions[0]);
       
              
        $attendances = Attendance::all();
                    
        
        if ($request->filled('session')){

            $temp = $temp->orderby('name','asc')->get()/*->take(50)*/;
            
            $ids = $temp->pluck('id');

            $session = \App\Session::where('name',$sessionname)->get()->first();
                 
                   
           
        }
          
       
        return view('admin.attendances.index', compact('sessions','attendances'));
        

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

            $filename =  $sessionname . '-absentees'.  date('Y-m-d') . '.csv';
            
            $csvExporter = new \Laracsv\Export();


            $csvExporter->build($absents, [ 'present_dates', 'employee_id', 'employee.pen' ]);

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
   
    
    public function parseCsvImportCustom(Request $request)
    {
        try {
            $file = $request->file('csv_file');
            $request->validate([
                'csv_file' => 'mimes:csv,txt',
            ]);

            $path      = $file->path();
            $filename = $file->getClientOriginalName();
            $sessionname = strtok($filename, ' ');
           // $hasHeader = $request->input('header', false) ? true : false;

            $reader  = new SpreadsheetReader($path);
            $headers = $reader->current();

        
            $session = \App\Session::whereDataentryAllowed('Yes')->whereName($sessionname)->first();

            if(!$session){
                File::delete($path);
                return redirect()->route('admin.attendances.index')->withErrors(['Session not-found or dataentry-not-allowed (extracted from first part of filename): ' . $sessionname ]);
            }
           // dd($session);

            $fields = array('present_dates' => 5,
                            'pen' => 2, 
                            'name' => 1,
                            'total' => 6);
            $insert = [];

            // $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            foreach ($reader as $key => $row) {
                //  $out->writeln( '------' . $key );
                if (/*$hasHeader &&*/ $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {
                    if (isset($row[$k])) {
                        $tmp[$header] = $row[$k];
                    }
                }

                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            File::delete($path);

            //find if employee exists
            $pens =[];
            foreach ($insert as $insert_item) {
                $insert_item['pen'] = str_replace(' ', '', $insert_item['pen']);
                $pens[] = $insert_item['pen'];
            }

            $employees_indb = Employee::select('pen')->whereIn('pen', $pens)->get()->pluck('pen');

            $result = array_diff($pens, $employees_indb->toarray());

           

            if( count( $result ) ){
                
                return redirect()->route('admin.attendances.index')->withErrors(['PEN not found: ' . implode( ',', array_values($result) )]);
            }

            $for_insert = array_chunk($insert, 100);

            foreach ($for_insert as $insert_item) {
                $session->attendances()->createMany($insert_item);
                
               
            }

            $rows  = count($insert);
           
            File::delete($path);

            session()->flash('message', 'imported :rows', [$rows]);
            
            return redirect()->route('admin.attendances.index');
        } catch (\Exception $ex) {
            throw $ex;
        }
        
    }

   

}
