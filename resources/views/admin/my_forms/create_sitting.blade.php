@extends('layouts.app')

    <style>
        [v-cloak] { display:none; }


    </style>


@section('content')


    <h4 class="page-title">Sitting-Days Form</h4>
    
    @if(count($sessions) > 0)
    
  
    <div class="panel panel-default" id="app">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>

        <div class="panel-body">
        
            <?php
            $readonly = "";
            ?> 

            @include('admin.my_forms.form_sitting')
 
         </div>

        <div class="panel-footer">
            <a href="{{route('admin.my_forms.index')}}" class="btn btn-default">Cancel</a>
            <button class="btn btn-primary" @click.prevent="create" :disabled="isProcessing"><i class="fa fa-save"></i> Save<i  v-show="isProcessing" class="fa fa-spinner fa-spin"></i></button>

            <a href="#" class="pull-right" v-show ="presets.length" @click.prevent="loadpreset" >Load Preset</a>

        </div>

    </div>
    
 <div class="row">
            <div class="col-md-12 form-group">
               
                <span>Note:
                    @unless ( \Config::get('custom.check_attendance')) 
                    <br> For each person, enter <b>leaves/late comings on sitting days</b> if any, correctly (NOT total leave count). e.g:  <i> 30/12, 31/12. </i>
                    @endunless
                    
                    <br> If person was transferred to/from this section, set his <b>Period-From</b> /<b>Period-To</b> accordingly.
                       
                </span>
                
            </div>
    </div>      

    @else
    
        Sorry, no sessions available for data entry
        <a href="{{route('admin.my_forms.index')}}" class="btn btn-primary">OK</a>

    @endif  

@stop


<!-- Flatpickr related files -->
<script type="text/javascript" src="{{ URL::asset('js/vue-sweetalert.js') }}"></script>

<!-- 
<script type="text/javascript" src="{{ URL::asset('js/flatpickr.min.js') }}"></script>
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

    var urlajaxpen = "{{url('admin/employees/ajaxfind')}}"
    var urlformsubmit = "{{url('admin/my_forms/store_sitting')}}"
    var urlformsucessredirect = "{{url('admin/my_forms/')}}"
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

  <script type="text/javascript" src="{{ URL::asset('js/form_sitting.1.5.js') }}"></script>

  

@stop
