<?php

namespace App\Http\Controllers\Admin;

use App\Calender;
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
            $query->with("session")->orderby('date','desc');
            $template = 'actionsTemplate';
            
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;')->rawColumns(['actions']);;
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey  = 'calender_';
                $routeKey = 'admin.calenders';

                return view($template, compact('row', 'gateKey', 'routeKey'));
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

        $forms_count = \App\Form::where('session',$session->name)->count();
        //$forms_other_count = \App\FormOther::where('session',$session->name)->count();

        if( $forms_count > 0 /*|| $forms_other_count > 0 */){

            \Session::flash('message-danger', 'Unable to delete as ' . $forms_count . ' forms still belong to this calender date session' ); 

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
