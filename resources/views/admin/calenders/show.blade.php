@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.calenders.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.calenders.fields.date')</th>
                            <td field-key='date'>{{ $calender->date }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.calenders.fields.day-type')</th>
                            <td field-key='day_type'>{{ $calender->day_type }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.calenders.fields.session')</th>
                            <td field-key='session'>{{ $calender->session->name ?? '' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.calenders.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
