@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.govtCalendar.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.govt-calendars.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.id') }}
                        </th>
                        <td>
                            {{ $govtCalendar->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.date') }}
                        </th>
                        <td>
                            {{ $govtCalendar->date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.govtholidaystatus') }}
                        </th>
                        <td>
                            {{ $govtCalendar->govtholidaystatus }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.restrictedholidaystatus') }}
                        </th>
                        <td>
                            {{ $govtCalendar->restrictedholidaystatus }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.bankholidaystatus') }}
                        </th>
                        <td>
                            {{ $govtCalendar->bankholidaystatus }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.festivallist') }}
                        </th>
                        <td>
                            {{ $govtCalendar->festivallist }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.success_attendance_fetched') }}
                        </th>
                        <td>
                            {{ $govtCalendar->success_attendance_fetched }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.success_attendance_lastfetchtime') }}
                        </th>
                        <td>
                            {{ $govtCalendar->success_attendance_lastfetchtime }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.success_attendance_rows_fetched') }}
                        </th>
                        <td>
                            {{ $govtCalendar->success_attendance_rows_fetched }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.attendance_today_trace_fetched') }}
                        </th>
                        <td>
                            {{ $govtCalendar->attendance_today_trace_fetched }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.attendancetodaytrace_lastfetchtime') }}
                        </th>
                        <td>
                            {{ $govtCalendar->attendancetodaytrace_lastfetchtime }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.govtCalendar.fields.attendance_today_trace_rows_fetched') }}
                        </th>
                        <td>
                            {{ $govtCalendar->attendance_today_trace_rows_fetched }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.govt-calendars.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection