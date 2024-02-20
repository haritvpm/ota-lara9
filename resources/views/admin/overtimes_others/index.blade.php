@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.overtimes-others.title')</h3>
    @can('overtimes_other_create')
    <p>
        <a href="{{ route('admin.overtimes_others.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="card">
        

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable @can('overtimes_other_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('overtimes_other_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.overtimes-others.fields.pen')</th>
                        <!-- <th>@lang('quickadmin.overtimes-others.fields.designation')</th> -->
                        
                        <th>@lang('quickadmin.forms-others.fields.session')</th>
                        <th>@lang('quickadmin.forms-others.fields.creator')</th>
                        <th>@lang('quickadmin.forms-others.fields.owner')</th>
                        <th>@lang('quickadmin.forms-others.fields.form-no')</th>
                        <th>@lang('quickadmin.forms-others.fields.overtime-slot')</th>
                        <th>@lang('quickadmin.forms-others.fields.duty-date')</th>
                        <th>@lang('quickadmin.forms-others.fields.date-from')</th>
                        <th>@lang('quickadmin.forms-others.fields.date-to')</th>
                        <th>@lang('quickadmin.overtimes-others.fields.from')</th>
                        <th>@lang('quickadmin.overtimes-others.fields.to')</th>
                        <th>@lang('quickadmin.overtimes-others.fields.count')</th>
                        <th>@lang('quickadmin.overtimes-others.fields.worknature')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('overtimes_other_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.overtimes_others.mass_destroy') }}';
        @endcan
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.overtimes_others.index') !!}';
            window.dtDefaultOptions.columns = [
                @can('overtimes_other_delete')
                    {data: 'massDelete', name: 'id', searchable: false, sortable: false},
                @endcan
                {data: 'pen', name: 'pen'},
                // {data: 'designation', name: 'designation'},
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
                {data: 'worknature', name: 'worknature'},
                
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>
@endsection