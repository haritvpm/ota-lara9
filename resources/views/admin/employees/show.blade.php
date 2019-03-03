@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.employees.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.employees.fields.name')</th>
                            <td field-key='name'>{{ $employee->srismt }}.{{ $employee->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.employees.fields.pen')</th>
                            <td field-key='pen'>{{ $employee->pen }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.employees.fields.designation')</th>
                            <td field-key='designation'>{{ $employee->designation->designation ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td field-key='category'>{{ $employee->category }}</td>
                        </tr>


                        
                                                
                        @if(\Auth::user()->isAdmin())
                        <tr>
                            <th>@lang('quickadmin.employees.fields.categories')</th>
                            <td field-key='categories'>{{ $employee->categories->category ?? '' }}</td>
                        </tr>
                        
                        <tr>
                            <th>Added by</th>
                            <td field-key='added_by'>{{ $employee->added_by->username ?? 'Admin' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.employees.fields.desig-display')</th>
                            <td field-key='desig_display'>{{ $employee->desig_display }}</td>
                        </tr>
                         <tr>
                            <th>Created</th>
                            <td field-key='desig_display'>{{ $employee->created_at }}</td>
                        </tr>
                        <tr>
                            <th>Updated</th>
                            <td field-key='desig_display'>{{ $employee->updated_at }}</td>
                        </tr>
                        @endif

                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.employees.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
