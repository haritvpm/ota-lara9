<?php

namespace App\Http\Controllers\Admin;

use App\GovtCalendar;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateGovtCalendarRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GovtCalendarController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('govt_calendar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $govtCalendars = GovtCalendar::all();

        return view('admin.govtCalendars.index', compact('govtCalendars'));
    }

    public function edit(GovtCalendar $govtCalendar)
    {
        abort_if(Gate::denies('govt_calendar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.govtCalendars.edit', compact('govtCalendar'));
    }

    public function update(UpdateGovtCalendarRequest $request, GovtCalendar $govtCalendar)
    {
        $govtCalendar->update($request->all());

        return redirect()->route('admin.govt-calendars.index');
    }

    public function show(GovtCalendar $govtCalendar)
    {
        abort_if(Gate::denies('govt_calendar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.govtCalendars.show', compact('govtCalendar'));
    }
}
