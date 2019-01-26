@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.sessions.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>


        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.sessions.fields.name')</th>
                            <td field-key='name'>{{ $session->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.sessions.fields.kla')</th>
                            <td field-key='kla'>{{ $session->kla }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.sessions.fields.session')</th>
                            <td field-key='session'>{{ $session->session }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.sessions.fields.dataentry-allowed')</th>
                            <td field-key='dataentry_allowed'>{{ $session->dataentry_allowed }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.sessions.fields.show-in-datatable')</th>
                            <td field-key='show_in_datatable'>{{ $session->show_in_datatable }}</td>
                        </tr>
                         <tr>
                            <th>@lang('quickadmin.sessions.fields.exemption-entry')</th>
                            <td field-key='exemption_entry'>{{ $session->exemption_entry }}</td>
                        </tr>
                         <tr>
                            <th>Total Sittings</th>
                            <td>{{ $maxsittingdates}}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#calenders" aria-controls="calenders" role="tab" data-toggle="tab">Calenders</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="calenders">
<table class="table table-bordered table-striped {{ count($calenders) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.calenders.fields.date')</th>
                        <th>@lang('quickadmin.calenders.fields.day-type')</th>
                        <th>@lang('quickadmin.calenders.fields.session')</th>
                                                <th>&nbsp;</th>

        </tr>
    </thead>

    <tbody>
        @if (count($calenders) > 0)
            @foreach ($calenders as $calender)
                <tr data-entry-id="{{ $calender->id }}">
                    <td field-key='date'>{{ $calender->date }}</td>
                                <td field-key='day_type'>{{ $calender->day_type }}</td>
                                <td field-key='session'>{{ $calender->session->name or '' }}</td>
                                                                <td>
                                    @can('calender_view')
                                    <a href="{{ route('admin.calenders.show',[$calender->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('calender_edit')
                                    <a href="{{ route('admin.calenders.edit',[$calender->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('calender_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.calenders.destroy', $calender->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="8">@lang('quickadmin.qa_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
</div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.sessions.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>

           
   
         @can('session_delete')
            {!! Form::open(array(
            'style' => 'display: inline-block;',
            'method' => 'DELETE',
            'onsubmit' => "return confirm('This is not recommended until at least after 3 years. Are you sure?');",
            'route' => ['admin.sessions.destroy', $session->id])) !!}
            {!! Form::submit('Delete Session', array('class' => 'btn btn-danger')) !!}
            &nbsp;
            {!! Form::close() !!}
            @endcan
            
        </div>
    </div>
@stop
