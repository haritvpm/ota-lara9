@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.routing.title')</h3>

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.routing.fields.user')</th>
                            <td field-key='user'>{{ $routing->user->username ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.routing.fields.route')</th>
                            <td field-key='route'>{{ $routing->route }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.routing.fields.last-forwarded-to')</th>
                            <td field-key='last_forwarded_to'>{{ $routing->last_forwarded_to }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.routings.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
