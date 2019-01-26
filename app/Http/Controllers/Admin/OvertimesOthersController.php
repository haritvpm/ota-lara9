<?php

namespace App\Http\Controllers\Admin;

use App\OvertimeOther;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOvertimesOthersRequest;
use App\Http\Requests\Admin\UpdateOvertimesOthersRequest;
use Yajra\DataTables\DataTables;

class OvertimesOthersController extends Controller
{
    /**
     * Display a listing of OvertimeOther.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('overtimes_other_access')) {
            return abort(401);
        }


        
        if (request()->ajax()) {
            $query = OvertimeOther::query();
            $query->with("form");
            $template = 'actionsTemplate';
            
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;')->rawColumns(['actions']);;
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey  = 'overtimes_other_';
                $routeKey = 'admin.overtimes_others';

                return view($template, compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('worknature', function ($row) {
                return $row->worknature ? $row->worknature : '';
            });

            

            return $table->make(true);
        }

        return view('admin.overtimes_others.index');
    }

    /**
     * Show the form for creating new OvertimeOther.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*if (! Gate::allows('overtimes_other_create')) {
            return abort(401);
        }*/
        
        $forms = \App\FormOther::get()->pluck('session', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.overtimes_others.create', compact('forms'));
    }

    /**
     * Store a newly created OvertimeOther in storage.
     *
     * @param  \App\Http\Requests\StoreOvertimesOthersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOvertimesOthersRequest $request)
    {
        /*if (! Gate::allows('overtimes_other_create')) {
            return abort(401);
        }*/
        $overtimes_other = OvertimeOther::create($request->all());



        return redirect()->route('admin.overtimes_others.index');
    }


    /**
     * Show the form for editing OvertimeOther.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       /* if (! Gate::allows('overtimes_other_edit')) {
            return abort(401);
        }*/
        
        $forms = \App\FormOther::get()->pluck('session', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $overtimes_other = OvertimeOther::findOrFail($id);

        return view('admin.overtimes_others.edit', compact('overtimes_other', 'forms'));
    }

    /**
     * Update OvertimeOther in storage.
     *
     * @param  \App\Http\Requests\UpdateOvertimesOthersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOvertimesOthersRequest $request, $id)
    {
       /* if (! Gate::allows('overtimes_other_edit')) {
            return abort(401);
        }*/
        $overtimes_other = OvertimeOther::findOrFail($id);
        $overtimes_other->update($request->all());



        return redirect()->route('admin.overtimes_others.index');
    }


    /**
     * Display OvertimeOther.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('overtimes_other_view')) {
            return abort(401);
        }
        $overtimes_other = OvertimeOther::findOrFail($id);

        return view('admin.overtimes_others.show', compact('overtimes_other'));
    }


    /**
     * Remove OvertimeOther from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       /* if (! Gate::allows('overtimes_other_delete')) {
            return abort(401);
        }*/
        $overtimes_other = OvertimeOther::findOrFail($id);
        $overtimes_other->delete();

        return redirect()->route('admin.overtimes_others.index');
    }

    /**
     * Delete all selected OvertimeOther at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('overtimes_other_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = OvertimeOther::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
