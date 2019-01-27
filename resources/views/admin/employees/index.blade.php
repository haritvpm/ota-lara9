@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')

    <h3 class="page-title">@lang('quickadmin.employees.title')</h3>
    @can('employee_create')
    <p>

        <a href="{{ route('admin.employees.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>

         <!-- @can('attendance_create')
         <a href="{{ route('admin.employees.create_temppen')}}" class="btn btn-success">Add New Employee with Temp PEN</a>
         @endcan -->

        @if(\Auth::user()->isAdmin())
        <!--  <a href="#" class="btn btn-warning" style="margin-left:5px;" data-toggle="modal" data-target="#myModal">@lang('quickadmin.qa_csvImport')</a>
        @include('csvImport.modal', ['model' => 'Employee']) -->


         <a href="{{ route('admin.employees.sparksync') }}" class="btn btn-warning">Spark Sync</a>
         &nbsp;
        Employees with no category set : {{$empwithnocategory}}<br>
        Right now, category and desig display in intranet do not affect EXCEL. 

        @endif
        
    </p>
    @endcan



 @if(!\Auth::user()->isAudit())
 
 <h5>Change Designation of Employee on Promotion/Reversion</h5>
     <div   id="app">
            
            <form action="{{url('admin/employees/updatedesig')}}" method="POST" id="filter" class="form-inline" onsubmit="return confirm('Do you want to change the designation of this employee?');">  
           
                    
            <div class="form-group">
             <input type="text" class="form-control" placeholder="Enter PEN" v-model="emppen" name="emppen" required>
            </div>
            <div class="form-group">
                 @{{empname}} 
            
             <div class="form-group">
             <!-- <input type="text" class="form-control" v-model="empdesig"> -->
             <select required name="empdesig"  v-model="empdesig" class="form-control" > 
                 <option v-for="option in designations">
                @{{ option }}
              </option>   
            </select>
             </div>
               <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
               
 
             <button type="submit" class="btn btn-default" >Update Designation</button>
             </div>
            </form>
          
    </div>
@endif
 <br>
<!-- prevent user changing employee details -->
    <!-- if(\Auth::user()->isAdmin()) -->

    <div class="panel panel-default">
        <div class="panel-heading">
            List of employees with no PEN
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable"> 
                <!-- inside above table class @can('employee_delete') dt-select @endcan -->
                <thead>
                    <tr>
                      <!--   @can('employee_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan -->
                        <!-- <th>@lang('quickadmin.employees.fields.srismt')</th> -->
                        <th>@lang('quickadmin.employees.fields.name')</th>
                        <!-- <th>@lang('quickadmin.employees.fields.name-mal')</th> -->
                        <th>@lang('quickadmin.employees.fields.pen')</th>
                        <th style="text-align:center;"><span class="glyphicon glyphicon-search"></span></th>
                        <th>@lang('quickadmin.employees.fields.designation')</th>
                        <th>Type</th>
                        @if(\Auth::user()->isAdmin())
                        <th>ID</th>
                        
                        <th>@lang('quickadmin.employees.fields.added-by')</th>
                        <th>@lang('quickadmin.employees.fields.categories')</th>
                        <th>@lang('quickadmin.employees.fields.desig-display')</th>
                        <!-- <th>Created</th> -->
<!--                         <th>Updated</th> -->                        
                         @endif
                        <th>&nbsp;</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
 <!-- endif -->




<!-- Employees -->

 @if(\Auth::user()->isAdmin())
    <br>

        <form action="{{url('admin/employees/download_emp')}}" method="get" class="form-inline">
            Download Employee Data 
            <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
            <button type="submit" class="btn btn-primary" rel="filter"><span class="glyphicon glyphicon-save"></span> </button>

        </form>


   <br>
    
    <form action="{{url('admin/employees/clearold')}}" method="get" class="form-inline">
       List unused Employees
        <button type="submit" class="btn btn-danger" >Dump</button>
        <!-- <button type="submit" class="btn btn-danger" name="delbtn" value="del">Delete All Forms</button> -->
                         
    </form>
     

   
@endif



@stop

@section('javascript') 
    <script>
      /*  @can('employee_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.employees.mass_destroy') }}';
        @endcan*/
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.employees.index') !!}';
            window.dtDefaultOptions.columns = [
             /*   @can('employee_delete')
                    {data: 'massDelete', name: 'id', searchable: false, sortable: false},
                @endcan*/
                // {data: 'srismt', name: 'srismt'},
                
                {data: 'name', name: 'name'},
                //{data: 'name_mal', name: 'name_mal'},
                {data: 'pen', name: 'pen'},
                {data: 'search', name: 'search'},
                {data: 'designation.designation', name: 'designation.designation'},
                {data: 'category', name: 'category'},
                @if(\Auth::user()->isAdmin())
                {data: 'id', name: 'id'},
                {data: 'added_by', name: 'added_by'},
                {data: 'categories.category', name: 'categories.category'},
                {data: 'desig_display', name: 'desig_display'},
                //{data: 'created_at', name: 'created_at'},
               // {data: 'updated_at', name: 'created_at'},
               
                @endif
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script>

<script type="text/javascript">

//var urlajaxpen = "{{url('admin/employees/ajaxfind')}}"
var urlajaxpen = "{{url('admin/employees/ajaxfindexactpen')}}"

var designations = {!! $data['designations'] !!};

    
</script>


 <script type="text/javascript" src="{{ URL::asset('js/employee_index.js') }}"></script>

@endsection