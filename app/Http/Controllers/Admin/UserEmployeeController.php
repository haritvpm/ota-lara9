<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\Admin\MassDestroyUserEmployeeRequest;
use App\Http\Requests\Admin\StoreUserEmployeeRequest;
use App\Http\Requests\Admin\UpdateUserEmployeeRequest;
use App\User;
use App\UserEmployee;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserEmployeeController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('user_employee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userEmployees = UserEmployee::with(['user', 'employee'])->get();

        return view('admin.userEmployees.index', compact('userEmployees'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_employee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        
        $users = User::Officers()->get()->mapWithKeys(function ($item) {

            return [$item['id'] => $item->title . ' (' . $item->username .')'  ];
        
        })->prepend(trans('global.pleaseSelect'), '');;


        $employees =  Employee::with('designation')->ProperlyFilled()->Active()->orderby('name','asc')->get()->mapWithKeys(function ($item) {
            return [$item['id'] =>  $item->AadharrPenNameDesig ];
            
        })->prepend(trans('global.pleaseSelect'), '');;

        return view('admin.userEmployees.create', compact('employees', 'users'));
    }

    public function store(StoreUserEmployeeRequest $request)
    {
        $userEmployee = UserEmployee::create($request->all());

        return redirect()->route('admin.user-employees.index');
    }

    public function edit(UserEmployee $userEmployee)
    {
        abort_if(Gate::denies('user_employee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $employees =  Employee::with('designation')->ProperlyFilled()->Active()->orderby('name','asc')->get()->mapWithKeys(function ($item) {
            return [$item['id'] =>  $item->AadharrPenNameDesig ];
            
        })->prepend(trans('global.pleaseSelect'), '');;

        $userEmployee->load('user', 'employee');

        return view('admin.userEmployees.edit', compact('employees', 'userEmployee', 'users'));
    }

    public function update(UpdateUserEmployeeRequest $request, UserEmployee $userEmployee)
    {
        $userEmployee->update($request->all());

        return redirect()->route('admin.user-employees.index');
    }

    public function show(UserEmployee $userEmployee)
    {
        abort_if(Gate::denies('user_employee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userEmployee->load('user', 'employee');

        return view('admin.userEmployees.show', compact('userEmployee'));
    }

    public function destroy(UserEmployee $userEmployee)
    {
        abort_if(Gate::denies('user_employee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userEmployee->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserEmployeeRequest $request)
    {
        $userEmployees = UserEmployee::find(request('ids'));

        foreach ($userEmployees as $userEmployee) {
            $userEmployee->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
