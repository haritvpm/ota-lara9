@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.routing.title')</h3>
    @can('routing_create')
    <p>
        <a href="{{ route('admin.routings.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($routings) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                       
                        <th>@lang('quickadmin.routing.fields.user')</th>
                        <th>@lang('quickadmin.routing.fields.route')</th>
                        <th>@lang('quickadmin.routing.fields.last-forwarded-to')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($routings) > 0)
                        @foreach ($routings as $routing)
                            <tr data-entry-id="{{ $routing->id }}">
                              

                                <td field-key='user'>{{ $routing->user->username or '' }}</td>
                                <td field-key='route'>{{ $routing->route }}</td>
                                <td field-key='last_forwarded_to'>{{ $routing->last_forwarded_to }}</td>
                                                                <td>
                                    @can('routing_view')
                                    <a href="{{ route('admin.routings.show',[$routing->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('routing_edit')
                                    <a href="{{ route('admin.routings.edit',[$routing->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('routing_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.routings.destroy', $routing->id])) !!}
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
@stop

@section('javascript') 
    <script>
       

    </script>
@endsection