<?php

namespace App\Http\Controllers\Api\V1;

use App\Designation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDesignationsRequest;
use App\Http\Requests\Admin\UpdateDesignationsRequest;
use Yajra\DataTables\DataTables;

class DesignationsController extends Controller
{
    public function index()
    {
        return Designation::all();
    }

    public function show($id)
    {
        return Designation::findOrFail($id);
    }

    public function update(UpdateDesignationsRequest $request, $id)
    {
        $designation = Designation::findOrFail($id);
        $designation->update($request->all());
        

        return $designation;
    }

    public function store(StoreDesignationsRequest $request)
    {
        $designation = Designation::create($request->all());
        

        return $designation;
    }

    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();
        return '';
    }
}
