<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\Admin\MassDestroySectionEmployeeRequest;
use App\Http\Requests\Admin\StoreSectionEmployeeRequest;
use App\Http\Requests\Admin\UpdateSectionEmployeeRequest;
use App\Section;
use App\SectionEmployee;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SectionEmployeeController extends Controller
{
    use CsvImportTrait;

    // public function index(Request $request)
    // {
    //     abort_if(Gate::denies('section_employee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     if ($request->ajax()) {
    //         $query = SectionEmployee::with(['section_or_offfice', 'employee'])->select(sprintf('%s.*', (new SectionEmployee)->table));
    //         $table = Datatables::of($query);

    //         $table->addColumn('placeholder', '&nbsp;');
    //         $table->addColumn('actions', '&nbsp;');

    //         $table->editColumn('actions', function ($row) {
    //             $viewGate      = 'section_employee_show';
    //             $editGate      = 'section_employee_edit';
    //             $deleteGate    = 'section_employee_delete';
    //             $crudRoutePart = 'section-employees';

    //             return view('partials.datatablesActions', compact(
    //                 'viewGate',
    //                 'editGate',
    //                 'deleteGate',
    //                 'crudRoutePart',
    //                 'row'
    //             ));
    //         });

    //         $table->editColumn('id', function ($row) {
    //             return $row->id ? $row->id : '';
    //         });
    //         $table->addColumn('section_or_offfice_name', function ($row) {
    //             return $row->section_or_offfice ? $row->section_or_offfice->name : '';
    //         });

    //         $table->addColumn('employee_name', function ($row) {
    //             return $row->employee ? $row->employee->name : '';
    //         });

    //         $table->editColumn('employee.aadhaarid', function ($row) {
    //             return $row->employee ? (is_string($row->employee) ? $row->employee : $row->employee->aadhaarid) : '';
    //         });

    //         $table->rawColumns(['actions', 'placeholder', 'section_or_offfice', 'employee']);

    //         return $table->make(true);
    //     }

    //     return view('admin.sectionEmployees.index');
    // }
    public function index()
    {
        abort_if(Gate::denies('section_employee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sectionEmployees = SectionEmployee::with(['section_or_offfice', 'employee'])->get();

        return view('admin.sectionEmployees.index', compact('sectionEmployees'));
    }

    public function create()
    {
        abort_if(Gate::denies('section_employee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $section_or_offfices = Section::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

         
        $employees =  Employee::with('designation')->ProperlyFilled()->Active()->orderby('name','asc')->get()->mapWithKeys(function ($item) {
            return [$item['id'] =>  $item->AadharrPenNameDesig ];
            
        })->prepend(trans('global.pleaseSelect'), '');;
      

        return view('admin.sectionEmployees.create', compact('employees', 'section_or_offfices'));
    }

    public function store(StoreSectionEmployeeRequest $request)
    {
        $sectionEmployee = SectionEmployee::create($request->all());

        return redirect()->route('admin.section-employees.index');
    }

    public function edit(SectionEmployee $sectionEmployee)
    {
        abort_if(Gate::denies('section_employee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $section_or_offfices = Section::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $employees =  Employee::with('designation')->ProperlyFilled()->Active()->orderby('name','asc')->get()->mapWithKeys(function ($item) {
            return [$item['id'] =>  $item->AadharrPenNameDesig ];
            
        })->prepend(trans('global.pleaseSelect'), '');;
      

        $sectionEmployee->load('section_or_offfice', 'employee');

        return view('admin.sectionEmployees.edit', compact('employees', 'sectionEmployee', 'section_or_offfices'));
    }

    public function update(UpdateSectionEmployeeRequest $request, SectionEmployee $sectionEmployee)
    {
        $sectionEmployee->update($request->all());

        return redirect()->route('admin.section-employees.index');
    }

    public function show(SectionEmployee $sectionEmployee)
    {
        abort_if(Gate::denies('section_employee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sectionEmployee->load('section_or_offfice', 'employee');

        return view('admin.sectionEmployees.show', compact('sectionEmployee'));
    }

    public function destroy(SectionEmployee $sectionEmployee)
    {
        abort_if(Gate::denies('section_employee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sectionEmployee->delete();

        return back();
    }

    public function massDestroy(MassDestroySectionEmployeeRequest $request)
    {
        $sectionEmployees = SectionEmployee::find(request('ids'));

        foreach ($sectionEmployees as $sectionEmployee) {
            $sectionEmployee->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
