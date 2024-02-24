@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.calenders.title')</h3>
    @can('calender_create')
    <p>
        <a href="{{ route('admin.calenders.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    <p>
   
MANUALENTRY: User can enter/edit punching time. <br>
AEBAS: Data from AEBAS fetched. Punching time will be autofetched for user form. No editing.<br>
AEBAS_FETCH_PENDING: Data is to be fetched from AEBAS. Users will not be able to create form for this day.<br>
NOPUNCHING: NIC server was down completely. No need to enter punching times for users<br>
Set empty/nothing for old sessions before punching impl

</p>

    <div class="">
        

        <div class="">
            <table class="table table-bordered table-striped ajaxTable" style="width:100%">
                <thead>
                    <tr>
                       

                        <th>@lang('quickadmin.calenders.fields.date')</th>
                        <th>@lang('quickadmin.calenders.fields.day-type')</th>
                        <th>Description</th>
                        <th>@lang('quickadmin.calenders.fields.session')</th>
                        <th>
                                        {{ trans('cruds.calender.fields.daylength_multiplier') }}
                                    </th> 
                                    <th>
                                    Punching
                                    </th>
                                    <th>
                                    Attendance
                                    </th>
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
                {data: 'daylength_multiplier', name: 'daylength_multiplier'},
                {data: 'punching_', name: 'punching_'},
                
                {data: 'punchin_actions', name: 'punchin_actions', searchable: false, sortable: false},
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>
@endsection