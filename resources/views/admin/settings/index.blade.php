@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.settings.title')</h3>
    @can('setting_create')
    <p>
        <a href="{{ route('admin.settings.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan
    

    <div class="card">
        

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable ">
                <thead>
                    <tr>
                      
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
       
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.settings.index') !!}';
            window.dtDefaultOptions.columns = [
               
                {data: 'name', name: 'name'},
                {data: 'value', name: 'value'},
                
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>
@endsection