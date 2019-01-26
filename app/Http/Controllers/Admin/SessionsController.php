<?php

namespace App\Http\Controllers\Admin;

use App\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSessionsRequest;
use App\Http\Requests\Admin\UpdateSessionsRequest;
use Yajra\DataTables\DataTables;

class SessionsController extends Controller
{
    /**
     * Display a listing of Session.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('session_access')) {
            return abort(401);
        }


        $sessions = Session::latest()->get();

        return view('admin.sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating new Session.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('session_create')) {
            return abort(401);
        }        

        $enum_dataentry_allowed = Session::$enum_dataentry_allowed;
        $enum_show_in_datatable = Session::$enum_show_in_datatable;
        $enum_exemption_entry = Session::$enum_exemption_entry;

        return view('admin.sessions.create', compact('enum_dataentry_allowed', 'enum_show_in_datatable', 'enum_exemption_entry'));
    }

    /**
     * Store a newly created Session in storage.
     *
     * @param  \App\Http\Requests\StoreSessionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSessionsRequest $request)
    {
        if (! Gate::allows('session_create')) {
            return abort(401);
        }
        $session = Session::create($request->all());



        return redirect()->route('admin.sessions.index');
    }


    /**
     * Show the form for editing Session.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('session_edit')) {
            return abort(401);
        }        
        $enum_dataentry_allowed = Session::$enum_dataentry_allowed;
        $enum_show_in_datatable = Session::$enum_show_in_datatable;
        $enum_exemption_entry = Session::$enum_exemption_entry;

        $session = Session::findOrFail($id);

        return view('admin.sessions.edit', compact('session', 'enum_dataentry_allowed', 'enum_show_in_datatable', 'enum_exemption_entry'));
    }

    /**
     * Update Session in storage.
     *
     * @param  \App\Http\Requests\UpdateSessionsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSessionsRequest $request, $id)
    {
        if (! Gate::allows('session_edit')) {
            return abort(401);
        }
        $session = Session::findOrFail($id);

        if( \Auth::user()->isAdmin() ){

            $session->update($request->all());
        } else {
            $session->update([
                'exemption_entry' => $request['exemption_entry'],
            ]);
        }



        return redirect()->route('admin.sessions.index');
    }


    /**
     * Display Session.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('session_view')) {
            return abort(401);
        }
       // $calenders = \App\Calender::where('session_id', $id)->orderby('date','asc')->get();

        $session = Session::findOrFail($id);

        $calenders = $session->calender()->orderby('date','asc')->get();


        $maxsittingdates = $calenders                           
                                ->where('day_type','Sitting day')->count();

        //dd($maxsittingdates);

        return view('admin.sessions.show', compact('session', 'calenders','maxsittingdates'));
    }


    /**
     * Remove Session from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('session_delete')) {
            return abort(401);
        }
        $session = Session::findOrFail($id);

        $forms_count = \App\Form::where('session',$session->name)->count();
        $forms_other_count = \App\FormOther::where('session',$session->name)->count();

        if( $forms_count > 0 || $forms_other_count > 0 ){

            \Session::flash('message-danger', 'Unable to delete as ' . $forms_count . ' forms and '. $forms_other_count . ' forms(other) still belong to this session' ); 

            return redirect()->route('admin.sessions.index');
        }


        $session->delete();

        return redirect()->route('admin.sessions.index');
    }

    /**
     * Delete all selected Session at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('session_delete')) {
            return abort(401);
        }
        /*
        if ($request->input('ids')) {
            $entries = Session::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }*/
    }

}
