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
         <button class="btn btn-primary" data-toggle="modal" data-target="#csvImportModal">
                 Import AttendanceId from AEBAS Empl CSV
            </button>
            @include('csvImport.modalfortrait', ['model' => 'Employee', 'route' => 'admin.employees.parseAadhaarCsvImport'])
         <hr>
         <br>
@if(Session::has('empls_not_found'))
    <table class="table table-condensed">
    <thead>
      <tr>
        <th>PEN/org_emp_code</th>
        <th>AttendanceId</th>
        <th>Name</th>
        <th>Designation</th>
      </tr>
    </thead>
    <tbody>
        @foreach (session()->get('empls_not_found') as $aebasemp )
        <tr class="danger">
        <td>{{$aebasemp['pen']}}</td>
        <td>{{$aebasemp['aadhaarid']}}</td>
        <td>{{$aebasemp['name']}}</td>
        <td>{{$aebasemp['designation']}}</td> <td>{{$aebasemp['section']}}</td>
    
        @endforeach
    
    </tbody>
  </table>
  @endif
        Employees with no category set : {{$empwithnocategory}}<br>
        Employees with redundant Desig Display : {{$empswithduplicatedesigdisplay}}<br>
        <hr>

        @endif
        
    </p>
    @endcan



 @if(\Auth::user()->isAdmin())
 
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
  @if(\Auth::user()->isAdmin())

    <div class="">
       
        <div class="table-responsive">
            <table class="table table-borderless table-striped ajaxTable "> 
             
                <thead>
                    <tr>
                  
                        <th>@lang('quickadmin.employees.fields.name')</th>
   
                        <th>@lang('quickadmin.employees.fields.pen')</th>
                        <th>@lang('quickadmin.employees.fields.aadhaarid')</th>
                        <!-- <th style="text-align:center;"><i class="fas fa-fw  fa-search"></i></th> -->
                        <th>@lang('quickadmin.employees.fields.designation')</th>
                        <th>Type</th>
                        @if(\Auth::user()->isAdmin())
                        <th>ID</th>
                        
                        <th>@lang('quickadmin.employees.fields.added-by')</th>
                        <th>Excel Category</th>
                        <th>
                                    {{ trans('cruds.employee.fields.punching') }}
                           </th>
                        <th>@lang('quickadmin.employees.fields.desig-display')</th>
                   
                         @endif
                        <th>&nbsp;</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
  @endif




<!-- Employees -->

 @if(\Auth::user()->isAdmin())
    <br>

        <form action="{{url('admin/employees/download_emp')}}" method="get" class="form-inline">
            Employee Data 
            <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
            <button type="submit" class="btn btn-primary" rel="filter"><i class="fas fa-fw  fa-download"></i>Download </button>

        </form>


   <br>
   
  
   <form action="{{url('admin/employees/findinvalidpen')}}" method="get" class="form-inline">
       Employees with invalid PEN for current session
        <button type="submit" class="btn btn-warning" >List</button>
        <!-- <button type="submit" class="btn btn-danger" name="delbtn" value="del">Delete All Forms</button> -->
                         
    </form>
 
    <!-- <form action="{{url('admin/employees/clearold')}}" method="get" class="form-inline">
       List unused Employees
        <button type="submit" class="btn btn-danger" >Dump</button>
                                 
    </form> -->
     
    
   
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
                {data: 'aadhaarid', name: 'aadhaarid'},
                //{data: 'search', name: 'search'},
                {data: 'designation_designation', name: 'designation.designation'},
                {data: 'category', name: 'category'},
                @if(\Auth::user()->isAdmin())
                {data: 'id', name: 'id'},
                {data: 'added_by', name: 'added_by'},
                {data: 'categories.category', name: 'categories.category'},
                { data: 'punching', name: 'punching' },
                {data: 'desig_display', name: 'desig_display'},
            //    {data: 'created_at', name: 'created_at'},
              // {data: 'updated_at', name: 'updated_at'},
               
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