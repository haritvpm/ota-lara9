<?php

namespace App\Http\Controllers\Admin;

use App\Exemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExemptionsRequest;
use App\Http\Requests\Admin\UpdateExemptionsRequest;
use Yajra\Datatables\Datatables;

class ExemptionsController extends Controller
{
    /**
     * Display a listing of Exemption.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('exemption_access')) {
            return abort(401);
        }


        
        if (request()->ajax()) {
            $query = Exemption::query();
            $query->with("form");
            $template = 'actionsTemplate';
            
            $query->select([
                'exemptions.id',
                'exemptions.pen',
                'exemptions.name',
                'exemptions.designation',
                'exemptions.worknature',
                'exemptions.exemptionform_id',
            ]);
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey  = 'exemption_';
                $routeKey = 'admin.exemptions';

                return view($template, compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('worknature', function ($row) {
                return $row->worknature ? $row->worknature : '';
            });
            $table->editColumn('form.session', function ($row) {
                return $row->form ? $row->form->session . '/' . $row->form->id : '';
            });
            $table->rawColumns(['actions']);

            

            return $table->make(true);
        }

        return view('admin.exemptions.index');
    }

    /**
     * Show the form for creating new Exemption.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('exemption_create')) {
            return abort(401);
        }
        
        $exemptionforms = \App\Exemptionform::get()->pluck('session', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.exemptions.create', compact('exemptionforms'));
    }

    /**
     * Store a newly created Exemption in storage.
     *
     * @param  \App\Http\Requests\StoreExemptionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExemptionsRequest $request)
    {
        if (! Gate::allows('exemption_create')) {
            return abort(401);
        }
        $exemption = Exemption::create($request->all());



        return redirect()->route('admin.exemptions.index');
    }


    /**
     * Show the form for editing Exemption.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('exemption_edit')) {
            return abort(401);
        }
        
        $exemptionforms = \App\Exemptionform::get()->pluck('session', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $exemption = Exemption::findOrFail($id);

        return view('admin.exemptions.edit', compact('exemption', 'exemptionforms'));
    }

    /**
     * Update Exemption in storage.
     *
     * @param  \App\Http\Requests\UpdateExemptionsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExemptionsRequest $request, $id)
    {
        if (! Gate::allows('exemption_edit')) {
            return abort(401);
        }
        $exemption = Exemption::findOrFail($id);
        $exemption->update($request->all());



        return redirect()->route('admin.exemptions.index');
    }


    /**
     * Display Exemption.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('exemption_view')) {
            return abort(401);
        }
        $exemption = Exemption::findOrFail($id);

        return view('admin.exemptions.show', compact('exemption'));
    }


    /**
     * Remove Exemption from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('exemption_delete')) {
            return abort(401);
        }
        $exemption = Exemption::findOrFail($id);
        $exemption->delete();

        return redirect()->route('admin.exemptions.index');
    }

    /**
     * Delete all selected Exemption at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('exemption_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Exemption::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
