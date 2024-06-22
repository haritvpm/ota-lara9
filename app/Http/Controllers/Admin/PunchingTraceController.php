<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PunchingTrace;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PunchingTraceController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('designation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PunchingTrace::query()->select(sprintf('%s.*', (new PunchingTrace)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'punching_trace_show';
                $editGate      = 'punching_trace_edit';
                $deleteGate    = 'punching_trace_delete';
                $crudRoutePart = 'punching-traces';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('aadhaarid', function ($row) {
                return $row->aadhaarid ? $row->aadhaarid : '';
            });
            $table->editColumn('org_emp_code', function ($row) {
                return $row->org_emp_code ? $row->org_emp_code : '';
            });
            $table->editColumn('device', function ($row) {
                return $row->device ? $row->device : '';
            });
            $table->editColumn('attendance_type', function ($row) {
                return $row->attendance_type ? $row->attendance_type : '';
            });
            $table->editColumn('auth_status', function ($row) {
                return $row->auth_status ? $row->auth_status : '';
            });
            $table->editColumn('err_code', function ($row) {
                return $row->err_code ? $row->err_code : '';
            });

            $table->editColumn('att_time', function ($row) {
                return $row->att_time ? $row->att_time : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.punchingTraces.index');
    }
}
