@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.forms.title')</h3>
    
    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable @can('form_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('form_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.forms.fields.session')</th>
                        <th>@lang('quickadmin.forms.fields.creator')</th>
                        <th>@lang('quickadmin.forms.fields.owner')</th>
                        <th>No.</th>
                        <th>@lang('quickadmin.forms.fields.overtime-slot')</th>
                        <th>@lang('quickadmin.forms.fields.duty-date')</th>
                        <th>@lang('quickadmin.forms.fields.date-from')</th>
                        <th>@lang('quickadmin.forms.fields.date-to')</th>
                        <th>Submitted By</th>
                       
                        <th>Submitted On</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>


    @if(count($session_todelete_array)>0)
    <p>Clear Old Forms created before {{$date_ago->toFormattedDateString()}}<br>
       This should only be done after all audit paras if any regarding this session have been dropped
    </p>
    <form action="forms/clearoldforms" onsubmit="return confirm('Do you really want to delete all forms?');" method="get" id="filter" class="form-inline">

        <div class="form-group">
         <select class="form-control" name="session_todelete">
                
                @foreach($session_todelete_array as $session)
                @if($session == \Request('session_todelete'))
                   <option selected>{{$session}}</option>
                @else
                    <option>{{$session}}</option>
                @endif
                @endforeach
                        
        </select>
        </div>

        <button type="submit" class="btn btn-danger" name="delbtn" value="view">Dump All Forms</button>
        <button type="submit" class="btn btn-danger" name="delbtn" value="del">Delete All Forms</button>
                         
    </form>
    @endif


@stop

@section('javascript') 
    <script>
        @can('form_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.forms.mass_destroy') }}';
        @endcan
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.forms.index') !!}';
            window.dtDefaultOptions.columns = [
                @can('form_delete')
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
                {data: 'submitted_by', name: 'submitted_by'},
               
                {data: 'submitted_on', name: 'submitted_on'},
                
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
                      

            processAjaxTables();
        });
    </script>
@endsection