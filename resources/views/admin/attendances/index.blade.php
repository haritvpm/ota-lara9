@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')
 <style>
        [v-cloak] { display:none; }
</style>

@section('content')
    <h3 class="page-title hidden-print">@lang('quickadmin.attendance.title')</h3>
    <h3 class="page-title visible-print">Absentee Report</h3>


    @can('attendance_create')
    <!-- <p>
        <a href="{{ route('admin.attendances.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p> -->
    @endcan


     <div id="app">

        <div v-cloak>
                  
                <div class="alert alert-danger hidden-print " v-if="myerrors.length">
                    <ul>
                        <li v-for="error in myerrors">@{{ error }}</li>
                    </ul>
                </div>

                <div class="alert alert-success hidden-print" v-if="mysuccess.length">
                    <ul>
                        <li v-for="error in mysuccess">@{{ error }}</li>
                    </ul>
                </div>
            
            </div>

                  
        @can('attendance_create') 

        <div class="row  hidden-print" v-cloak >
           <div class="col-md-4 form-group">  
           Date:  <span class="text-danger" >@{{sitting_date_display}} </span>
            <date-picker v-model="sitting_date"
                    :config="configdate"
                    placeholder="Select date"
                    :required="true"
                    @dp-change="datechange"
                               
                    class="form-control">
            </date-picker>

            </div>
           
        
                  
            <div class="col-md-2 ">
            PEN, Name or Sl.No : 
             <input type="text" class="form-control" placeholder="Enter PEN" v-model="emppen" name="emppen" required autocomplete="off" >
            </div>
         
         </div>  

         <div class="row  hidden-print" v-cloak >
            <div class="col-md-6 form-group ">
             <table class="table table-bordered table-striped table-condensed">
                <thead v-show="list.length" >
                <tr style="font-size: 12px; font-weight: bold">
                    <th>Name</th>
                    <th>Designation</th>
                    
                    <th></th>
                </tr>
            </thead>

                <tbody>
                    <tr v-for="(item, index) in list">
                     <td >@{{ item.name }}</td>
                     <td >@{{ item.desig }} </td>
                     <td >
                        <button class="btn btn-xs btn-success" v-if=item.absent v-on:click="mark(index)">Mark Present</button>
                <button class="btn btn-xs btn-danger" v-else=item.absent v-on:click="mark(index)">Mark Absent</button>
                         
                     </td>
                    </tr>
                </tbody>
            </table>
          </div>  

        </div>
        
        @endcan

        @can('attendance_view') 
            
            <div class="panel panel-default">
            <div class="panel-heading">Report</div>

             <div class="panel-body">

            <form action="" method="get" id="filter" class="form-inline  hidden-print">
                <div class="form-group">
                    Session <select class="form-control" name="session">
                        @foreach($sessions as $sess)
                        @if($sess == \Request('session'))
                        <option selected>{{$sess}}</option>
                        @else
                        <option>{{$sess}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>

                @cannot('attendance_create') 
                <input  class="form-control" placeholder="Name/Pen" type="text" name = "namefilter" value="{{\Request('namefilter')}}" required minlength="3" rel="filter">
                @endcannot

                <input  class="form-control" placeholder="Date (dd-mm-yyyy)" type="text" name = "datefilter" value="{{\Request('datefilter')}}" rel="filter">
               
                <button type="submit"  class="btn btn-danger" rel="filter">Report</button>

            </form>


            @if(count($data_names))
            Session: {{\Request('session')}}
            <div class="row" v-cloak >
            <div class="col-md-6 form-group ">
             <table class="table table-bordered table-striped table-condensed">
                <thead >
                <tr style="font-size: 12px; font-weight: bold">
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Absent/ Late</th>
                </tr>
            </thead>

                <tbody>
                     @foreach ($data_desigs as  $key => $item)
                    <tr>
                     <td class="small">{{ $data_names[$key]}}</td>
                     <td class="small">{{ $item }} </td>
                     <td class="small">
                          {{  trim($data_dates[$key],", ") }}                   
                     </td>
                    </tr>
                    @endforeach 
                </tbody>
            </table>
          </div>
          </div>
           <button class="btn btn-primary hidden-print" onClick="window.print()">Print</button> 
            
          </div>
          </div>


          @endif 
          

           
         

        @endcan



        @if(\Auth::user()->isAdmin())
        <br>

        <form action="{{url('admin/attendances/download')}}" method="get" class="form-inline">
            Download Attendance Data 
            <div class="form-group">
                    Session <select class="form-control" name="session">
                        @foreach($sessions as $sess)
                        @if($sess == \Request('session'))
                        <option selected>{{$sess}}</option>
                        @else
                        <option>{{$sess}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            
            <button type="submit" class="btn btn-primary" rel="filter"><span class="glyphicon glyphicon-save"></span> </button>

        </form>

   
        @endif

    </div>  

    
<!-- 
    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped ajaxTable @can('attendance_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('attendance_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.attendance.fields.session')</th>
                        <th>@lang('quickadmin.attendance.fields.employee')</th>
                        <th>@lang('quickadmin.employees.fields.pen')</th>
                        <th>@lang('quickadmin.attendance.fields.dates-absent')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>-->
 @stop

@section('javascript') 
    <!-- <script>
        @can('attendance_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.attendances.mass_destroy') }}';
        @endcan
        $(document).ready(function () {
            window.dtDefaultOptions.ajax = '{!! route('admin.attendances.index') !!}';
            window.dtDefaultOptions.columns = [
                @can('attendance_delete')
                    {data: 'massDelete', name: 'id', searchable: false, sortable: false},
                @endcan
                {data: 'session.name', name: 'session.name'},
                {data: 'employee.name', name: 'employee.name'},
                {data: 'employee.pen', name: 'employee.pen'},
                {data: 'dates_absent', name: 'dates_absent'},
                
                {data: 'actions', name: 'actions', searchable: false, sortable: false}
            ];
            processAjaxTables();
        });
    </script> -->
<script type="text/javascript" src="{{ URL::asset('js/vue-sweetalert.js') }}"></script>


<script type="text/javascript">


var urlajaxpen = "{{url('admin/attendances/ajaxfindexactpenforattendace')}}"
var urlajaxpenupdate = "{{url('admin/attendances/ajaxupdateattendance')}}"
Vue.use(VueSweetAlert.default)
    
Vue.component('date-picker', VueBootstrapDatetimePicker.default);


var calenderdaysmap = {!! $data['calenderdaysmap'] !!};
var calenderdays2 = {!! $data['calenderdays2'] !!};

</script>


<script type="text/javascript" src="{{ URL::asset('js/attendance_index.js') }}"></script>


@endsection