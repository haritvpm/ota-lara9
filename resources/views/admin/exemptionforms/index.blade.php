@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.exemptionforms.title')</h3>
    @can('exemptionform_create')
    <p>
        <a href="{{ route('admin.exemptionforms.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable @can('exemptionform_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('exemptionform_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.exemptionforms.fields.session')</th>
                        <th>@lang('quickadmin.exemptionforms.fields.creator')</th>
                        <th>@lang('quickadmin.exemptionforms.fields.owner')</th>
                        <th>@lang('quickadmin.exemptionforms.fields.form-no')</th>
                        <th>@lang('quickadmin.exemptionforms.fields.submitted-names')</th>
                        <th>@lang('quickadmin.exemptionforms.fields.submitted-by')</th>
                        <th>@lang('quickadmin.exemptionforms.fields.submitted-on')</th>
                        <th>@lang('quickadmin.exemptionforms.fields.remarks')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('exemptionform_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.exemptionforms.mass_destroy') }}';
        @endcan
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.exemptionforms.index') !!}';
            window.dtDefaultOptions.columns = [@can('exemptionform_delete')
                    {data: 'massDelete', name: 'id', searchable: false, sortable: false},
                @endcan{data: 'session', name: 'session'},
                {data: 'creator', name: 'creator'},
                {data: 'owner', name: 'owner'},
                {data: 'form_no', name: 'form_no'},
                {data: 'submitted_names', name: 'submitted_names'},
                {data: 'submitted_by', name: 'submitted_by'},
                {data: 'submitted_on', name: 'submitted_on'},
                {data: 'remarks', name: 'remarks'},
                
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>
@endsection