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
            Edit
            <div class = "pull-right">
             <small> Created, Updated : 
            {{ date('d-m-Y', strtotime($form->created_at)) }}, {{ date('d-m-Y', strtotime($form->updated_at)) }}, No.{{ $form->id }}
            </small>
             </div>
        </div>
                

        <div class="panel-body">
        
            <div class="row">
                <div class="col-md-4 form-group">           
                    Created:  By <strong>{{$form->created_by->name}}</strong>
                </div>
               
                
            </div>


            <?php
            // $readonly = "disabled";
            $readonly = "";
            ?> 

            @include('admin.my_forms_others.form_sitting')
 
        </div>

        <div class="panel-footer">
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

    var urlajaxpen = "{{url('admin/employees_others/ajaxfind')}}"
    var urlformsubmit = "{{url('admin/my_forms_others/update_sitting')}}"
    var urlformsucessredirect = "{{url('admin/my_forms_others/')}}"
        
    var calenderdaysmap = {!! $data['calenderdaysmap'] !!};
  
    Vue.component('Multiselect', VueMultiselect.default);
    // Vue.component('flat-pickr', VueFlatpickr.default);
    Vue.component('date-picker', VueBootstrapDatetimePicker.default);
    Vue.use(VueSweetAlert.default)
    var calenderdays2 = {!! $data['calenderdays2'] !!};
    
  

    window._form = {!! $form->toJson() !!};
    
    </script>

  <script type="text/javascript" src="{{ URL::asset('js/form_sitting_other.1.js') }}"></script>
@stop
