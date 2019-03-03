@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.attendance.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.attendance.fields.session')</th>
                            <td field-key='session'>{{ $attendance->session->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.attendance.fields.employee')</th>
                            <td field-key='employee'>{{ $attendance->employee->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.attendance.fields.dates-absent')</th>
                            <td field-key='dates_absent'>{!! $attendance->dates_absent !!}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.attendances.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
