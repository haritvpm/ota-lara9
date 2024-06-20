<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyShiftTimeRequest;
use App\Http\Requests\StoreShiftTimeRequest;
use App\Http\Requests\UpdateShiftTimeRequest;
use App\ShiftTime;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShiftTimeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('shift_time_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shiftTimes = ShiftTime::all();

        return view('admin.shiftTimes.index', compact('shiftTimes'));
    }

    public function create()
    {
        abort_if(Gate::denies('shift_time_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.shiftTimes.create');
    }

    public function store(StoreShiftTimeRequest $request)
    {
        $shiftTime = ShiftTime::create($request->all());

        return redirect()->route('admin.shift-times.index');
    }

    public function edit(ShiftTime $shiftTime)
    {
        abort_if(Gate::denies('shift_time_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.shiftTimes.edit', compact('shiftTime'));
    }

    public function update(UpdateShiftTimeRequest $request, ShiftTime $shiftTime)
    {
        $shiftTime->update($request->all());

        return redirect()->route('admin.shift-times.index');
    }

    public function show(ShiftTime $shiftTime)
    {
        abort_if(Gate::denies('shift_time_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.shiftTimes.show', compact('shiftTime'));
    }

    public function destroy(ShiftTime $shiftTime)
    {
        abort_if(Gate::denies('shift_time_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shiftTime->delete();

        return back();
    }

    public function massDestroy(MassDestroyShiftTimeRequest $request)
    {
        $shiftTimes = ShiftTime::find(request('ids'));

        foreach ($shiftTimes as $shiftTime) {
            $shiftTime->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
