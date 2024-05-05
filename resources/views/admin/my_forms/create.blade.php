@extends('layouts.app')

    <style>
        [v-cloak] { display:none; }
    </style>

@section('content')

    <h4 class="page-title">Duty Form</h4>
        

    @if(count($sessions) > 0)

    <div class="" id="app">
     

        <div class="">
        

            <?php
            $readonly = "";
            ?> 

            @include('admin.my_forms.form')
 
         </div>

        <div class="panel-footer">
            <a href="{{route('admin.my_forms.index')}}" class="btn btn-default">Cancel</a>
            <button class="btn btn-primary" @click.prevent="create" :disabled="isProcessing"><i class="fa fa-save"></i> Save <i  v-show="isProcessing" class="fa fa-spinner fa-spin"></i></button>


            <!--  <a href="#" class="pull-right" v-show ="form.overtimes.length" @click="copytimedown" >Copy First Row</a><span v-show ="form.overtimes.length" class="pull-right">&nbsp;|&nbsp;</span> -->
             <a href="#" class="pull-right" v-show ="form.overtimes.length" @click="savepreset" >Save as Preset</a><span v-show ="form.overtimes.length" class="pull-right">&nbsp;|&nbsp;</span>
             <a href="#" class="pull-right" v-show ="presets.length" @click.prevent="loadpreset" >Load Preset</a>
            
        </div>

    </div>     

    @else
    
        Sorry, no sessions available for data entry
         <a href="{{route('admin.my_forms.index')}}" class="btn btn-primary">OK</a>
         
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

    var urlajaxpen = "{{url('admin/employees/ajaxfindold')}}"
    var urlformsubmit = "{{url('admin/my_forms/')}}"
    var urlformsucessredirect = "{{url('admin/my_forms/')}}"
    var urlpresetsubmit = "{{url('admin/presets/')}}"
    var urlajaxpresets = "{{url('admin/presets/ajaxfind')}}"
    
    

    var calenderdaysmap = {!! $data['calenderdaysmap'] !!};
    var designations = {!! $data['designations'] !!};
    Vue.component('Multiselect', VueMultiselect.default);
    // Vue.component('flat-pickr', VueFlatpickr.default);
    Vue.use(VueSweetAlert.default)
    
    Vue.component('date-picker', VueBootstrapDatetimePicker.default);

    var calenderdays2 = {!! $data['calenderdays2'] !!};

    
   

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

  <script type="text/javascript" src="{{ URL::asset('js/form.1.5.js') }}"></script>





@stop
