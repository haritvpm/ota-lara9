@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.employees-other.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.employees-other.fields.srismt')</th>
                            <td field-key='srismt'>{{ $employees_other->srismt }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.employees-other.fields.name')</th>
                            <td field-key='name'>{{ $employees_other->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.employees-other.fields.pen')</th>
                            <td field-key='pen'>{{ $employees_other->pen }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.employees-other.fields.designation')</th>
                            <td field-key='designation'>{{ $employees_other->designation->designation ?? '' }}</td>
                        </tr>
                        <!-- <tr>
                            <th>@lang('quickadmin.employees-other.fields.department-idno')</th>
                            <td field-key='department_idno'>{{ $employees_other->department_idno }}</td>
                        </tr> -->
                        
                        @if(\Auth::user()->isAdmin())
                        <tr>
                            <th>@lang('quickadmin.employees-other.fields.added-by')</th>
                            <td field-key='added_by'>{{ $employees_other->added_by->username ?? '' }}</td>
                        </tr>
                        @endif

                        <tr>
                            <th>@lang('quickadmin.employees-other.fields.account-type')</th>
                            <td field-key='account_type'>{{ $employees_other->account_type }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.employees-other.fields.ifsc')</th>
                            <td field-key='ifsc'>{{ $employees_other->ifsc }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.employees-other.fields.account-no')</th>
                            <td field-key='account_no'>{{ $employees_other->account_no }}</td>
                        </tr>
                         <tr>
                            <th>@lang('quickadmin.employees-other.fields.mobile')</th>
                            <td field-key='mobile'>{{ $employees_other->mobile }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.employees_others.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
