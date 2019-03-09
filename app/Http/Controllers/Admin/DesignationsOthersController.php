<?php

namespace App\Http\Controllers\Admin;

use App\DesignationsOther;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDesignationsOthersRequest;
use App\Http\Requests\Admin\UpdateDesignationsOthersRequest;
use Yajra\DataTables\DataTables;

class DesignationsOthersController extends Controller
{
    /**
     * Display a listing of DesignationsOther.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('designations_other_access')) {
            return abort(401);
        }

        $designations_others = DesignationsOther::with('user')-> where('user_id', \Auth::user()->id );

        if(\Auth::user()->isAdmin()){

            $designations_others = DesignationsOther::orderBy('created_at', 'desc');
        }

        $designations_others = $designations_others->get();

        return view('admin.designations_others.index', compact('designations_others'));
    }

    /**
     * Show the form for creating new DesignationsOther.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('designations_other_create')) {
            return abort(401);
        }
        
        $users = \App\User::OtherDeptUsers()->get()->pluck('username', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.designations_others.create', compact('users'));
    }

    /**
     * Store a newly created DesignationsOther in storage.
     *
     * @param  \App\Http\Requests\StoreDesignationsOthersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDesignationsOthersRequest $request)
    {
        if (! Gate::allows('designations_other_create')) {
            return abort(401);
        }
        $designations_other = DesignationsOther::create($request->all());



        return redirect()->route('admin.designations_others.index');
    }


    /**
     * Show the form for editing DesignationsOther.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('designations_other_edit')) {
            return abort(401);
        }
        $designations_other = DesignationsOther::findOrFail($id);

         $users = \App\User::OtherDeptUsers()->get()->pluck('username', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.designations_others.edit', compact('designations_other', 'users' ));
    }

    /**
     * Update DesignationsOther in storage.
     *
     * @param  \App\Http\Requests\UpdateDesignationsOthersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDesignationsOthersRequest $request, $id)
    {
        if (! Gate::allows('designations_other_edit')) {
            return abort(401);
        }
        $designations_other = DesignationsOther::findOrFail($id);
        $designations_other->update($request->all());



        return redirect()->route('admin.designations_others.index');
    }


    /**
     * Display DesignationsOther.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('designations_other_view')) {
            return abort(401);
        }
        $designations_other = DesignationsOther::findOrFail($id);

        return view('admin.designations_others.show', compact('designations_other'));
    }


    /**
     * Remove DesignationsOther from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('designations_other_delete')) {
            return abort(401);
        }
        $designations_other = DesignationsOther::findOrFail($id);
        $designations_other->delete();

        return redirect()->route('admin.designations_others.index');
    }

    /**
     * Delete all selected DesignationsOther at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('designations_other_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = DesignationsOther::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

    public function download_desig()
    {        

        $desig = \App\DesignationsOther::orderby('rate','desc')->orderby('id','asc')->get();

        $filename =  'otherdept_designation_table-'.  date('Y-m-d') . '.csv';
        
        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($desig, [ 'id','designation', 'max_persons' , 'user_id' , 'rate' ]);

        $csvExporter->download($filename);

    }

}
