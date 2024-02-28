@extends('layouts.app')

    <style>
        [v-cloak] { display:none; }
    </style>

@section('content')


    <h3 class="page-title">Sitting-Days Form</h3>
    
    @if(count($sessions) > 0)

    <input type="hidden" name="_token" value="{{ csrf_token() }}">


    <div class="card p-2" id="app">
        <div class="card-title">
            Edit
        </div>
                

        <div class="card-body">
        
            <div class="row">
                <div class="col-md-4 form-group">           
                    Created:  By <strong>{{$form->created_by->name}}</strong> at <strong>{{$form->created_at}}</strong>
                </div>
                <div class="col-md-4 form-group">  
                    Last Updated: <strong>{{$form->updated_at}}</strong>
                </div>
                <div class="col-md-4 form-group">  
                    Form no: <strong>{{$form->id}}</strong>
                </div>
                
            </div>


            <?php
            $readonly = "disabled";
            ?> 

            @include('admin.pa2mlaforms.form')
 
        </div>

        <div class="card-footer">
            <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
            <button class="btn btn-primary" @click.prevent="update" :disabled="isProcessing"><i class="fa fa-save"></i> Save<i  v-show="isProcessing" class="fa fa-spinner fa-spin"></i></button>
        </div>

    </div>

    @else
    
        Sorry, no sessions available for data entry
    
    @endif  

@stop

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
 
    var pen_names_to_desig = {!! $data['pen_names_to_desig'] !!};

    window._form = {!! $form->toJson() !!};
    
    </script>

  <script type="text/javascript" src="{{ URL::asset('js/form_pa2mla.js') }}"></script>
@stop
