@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.forms-others.title')</h3>
    @can('forms_other_create')
    <p>
        <a href="{{ route('admin.forms_others.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable @can('forms_other_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('forms_other_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.forms-others.fields.session')</th>
                        <th>@lang('quickadmin.forms-others.fields.creator')</th>
                        <th>@lang('quickadmin.forms-others.fields.owner')</th>
                        <th>@lang('quickadmin.forms-others.fields.form-no')</th>
                        <th>@lang('quickadmin.forms-others.fields.overtime-slot')</th>
                        <th>@lang('quickadmin.forms-others.fields.duty-date')</th>
                        <th>@lang('quickadmin.forms-others.fields.date-from')</th>
                        <th>@lang('quickadmin.forms-others.fields.date-to')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('forms_other_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.forms_others.mass_destroy') }}';
        @endcan
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.forms_others.index') !!}';
            window.dtDefaultOptions.columns = [
                @can('forms_other_delete')
                    {data: 'massDelete', name: 'id', searchable: false, sortable: false},
                @endcan
                {data: 'session', name: 'session'},
                {data: 'creator', name: 'creator'},
                {data: 'owner', name: 'owner'},
                {data: 'id', name: 'id'},
                {data: 'overtime_slot', name: 'overtime_slot'},
                {data: 'duty_date', name: 'duty_date'},
                {data: 'date_from', name: 'date_from'},
                {data: 'date_to', name: 'date_to'},
                
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>
@endsection