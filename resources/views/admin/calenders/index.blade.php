@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.calenders.title')</h3>
    @can('calender_create')
    <p>
        <a href="{{ route('admin.calenders.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable">
                <thead>
                    <tr>
                       

                        <th>@lang('quickadmin.calenders.fields.date')</th>
                        <th>@lang('quickadmin.calenders.fields.day-type')</th>
                        <th>Description</th>
                        <th>@lang('quickadmin.calenders.fields.session')</th>

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
            window.dtDefaultOptions.ajax = '{!! route('admin.calenders.index') !!}';
            window.dtDefaultOptions.columns = [
               
                {data: 'date', name: 'date'},
                {data: 'day_type', name: 'day_type'},
                {data: 'description', name: 'description'},
                {data: 'session.name', name: 'session.name'},
                
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>
@endsection