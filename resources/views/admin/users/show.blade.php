@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.users.title')</h3>

    <div class="card">
        <div class="card-title">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.users.fields.name')</th>
                            <td field-key='name'>{{ $user->getAttributes()['name'] }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.users.fields.email')</th>
                            <td field-key='email'>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.users.fields.role')</th>
                            <td field-key='role'>{{ $user->role->title ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.users.fields.username')</th>
                            <td field-key='username'>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.users.fields.displayname')</th>
                            <td field-key='displayname'>{{ $user->displayname }}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#routing" aria-controls="routing" role="tab" data-toggle="tab">Routing</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="routing">
<table class="table table-bordered table-striped {{ count($routings) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.routing.fields.user')</th>
                        <th>@lang('quickadmin.users.fields.name')</th>
                        <th>@lang('quickadmin.routing.fields.route')</th>
                                                <th>&nbsp;</th>

        </tr>
    </thead>

    <tbody>
        @if (count($routings) > 0)
            @foreach ($routings as $routing)
                <tr data-entry-id="{{ $routing->id }}">
                    <td field-key='user'>{{ $routing->user->username ?? '' }}</td>
<td field-key='name'>{{ isset($routing->user) ? $routing->user->Title : '' }}</td>
                                <td field-key='route'>{{ $routing->route }}</td>
                                                                <td>
                                    @can('routing_view')
                                    <a href="{{ route('admin.routings.show',[$routing->id]) }}" class="btn btn-sm btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('routing_edit')
                                    <a href="{{ route('admin.routings.edit',[$routing->id]) }}" class="btn btn-sm btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan

                                    @can('user_edit')
                                     <a href="{{ route('admin.users.password_reset',[$user->id]) }}" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure to reset password of {{$user->username}}?')">Reset PW</a>
                                     
                                    @if( $user->isSectionOfficer() || $user->isDSorAbove() )
                                    <a href="{{ route('admin.users.create_dataentry',[$user->id]) }}" class="btn btn-default btn-sm">Create DE</a>
                                    @endif

                                    @endcan

                                    @can('routing_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.routings.destroy', $routing->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-sm btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
</div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.users.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
