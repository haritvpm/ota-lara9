@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.designations.title')</h3>

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.designations.fields.designation')</th>
                            <td field-key='designation'>{{ $designation->designation }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.designations.fields.rate')</th>
                            <td field-key='rate'>{{ $designation->rate }}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#employees" aria-controls="employees" role="tab" data-toggle="tab">Employees</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="employees">
<table class="table table-bordered table-striped {{ count($employees) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('quickadmin.employees.fields.name')</th>
                        <th>@lang('quickadmin.employees.fields.pen')</th>
                        <th>@lang('quickadmin.employees.fields.designation')</th>
                                                <th>&nbsp;</th>

        </tr>
    </thead>

    <tbody>
        @if (count($employees) > 0)
            @foreach ($employees as $employee)
                <tr data-entry-id="{{ $employee->id }}">
                    <td field-key='name'>{{ $employee->name }}</td>
                                <td field-key='pen'>{{ $employee->pen }}</td>
                                <td field-key='designation'>{{ $employee->designation->designation ?? '' }}</td>
                                                                <td>
                                    @can('employee_view')
                                    <a href="{{ route('admin.employees.show',[$employee->id]) }}" class="btn btn-sm btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('employee_edit')
                                    <a href="{{ route('admin.employees.edit',[$employee->id]) }}" class="btn btn-sm btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('employee_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.employees.destroy', $employee->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-sm btn-danger')) !!}
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

            <a href="{{ route('admin.designations.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
