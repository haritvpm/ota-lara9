<?php

namespace App\Http\Controllers\Admin;

use App\FormOther;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFormsOthersRequest;
use App\Http\Requests\Admin\UpdateFormsOthersRequest;
use Yajra\DataTables\DataTables;

class FormsOthersController extends Controller
{
    /**
     * Display a listing of FormOther.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('forms_other_access')) {
            return abort(401);
        }


        
        if (request()->ajax()) {
            $query = FormOther::query();
            $template = 'actionsTemplate';
            
            $table = Datatables::of($query);

            $table->setRowAttr([
                'data-entry-id' => '{{$id}}',
            ]);
            $table->addColumn('massDelete', '&nbsp;');
            $table->addColumn('actions', '&nbsp;')->rawColumns(['actions']);;
            $table->editColumn('actions', function ($row) use ($template) {
                $gateKey  = 'forms_other_';
                $routeKey = 'admin.forms_others';

                return view($template, compact('row', 'gateKey', 'routeKey'));
            });
            $table->editColumn('owner', function ($row) {
                return $row->owner ? $row->owner : '';
            });
            $table->editColumn('form_no', function ($row) {
                return $row->form_no ? $row->form_no : '';
            });
            $table->editColumn('duty_date', function ($row) {
                return $row->duty_date ? $row->duty_date : '';
            });
            $table->editColumn('date_from', function ($row) {
                return $row->date_from ? $row->date_from : '';
            });
            $table->editColumn('date_to', function ($row) {
                return $row->date_to ? $row->date_to : '';
            });

            

            return $table->make(true);
        }

        return view('admin.forms_others.index');
    }

    /**
     * Show the form for creating new FormOther.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('forms_other_create')) {
            return abort(401);
        }        $enum_overtime_slot = FormOther::$enum_overtime_slot;
            
        return view('admin.forms_others.create', compact('enum_overtime_slot'));
    }

    /**
     * Store a newly created FormOther in storage.
     *
     * @param  \App\Http\Requests\StoreFormsOthersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFormsOthersRequest $request)
    {
        if (! Gate::allows('forms_other_create')) {
            return abort(401);
        }
        $forms_other = FormOther::create($request->all());



        return redirect()->route('admin.forms_others.index');
    }


    /**
     * Show the form for editing FormOther.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('forms_other_edit')) {
            return abort(401);
        }        $enum_overtime_slot = FormOther::$enum_overtime_slot;
            
        $forms_other = FormOther::findOrFail($id);

        return view('admin.forms_others.edit', compact('forms_other', 'enum_overtime_slot'));
    }

    /**
     * Update FormOther in storage.
     *
     * @param  \App\Http\Requests\UpdateFormsOthersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFormsOthersRequest $request, $id)
    {
        if (! Gate::allows('forms_other_edit')) {
            return abort(401);
        }
        $forms_other = FormOther::findOrFail($id);
        $forms_other->update($request->all());



        return redirect()->route('admin.forms_others.index');
    }


    /**
     * Display FormOther.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('forms_other_view')) {
            return abort(401);
        }
        $overtimes_others = \App\OvertimeOther::where('form_id', $id)->get();

        $forms_other = FormOther::findOrFail($id);

        return view('admin.forms_others.show', compact('forms_other', 'overtimes_others'));
    }


    /**
     * Remove FormOther from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('forms_other_delete')) {
            return abort(401);
        }
        $forms_other = FormOther::findOrFail($id);
        $forms_other->delete();

        return redirect()->route('admin.forms_others.index');
    }

    /**
     * Delete all selected FormOther at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('forms_other_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = FormOther::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}
