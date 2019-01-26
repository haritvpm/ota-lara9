@extends('layouts.app')

    <style>
        [v-cloak] { display:none; }
    </style>

@section('content')

    <h3 class="page-title">Sitting-Days Form <small>(Other Dept)</small></h3>
    
    @if(count($sessions) > 0)

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="panel panel-default" id="app">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>

        <div class="panel-body">
        
            <?php
            $readonly = "";
            ?> 

            @include('admin.my_forms_others.form_sitting')
 
         </div>

        <div class="panel-footer">
            <a href="{{route('admin.my_forms_others.index')}}" class="btn btn-default">Cancel</a>
            <button class="btn btn-primary" @click.prevent="create" :disabled="isProcessing"><i class="fa fa-save"></i> Save<i  v-show="isProcessing" class="fa fa-spinner fa-spin"></i></button>


            <!-- <a href="#" class="pull-right" @click.prevent="loadall" >Load All</a> -->
             

        </div>

    </div>     

    @else
    
        Sorry, no sessions available for data entry
    
    @endif  

@stop


<!-- Flatpickr related files -->
<script type="text/javascript" src="{{ URL::asset('js/vue-sweetalert.js') }}"></script>

<!-- <script type="text/javascript" src="{{ URL::asset('js/flatpickr.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/vue-flatpickr.min.js') }}"></script> -->


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

    var urlajaxpen = "{{url('admin/employees_others/ajaxfind')}}"
    var urlformsubmit = "{{url('admin/my_forms_others/store_sitting')}}"
    var urlformsucessredirect = "{{url('admin/my_forms_others/')}}"
    var urlajaxpresets = "{{url('admin/employees_others/ajaxload')}}"


    var calenderdaysmap = {!! $data['calenderdaysmap'] !!};
  
    Vue.component('Multiselect', VueMultiselect.default);
    // Vue.component('flat-pickr', VueFlatpickr.default);
    Vue.component('date-picker', VueBootstrapDatetimePicker.default);
    Vue.use(VueSweetAlert.default)
    var calenderdays2 = {!! $data['calenderdays2'] !!};


    window._form = {
            session: latest_session,
            date_from: '',
            date_to: '',
            remarks : '',
            overtimes: [/* {
                pen: "",
                designation: "",
                from: def_time_start,
                to: def_time_end,
                worknature: "",
            } */]
        };
    </script>

  <script type="text/javascript" src="{{ URL::asset('js/form_sitting_other.1.js') }}"></script>

  

@stop
