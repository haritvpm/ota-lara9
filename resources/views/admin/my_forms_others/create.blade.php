@extends('layouts.app')

    <style>
        [v-cloak] { display:none; }
    </style>

@section('content')

    <h3 class="page-title">Duty Form <small>(Other Dept)</small></h3>
    
    @if(count($sessions) > 0)



    <div class="card" id="app">
        <div class="card-title">
            @lang('quickadmin.qa_create')
        </div>

        <div class="card-body">
        

            <?php
            $readonly = "";
            ?> 

            @include('admin.my_forms_others.form')
 
         </div>

        <div class="card-footer">
            <a href="{{route('admin.my_forms_others.index')}}" class="btn btn-default">Cancel</a>
            <button class="btn btn-primary" @click.prevent="create" :disabled="isProcessing"><i class="fa fa-save"></i> Save <i  v-show="isProcessing" class="fa fa-spinner fa-spin"></i></button>



             <!-- <a href="#" class="pull-right" v-show ="form.overtimes.length" @click="savepreset" >Save as Preset</a>
             <span v-show ="form.overtimes.length" class="pull-right">&nbsp;|&nbsp;</span> -->
             <!-- <a href="#" class="pull-right" @click.prevent="loadall" >Load All</a> -->
             <!-- <span v-show ="form.overtimes.length>1" class="pull-right">&nbsp;|&nbsp;</span>
            <a href="#" class="pull-right" v-show ="form.overtimes.length>0" @click="removeunchecked" >Remove Unchecked</a> -->
        </div>

    </div>     

     @else
    
        Sorry, no sessions available for data entry
        <a href="{{route('admin.my_forms_others.index')}}" class="btn btn-primary">OK</a>

    @endif  

@stop


<!-- <script type="text/javascript" src="{{ URL::asset('js/flatpickr.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/vue-flatpickr.min.js') }}"></script> -->
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

    var urlajaxpen = "{{url('admin/employees_others/ajaxfind')}}"
    var urlformsubmit = "{{url('admin/my_forms_others/')}}"
    var urlformsucessredirect = "{{url('admin/my_forms_others/')}}"
    var urlpresetsubmit = "{{url('admin/presets/')}}"
    var urlajaxpresets = "{{url('admin/employees_others/ajaxload')}}"
    
    

    var calenderdaysmap = {!! $data['calenderdaysmap'] !!};
    
    Vue.component('Multiselect', VueMultiselect.default);
    // Vue.component('flat-pickr', VueFlatpickr.default);
    Vue.component('date-picker', VueBootstrapDatetimePicker.default);

    Vue.use(VueSweetAlert.default)
    
    var calenderdays2 = {!! $data['calenderdays2'] !!};


   

    var def_time_start = "17:30";
    var def_time_end = "20:30";


    window._form = {
            session: latest_session,
            duty_date: '',
            overtime_slot: '',
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

  <script type="text/javascript" src="{{ URL::asset('js/form_other.1.js') }}"></script>

  

@stop
