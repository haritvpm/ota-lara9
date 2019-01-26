@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.exemptions.title')</h3>
    @can('exemption_create')
    <p>
        <a href="{{ route('admin.exemptions.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable @can('exemption_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('exemption_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.exemptions.fields.pen')</th>
                        <th>@lang('quickadmin.exemptions.fields.name')</th>
                        <th>@lang('quickadmin.exemptions.fields.designation')</th>
                        <th>@lang('quickadmin.exemptions.fields.worknature')</th>
                        <th>@lang('quickadmin.exemptions.fields.exemptionform')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('exemption_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.exemptions.mass_destroy') }}';
        @endcan
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.exemptions.index') !!}';
            window.dtDefaultOptions.columns = [@can('exemption_delete')
                    {data: 'massDelete', name: 'id', searchable: false, sortable: false},
                @endcan
                {data: 'pen', name: 'pen'},
                {data: 'name', name: 'name'},
                {data: 'designation', name: 'designation'},
                {data: 'worknature', name: 'worknature'},
                {data: 'form.session', name: 'form.session'},
                
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>
@endsection