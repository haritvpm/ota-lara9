@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.settings.title')</h3>
    @can('setting_create')
    <p>
        <a href="{{ route('admin.settings.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan
    

    <div class="card p-2">
        

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable @can('setting_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('setting_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.settings.fields.name')</th>
                        <th>@lang('quickadmin.settings.fields.value')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>




@stop

@section('javascript') 
    <script>
        @can('setting_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.settings.mass_destroy') }}';
        @endcan
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.settings.index') !!}';
            window.dtDefaultOptions.columns = [
                @can('setting_delete')
                    {data: 'massDelete', name: 'id', searchable: false, sortable: false},
                @endcan
                {data: 'name', name: 'name'},
                {data: 'value', name: 'value'},
                
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>
@endsection