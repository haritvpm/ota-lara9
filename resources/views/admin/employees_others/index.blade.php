@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.employees-other.title')</h3>
    @can('employees_other_create')
    <p>
        <a href="{{ route('admin.employees_others.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="">
      
        <div class="">
            <table class="table table-bordered table-striped ajaxTable @can('employees_other_delete') dt-select @endcan" style="width: 100%;">
                <thead>
                    <tr>
                        @can('employees_other_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th></th>
                        <th>@lang('quickadmin.employees-other.fields.name')</th>
                        <th>@lang('quickadmin.employees-other.fields.pen')</th>
                        <th>@lang('quickadmin.employees-other.fields.designation')</th>
                       <!--  <th>@lang('quickadmin.employees-other.fields.department-idno')</th> -->
                       @if(\Auth::user()->isAdmin())
                        <!-- <th>ID</th> -->
                        <th>@lang('quickadmin.employees-other.fields.added-by')</th>
                       @endif
                        <!-- <th>@lang('quickadmin.employees-other.fields.account-type')</th> -->
                        <!-- <th>IFSC</th> -->
                        <!-- <th>@lang('quickadmin.employees-other.fields.account-no')</th> -->
                         <th>@lang('quickadmin.employees-other.fields.mobile')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Delete old-->

    @if($session_array)
    <form action="employees_others/clearold" method="get" id="filter" class="form-inline" onsubmit="return confirm('Do you want to delete employees ?');">

        Delete all employees not used in the last three sessions ({{$session_array}})
        <!--  <br><div class="form-group">                                
        Designation<select class="form-control" name="designation_todel">
                        
                @foreach($designations_others_todel as $val => $desig)
                @if($val == \Request('designation_todel'))
                <option value="{{$val}}" selected>{{$desig}}</option>
                @else
                     <option value="{{$val}}">{{$desig}}</option>
                @endif
                @endforeach
                              
        </select>
        </div> -->

        <input type="hidden" name="sessions_toignore" value={{$session_array}}>
                             
        <button type="submit" class="btn btn btn-danger">Delete</button>
         
        
    </form>
    @endif


@stop

@section('javascript') 
    <script>
        @can('employees_other_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.employees_others.mass_destroy') }}';
        @endcan
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.employees_others.index') !!}';
            window.dtDefaultOptions.columns = [
                @can('employees_other_delete')
                    {data: 'massDelete', name: 'id', searchable: false, sortable: false},
                @endcan
                {data: 'srismt', name: 'srismt'},
                {data: 'name', name: 'name'},
                {data: 'pen', name: 'pen'},
                {data: 'designation.designation', name: 'designation.designation'},
                // {data: 'department_idno', name: 'department_idno'},
                @if(\Auth::user()->isAdmin())
                // {data: 'id', name: 'id'},       
                {data: 'added_by', name: 'added_by'},
                @endif
                // {data: 'account_type', name: 'account_type'},
               
                // {data: 'account_no', name: 'account_no'},
                {data: 'mobile', name: 'mobile'},
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>

@endsection