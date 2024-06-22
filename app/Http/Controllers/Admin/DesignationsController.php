<?php

namespace App\Http\Controllers\Admin;

use App\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDesignationsRequest;
use App\Http\Requests\Admin\UpdateDesignationsRequest;
use Yajra\DataTables\DataTables;
use App\OfficeTime;

class DesignationsController extends Controller
{
    /**
     * Display a listing of Designation.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('designation_access')) {
            return abort(401);
        }


                $designations = Designation::all();

        return view('admin.designations.index', compact('designations'));
    }

    /**
     * Show the form for creating new Designation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('designation_create')) {
            return abort(401);
        }
       $office_times = OfficeTime::pluck('groupname', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.designations.create', compact('office_times'));
    }

    /**
     * Store a newly created Designation in storage.
     *
     * @param  \App\Http\Requests\StoreDesignationsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDesignationsRequest $request)
    {
        if (! Gate::allows('designation_create')) {
            return abort(401);
        }
        $designation = Designation::create($request->all());



        return redirect()->route('admin.designations.index');
    }


    /**
     * Show the form for editing Designation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('designation_edit')) {
            return abort(401);
        }
        $designation = Designation::findOrFail($id);
	   $office_times = OfficeTime::pluck('groupname', 'id')->prepend(trans('global.pleaseSelect'), '');

        $designation->load('office_time');

        return view('admin.designations.edit', compact('designation', 'office_times'));
    }

    /**
     * Update Designation in storage.
     *
     * @param  \App\Http\Requests\UpdateDesignationsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDesignationsRequest $request, $id)
    {
        if (! Gate::allows('designation_edit')) {
            return abort(401);
        }
        $designation = Designation::findOrFail($id);
        $designation->update($request->all());



        return redirect()->route('admin.designations.index');
    }


    /**
     * Display Designation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('designation_view')) {
            return abort(401);
        }
        $employees = \App\Employee::where('designation_id', $id)->get();

        $designation = Designation::findOrFail($id);
	 $designation->load('office_time');

        return view('admin.designations.show', compact('designation', 'employees'));
    }


    /**
     * Remove Designation from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('designation_delete')) {
            return abort(401);
        }
        $designation = Designation::findOrFail($id);
        $designation->delete();

        return redirect()->route('admin.designations.index');
    }

    /**
     * Delete all selected Designation at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('designation_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Designation::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

     public function download_desig()
    {        

        $desig = \App\Designation::orderby('rate','desc')->orderby('id','asc')->get();

        $filename =  'sectt_designation_table-'.  date('Y-m-d') . '.csv';
        
        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($desig, [ 'id','designation','rate' ]);

        $csvExporter->download($filename);

    }

}
