@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.overtimes.title')</h3>
    @can('overtime_create')
    <p>
        <a href="{{ route('admin.overtimes.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    <p>
        <!-- <a href="overtimes/fixx" class="btn btn-danger">Fix PEN field</a> -->
        <a href="overtimes/fixx" class="btn btn-danger">Add 10000</a>
        
    </p>

    @endcan

    

    <div class="card p-2">
        

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable @can('overtime_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('overtime_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.overtimes.fields.pen')</th>
                        <th>@lang('quickadmin.overtimes.fields.name')</th>
                        <th>@lang('quickadmin.overtimes.fields.designation')</th>
                        <th>@lang('quickadmin.forms.fields.session')</th>
                        <th>@lang('quickadmin.forms.fields.creator')</th>
                        <th>@lang('quickadmin.forms.fields.owner')</th>
                        <th>@lang('quickadmin.forms.fields.form-no')</th>
                        <th>@lang('quickadmin.forms.fields.overtime-slot')</th>
                        <th>@lang('quickadmin.forms.fields.duty-date')</th>
                        <th>@lang('quickadmin.forms.fields.date-from')</th>
                        <th>@lang('quickadmin.forms.fields.date-to')</th>
                        <th>@lang('quickadmin.overtimes.fields.from')</th>
                        <th>@lang('quickadmin.overtimes.fields.to')</th>
                        <th>@lang('quickadmin.overtimes.fields.count')</th>
                        <th>Rate</th>
                        <th>@lang('quickadmin.overtimes.fields.worknature')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('overtime_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.overtimes.mass_destroy') }}';
        @endcan
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.overtimes.index') !!}';
            window.dtDefaultOptions.columns = [
                @can('overtime_delete')
                    {data: 'massDelete', name: 'id', searchable: false, sortable: false},
                @endcan
                {data: 'pen', name: 'pen'},
                {data: 'name', name: 'name'},
                {data: 'designation', name: 'designation'},
                {data: 'form.session', name: 'form.session'},
                {data: 'form.creator', name: 'form.creator'},
                {data: 'form.owner', name: 'form.owner'},
                {data: 'form.id', name: 'form.id'},
                {data: 'form.overtime_slot', name: 'form.overtime_slot'},
                {data: 'form.duty_date', name: 'form.duty_date'},
                {data: 'form.date_from', name: 'form.date_from'},
                {data: 'form.date_to', name: 'form.date_to'},
                {data: 'from', name: 'from'},
                {data: 'to', name: 'to'},
                {data: 'count', name: 'count'},
                {data: 'rate', name: 'rate'},
                {data: 'worknature', name: 'worknature'},
                
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>
@endsection