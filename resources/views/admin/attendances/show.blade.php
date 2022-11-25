@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('quickadmin.attendance.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.attendances.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('quickadmin.attendance.fields.id') }}
                        </th>
                        <td>
                            {{ $attendance->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('quickadmin.attendance.fields.dates_present') }}
                        </th>
                        <td>
                            {{ $attendance->dates_present }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('quickadmin.attendance.fields.pen') }}
                        </th>
                        <td>
                            {{ $attendance->pen }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('quickadmin.attendance.fields.session') }}
                        </th>
                        <td>
                            {{ $attendance->session->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('quickadmin.attendance.fields.total') }}
                        </th>
                        <td>
                            {{ $attendance->total }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.attendances.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection