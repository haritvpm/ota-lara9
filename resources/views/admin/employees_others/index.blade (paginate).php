@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.employees-other.title')</h3>
    @can('employees_other_create')
    <p>
        <a href="{{ route('admin.employees_others.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="card">
        

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($employees_others) > 0 ? 'datatable' : '' }} @can('employees_other_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('employees_other_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan
                        
                        <th>@lang('quickadmin.employees-other.fields.name')</th>
                        <th>@lang('quickadmin.employees-other.fields.pen')</th>
                        <th>@lang('quickadmin.employees-other.fields.designation')</th>
                        <!-- <th>@lang('quickadmin.employees-other.fields.department-idno')</th> -->
                       
                        @if(\Auth::user()->isAdmin())
                        <th>@lang('quickadmin.employees-other.fields.added-by')</th>
                        @endif
                       
                        <!-- <th>@lang('quickadmin.employees-other.fields.account-type')</th> -->
                    
                        <!-- <th>@lang('quickadmin.employees-other.fields.account-no')</th> -->
                         <th>@lang('quickadmin.employees-other.fields.mobile')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>

                 <tbody>
                    @if (count($employees_others) > 0)
                        @foreach ($employees_others as $employees_other)
                            <tr data-entry-id="{{ $employees_other->id }}">
                                @can('employees_other_delete')
                                    <td></td>
                                @endcan
                                
                                <td field-key='name'>{{ $employees_other->srismt }}. {{ $employees_other->name }}</td>
                                <td field-key='pen'>{{ $employees_other->pen }}</td>
                                <td field-key='designation'>{{ $employees_other->designation->designation ?? '' }}</td>
                                <!-- <td field-key='department_idno'>{{ $employees_other->department_idno }}</td> -->
                                @if(\Auth::user()->isAdmin())
                                <td field-key='added_by'>{{ $employees_other->added_by }}</td>
                                @endif
                                <!-- <td field-key='account_type'>{{ $employees_other->account_type }}</td> -->
                                <!-- <td field-key='ifsc'>{{ $employees_other->ifsc }}</td> -->
                                <!-- <td field-key='account_no'>{{ $employees_other->account_no }}</td> -->
                                <td field-key='mobile'>{{ $employees_other->mobile }}</td>
                                                                <td>
                                    @can('employees_other_view')
                                    <a href="{{ route('admin.employees_others.show',[$employees_other->id]) }}" class="btn btn-sm btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('employees_other_edit')
                                    <a href="{{ route('admin.employees_others.edit',[$employees_other->id]) }}" class="btn btn-sm btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('employees_other_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.employees_others.destroy', $employees_other->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-sm btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="15">@lang('quickadmin.qa_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>
    </div>
@stop
@section('javascript') 
    <script>
        @can('employees_other_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.employees_others.mass_destroy') }}';
        @endcan

    </script>
@endsection