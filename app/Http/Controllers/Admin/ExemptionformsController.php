<?php

namespace App\Http\Controllers\Admin;

use App\Exemptionform;

use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExemptionformsRequest;
use App\Http\Requests\Admin\UpdateExemptionformsRequest;
use Yajra\Datatables\Datatables;

class ExemptionformsController extends Controller
{
    /**
     * Display a listing of Exemptionform.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('exemptionform_access')) {
            return abort(401);
        }


        
        if (request()->ajax()) {
            $query = Exemptionform::query();
            $template = 'actionsTemplate';
            
            $query->select([
                'exemptionforms.id',
                'exemptionforms.session',
                'exemptionforms.creator',
                'exemptionforms.owner',
                'exemptionforms.form_no',
                'exemptionforms.submitted_names',
                'exemptionforms.submitted_by',
                'exemptionforms.submitted_on',
                'exemptionforms.remarks',
            ]);
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey  = 'exemptionform_';
                $routeKey = 'admin.exemptionforms';

                return view($template, compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('owner', function ($row) {
                return $row->owner ? $row->owner : '';
            });
            $table->editColumn('form_no', function ($row) {
                return $row->form_no ? $row->form_no : '';
            });
            $table->editColumn('submitted_names', function ($row) {
                return $row->submitted_names ? $row->submitted_names : '';
            });
            $table->editColumn('submitted_by', function ($row) {
                return $row->submitted_by ? $row->submitted_by : '';
            });
            $table->editColumn('submitted_on', function ($row) {
                return $row->submitted_on ? $row->submitted_on : '';
            });
            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });
            $table->rawColumns(['actions']);
            

            return $table->make(true);
        }

        return view('admin.exemptionforms.index');
    }

    /**
     * Show the form for creating new Exemptionform.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('exemptionform_create')) {
            return abort(401);
        }
        return view('admin.exemptionforms.create');
    }

    /**
     * Store a newly created Exemptionform in storage.
     *
     * @param  \App\Http\Requests\StoreExemptionformsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExemptionformsRequest $request)
    {
        if (! Gate::allows('exemptionform_create')) {
            return abort(401);
        }
        $exemptionform = Exemptionform::create($request->all());



        return redirect()->route('admin.exemptionforms.index');
    }


    /**
     * Show the form for editing Exemptionform.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('exemptionform_edit')) {
            return abort(401);
        }
        $exemptionform = Exemptionform::findOrFail($id);

        return view('admin.exemptionforms.edit', compact('exemptionform'));
    }

    /**
     * Update Exemptionform in storage.
     *
     * @param  \App\Http\Requests\UpdateExemptionformsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExemptionformsRequest $request, $id)
    {
        if (! Gate::allows('exemptionform_edit')) {
            return abort(401);
        }
        $exemptionform = Exemptionform::findOrFail($id);
        $exemptionform->update($request->all());



        return redirect()->route('admin.exemptionforms.index');
    }


    /**
     * Display Exemptionform.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('exemptionform_view')) {
            return abort(401);
        }
        $exemptions = \App\Exemption::where('exemptionform_id', $id)->get();

        $exemptionform = Exemptionform::findOrFail($id);

        return view('admin.exemptionforms.show', compact('exemptionform', 'exemptions'));
    }


    /**
     * Remove Exemptionform from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('exemptionform_delete')) {
            return abort(401);
        }
        $exemptionform = Exemptionform::findOrFail($id);
        $exemptionform->delete();

        return redirect()->route('admin.exemptionforms.index');
    }

    /**
     * Delete all selected Exemptionform at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('exemptionform_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Exemptionform::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
