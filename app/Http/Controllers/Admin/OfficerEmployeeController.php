<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\Admin\MassDestroyOfficerEmployeeRequest;
use App\Http\Requests\Admin\StoreOfficerEmployeeRequest;
use App\Http\Requests\Admin\UpdateOfficerEmployeeRequest;
use App\OfficerEmployee;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OfficerEmployeeController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('officer_employee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $officerEmployees = OfficerEmployee::with(['officer', 'employee'])->get();

        return view('admin.officerEmployees.index', compact('officerEmployees'));
    }

    public function create()
    {
        abort_if(Gate::denies('officer_employee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $officers = User::DSorAboveOfficers()->get()->mapWithKeys(function ($item) {

            return [$item['id'] => $item->title . ' (' . $item->username .')'  ];
        
        })->prepend(trans('global.pleaseSelect'), '');;



        $employees =  Employee::with('designation')->ProperlyFilled()->Active()->orderby('name','asc')->get()->mapWithKeys(function ($item) {
            return [$item['id'] =>  $item->AadharrPenNameDesig ];
            
        })->prepend(trans('global.pleaseSelect'), '');;
      

        return view('admin.officerEmployees.create', compact('employees', 'officers'));
    }

    public function store(StoreOfficerEmployeeRequest $request)
    {
        $officerEmployee = OfficerEmployee::create($request->all());

        return redirect()->route('admin.officer-employees.index');
    }

    public function edit(OfficerEmployee $officerEmployee)
    {
        abort_if(Gate::denies('officer_employee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

       
        $officers = User::DSorAboveOfficers()->get()->mapWithKeys(function ($item) {

            return [$item['id'] => $item->title . ' (' . $item->username .')'  ];
        
        })->prepend(trans('global.pleaseSelect'), '');;


        $employees =  Employee::with('designation')->ProperlyFilled()->Active()->orderby('name','asc')->get()->mapWithKeys(function ($item) {
            return [$item['id'] =>  $item->AadharrPenNameDesig ];
            
        })->prepend(trans('global.pleaseSelect'), '');;
      

        $officerEmployee->load('officer', 'employee');

        return view('admin.officerEmployees.edit', compact('employees', 'officerEmployee', 'officers'));
    }

    public function update(UpdateOfficerEmployeeRequest $request, OfficerEmployee $officerEmployee)
    {
        $officerEmployee->update($request->all());

        return redirect()->route('admin.officer-employees.index');
    }

    public function show(OfficerEmployee $officerEmployee)
    {
        abort_if(Gate::denies('officer_employee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $officerEmployee->load('officer', 'employee');

        return view('admin.officerEmployees.show', compact('officerEmployee'));
    }

    public function destroy(OfficerEmployee $officerEmployee)
    {
        abort_if(Gate::denies('officer_employee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $officerEmployee->delete();

        return back();
    }

    public function massDestroy(MassDestroyOfficerEmployeeRequest $request)
    {
        $officerEmployees = OfficerEmployee::find(request('ids'));

        foreach ($officerEmployees as $officerEmployee) {
            $officerEmployee->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
