<?php

namespace App\Http\Controllers\Api\V1;

use App\Overtime;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOvertimesRequest;
use App\Http\Requests\Admin\UpdateOvertimesRequest;
use Yajra\DataTables\DataTables;

class OvertimesController extends Controller
{
    public function index()
    {
        return Overtime::all();
    }

    public function show($id)
    {
        return Overtime::findOrFail($id);
    }

    public function update(UpdateOvertimesRequest $request, $id)
    {
        $overtime = Overtime::findOrFail($id);
        $overtime->update($request->all());
        

        return $overtime;
    }

    public function store(StoreOvertimesRequest $request)
    {
        $overtime = Overtime::create($request->all());
        

        return $overtime;
    }

    public function destroy($id)
    {
        $overtime = Overtime::findOrFail($id);
        $overtime->delete();
        return '';
    }
}
