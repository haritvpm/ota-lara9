<?php

namespace App\Http\Controllers\Api\V1;

use App\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeesRequest;
use App\Http\Requests\Admin\UpdateEmployeesRequest;
use Yajra\DataTables\DataTables;

class EmployeesController extends Controller
{
    /*
    public function ajaxfind($search)
    {
        return Employee::where('name','like',$search);
        /*
        $emp = Employee::all(['pen','name'])->first();

        return json_encode($emp);*/

    }*/

    public function index()
    {
        return Employee::all();
    }

    public function show($id)
    {
        return Employee::findOrFail($id);
    }


    public function update(UpdateEmployeesRequest $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update($request->all());
        

        return $employee;
    }

    public function store(StoreEmployeesRequest $request)
    {
        $employee = Employee::create($request->all());
        

        return $employee;
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return '';
    }
}
