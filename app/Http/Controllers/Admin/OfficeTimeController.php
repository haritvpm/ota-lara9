<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOfficeTimeRequest;
use App\Http\Requests\StoreOfficeTimeRequest;
use App\Http\Requests\UpdateOfficeTimeRequest;
use App\OfficeTime;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OfficeTimeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('office_time_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $officeTimes = OfficeTime::all();

        return view('admin.officeTimes.index', compact('officeTimes'));
    }

    public function create()
    {
        abort_if(Gate::denies('office_time_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.officeTimes.create');
    }

    public function store(StoreOfficeTimeRequest $request)
    {
        $officeTime = OfficeTime::create($request->all());

        return redirect()->route('admin.office-times.index');
    }

    public function edit(OfficeTime $officeTime)
    {
        abort_if(Gate::denies('office_time_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.officeTimes.edit', compact('officeTime'));
    }

    public function update(UpdateOfficeTimeRequest $request, OfficeTime $officeTime)
    {
        $officeTime->update($request->all());

        return redirect()->route('admin.office-times.index');
    }

    public function show(OfficeTime $officeTime)
    {
        abort_if(Gate::denies('office_time_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.officeTimes.show', compact('officeTime'));
    }

    public function destroy(OfficeTime $officeTime)
    {
        abort_if(Gate::denies('office_time_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $officeTime->delete();

        return back();
    }

    public function massDestroy(MassDestroyOfficeTimeRequest $request)
    {
        $officeTimes = OfficeTime::find(request('ids'));

        foreach ($officeTimes as $officeTime) {
            $officeTime->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
