<?php

namespace App\Http\Controllers\Api\V1;

use App\Calender;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCalendersRequest;
use App\Http\Requests\Admin\UpdateCalendersRequest;
use Yajra\DataTables\DataTables;

class CalendersController extends Controller
{
    public function index()
    {
        return Calender::all();
    }

    public function show($id)
    {
        return Calender::findOrFail($id);
    }

    public function update(UpdateCalendersRequest $request, $id)
    {
        $calender = Calender::findOrFail($id);
        $calender->update($request->all());
        

        return $calender;
    }

    public function store(StoreCalendersRequest $request)
    {
        $calender = Calender::create($request->all());
        

        return $calender;
    }

    public function destroy($id)
    {
        $calender = Calender::findOrFail($id);
        $calender->delete();
        return '';
    }
}
