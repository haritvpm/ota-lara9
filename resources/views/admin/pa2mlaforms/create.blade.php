@extends('layouts.app')

    <style>
        [v-cloak] { display:none; }
    </style>

@section('content')

    <h3 class="page-title">PA to MLA Form</h3>
    
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

            @include('admin.pa2mlaforms.form')
 
         </div>

        <div class="panel-footer">
            <a href="{{route('admin.pa2mlaforms.index')}}" class="btn btn-default">Cancel</a>
            <button class="btn btn-primary" @click.prevent="create" :disabled="isProcessing"><i class="fa fa-save"></i> Save<i  v-show="isProcessing" class="fa fa-spinner fa-spin"></i></button>
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


    <script type="text/javascript">

    var urlajaxpen = "{{url('admin/employees/ajaxfind')}}"
    var urlformsubmit = "{{url('admin/pa2mlaforms/')}}"
    var urlformsucessredirect = "{{url('admin/pa2mlaforms/')}}"


    var calenderdaysmap = {!! $data['calenderdaysmap'] !!};
    var designations = {!! $data['designations'] !!};
    Vue.component('Multiselect', VueMultiselect.default);
    // Vue.component('flat-pickr', VueFlatpickr.default);
    Vue.component('date-picker', VueBootstrapDatetimePicker.default);
    Vue.use(VueSweetAlert.default)
    var calenderdays2 = {!! $data['calenderdays2'] !!};
    var pa2mlas = {!! $data['pa2mlas'] !!};

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

  <script type="text/javascript" src="{{ URL::asset('js/form_pa2mla.js') }}"></script>

  

@stop
