@extends('layouts.app')

    <style>
        [v-cloak] { display:none; }
    </style>

@section('content')

    <h4 class="page-title">Punching Form</h4>
        

    @if(count($sessions) > 0)

    <div class="panel panel-default" id="app">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>

        <div class="panel-body">
        
            @include('admin.punchings.form')
 
         </div>

        <div class="panel-footer">
            <a href="{{route('admin.punchings.index')}}" class="btn btn-default">Cancel</a>
            <button class="btn btn-primary" @click.prevent="create" :disabled="isProcessing"><i class="fa fa-save"></i> Save <i  v-show="isProcessing" class="fa fa-spinner fa-spin"></i></button>

     
            
        </div>

    </div>     

    @else
    
        Sorry, no sessions available for data entry
         <a href="{{route('admin.punchings.index')}}" class="btn btn-primary">OK</a>
         
    @endif  

@stop


<!-- <script type="text/javascript" src="{{ URL::asset('js/flatpickr.min.js') }}"></script> -->
<!-- <script type="text/javascript" src="{{ URL::asset('js/vue-flatpickr.min.js') }}"></script> -->
<script type="text/javascript" src="{{ URL::asset('js/vue-sweetalert.js') }}"></script>


@section('javascript')
    @parent

    <!-- <script>
            $('.date').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            });
        </script> -->
    <!--   
    <script>
        var oldFormData = {
        old: "{{ json_encode(Session::getOldInput()) }}",
        oldname: "{{ json_encode( old('name') ) }}",
        
        //...
        }
    </script>
    -->

    <script type="text/javascript">

    var urlajaxpen = "{{url('admin/employees/ajaxfind')}}"
    var urlformsubmit = "{{url('admin/punchings/')}}"
    var urlformsucessredirect = "{{url('admin/punchings/')}}"
  //  var urlpresetsubmit = "{{url('admin/presets/')}}"
   // var urlajaxpresets = "{{url('admin/presets/ajaxfind')}}"
    
    

    var calenderdaysmap = {!! $data['calenderdaysmap'] !!};
    var designations = {!! $data['designations'] !!};
    Vue.component('Multiselect', VueMultiselect.default);
    // Vue.component('flat-pickr', VueFlatpickr.default);
    Vue.use(VueSweetAlert.default)
    
    Vue.component('date-picker', VueBootstrapDatetimePicker.default);

    var calenderdays2 = {!! $data['calenderdays2'] !!};

    
   

    window._form = {
            session: latest_session,
            pen: "",
          //  aadhaarid: '',
            remarks : '',
            punchings: [/* {
                date: "",
                
                punchin: "",
                punchout: "",
                
            } */]
        };
    </script>

  <script type="text/javascript" src="{{ URL::asset('js/punching.js') }}"></script>





@stop
