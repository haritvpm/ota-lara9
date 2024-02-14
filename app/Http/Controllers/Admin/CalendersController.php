<?php

namespace App\Http\Controllers\Admin;


use App\Calender;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCalendersRequest;
use App\Http\Requests\Admin\UpdateCalendersRequest;
use Yajra\DataTables\DataTables;

class CalendersController extends Controller
{
    /**
     * Display a listing of Calender.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('calender_access')) {
            return abort(401);
        }

              
        if (request()->ajax()) {
            $query = Calender::query();
            $query->with("session")->whereHas('session', function ($query) {
                $query->where('show_in_datatable', 'Yes');
               })->orderby('date','desc');
            $template = 'actionsTemplate';
            
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
             $table->addColumn('punching_', function ($row) {
               return Calender::PUNCHING_SELECT[$row->punching] ?? '' ;
               
             });
             
             $table->addColumn('punchin_actions', '&nbsp;')->rawColumns(['punchin_actions']);;
             $table->editColumn('punchin_actions', function ($row) {
                 $gateKey  = 'calender_';
                 $routeKey = 'admin.punchings';
 
                 return view('actionsTemplatePunching', compact('row', 'gateKey', 'routeKey'));
             });
             
            $table->addColumn('actions', '&nbsp;')->rawColumns(['actions']);;
            $table->editColumn('actions', function ($row) {
                $gateKey  = 'calender_';
                $routeKey = 'admin.calenders';

                return view('actionsTemplate', compact('row', 'gateKey', 'routeKey'));
            });


            return $table->make(true);
        }

        return view('admin.calenders.index');
    }

    /**
     * Show the form for creating new Calender.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('calender_create')) {
            return abort(401);
        }
        
        $sessions = \App\Session::latest()->pluck('name', 'id');
        $enum_day_type = Calender::$enum_day_type;
      

        $would_be_date = old('date');
        if(null == $would_be_date){
            $last_date =  \App\Calender::orderby('date','desc')->first();
            if($last_date!=null){
                $would_be_date = date('d-m-Y', strtotime($last_date->date. ' + 1 days'));
            }
        }
            
        return view('admin.calenders.create', 
                    compact('enum_day_type', 'sessions','would_be_date'));
    }

    /**
     * Store a newly created Calender in storage.
     *
     * @param  \App\Http\Requests\StoreCalendersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCalendersRequest $request)
    {
        if (! Gate::allows('calender_create')) {
            return abort(401);
        }
 
        $calender = Calender::create($request->all());

        \Session::flash('message-success', 'Success: added: ' . $calender->date ); 
        
        switch($request->submitbutton) {
          
            case 'Save & New':

                return redirect()->route('admin.calenders.create');
            break;
        }

        return redirect()->route('admin.calenders.index');
    }


    /**
     * Show the form for editing Calender.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('calender_edit')) {
            return abort(401);
        }
        
        $sessions = \App\Session::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $enum_day_type = Calender::$enum_day_type;
            
        $calender = Calender::findOrFail($id);

        return view('admin.calenders.edit', compact('calender', 'enum_day_type', 'sessions'));
    }

    /**
     * Update Calender in storage.
     *
     * @param  \App\Http\Requests\UpdateCalendersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCalendersRequest $request, $id)
    {
        if (! Gate::allows('calender_edit')) {
            return abort(401);
        }
        $calender = Calender::findOrFail($id);
        $calender->update($request->all());



        return redirect()->route('admin.calenders.index');
    }


    /**
     * Display Calender.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('calender_view')) {
            return abort(401);
        }
        $calender = Calender::findOrFail($id);

        return view('admin.calenders.show', compact('calender'));
    }


    /**
     * Remove Calender from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('calender_delete')) {
            return abort(401);
        }
        $calender = Calender::findOrFail($id);
        $session = $calender->session;

        $date = Carbon::createFromFormat(config('app.date_format'), $calender->date)->format('Y-m-d');

        //dd ($date  . '-----' . $calender->date);

        $forms_count = \App\Form::where('session',$session->name)
                        ->where('overtime_slot', '<>' ,'Sittings')
                        ->where('duty_date', $date)
                        ->count();

        //if sitting forms are there, it means session has already finished
        $sitting_form_count =  \App\Form::where('session',$session->name)
                                ->where('overtime_slot', 'Sittings')->count();

        //$forms_other_count = \App\FormOther::where('session',$session->name)->count();

        if( $forms_count > 0 ){

            \Session::flash('message-danger', 'Unable to delete as ' . $forms_count . ' forms belong to this date' ); 

            return redirect()->route('admin.calenders.index');
        }

         if( /*|| $forms_other_count > 0  ||*/ $sitting_form_count > 0){

            \Session::flash('message-danger', 'Unable to delete as ' . $sitting_form_count . ' sittings forms belong to this session' ); 

            return redirect()->route('admin.calenders.index');
        }


        $calender->delete();

        return redirect()->route('admin.calenders.index');
    }

    /**
     * Delete all selected Calender at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('calender_delete')) {
            return abort(401);
        }
        /*
        Do not allow delete by datatable check marks.
        calender date delete one by one
        if ($request->input('ids')) {
            $entries = Calender::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
        */
    }

}
