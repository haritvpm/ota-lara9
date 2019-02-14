@extends('layouts.app')

    <style>
        [v-cloak] { display:none; }
    </style>

@section('content')


    <h4 class="page-title">Sitting-Days Form</h4>
    
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
                    Created:  By <strong>{{$form->created_by->Title}}</strong>
                </div>
               
                
            </div>


            <?php
            // $readonly = "disabled";
            $readonly = "";
            ?> 

            @include('admin.my_forms.form_sitting')
 
        </div>

        <div class="panel-footer">
            <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
            <button class="btn btn-primary" @click.prevent="update" :disabled="isProcessing"><i class="fa fa-save"></i> Save <i  v-show="isProcessing" class="fa fa-spinner fa-spin"></i></button>
             <small>&nbsp; (click Cancel if you have not made any changes)</small>
        </div>

    </div>

    <div class="row">
            <div class="col-md-12 form-group">
               
                <span>Note:<br> For each person, enter <b>leaves/late comings on sitting days</b> if any, correctly (NOT total leave count). e.g:  <i> 30/12, 31/12. </i>
                    <br> If leaves are continuous, enter the range
                    e.g:  <i> 15/12 to 19/12, 31/12. </i>
                    <br> If person transferred to/from this section, set his <b>Period-From</b> /<b>Period-To</b> accordingly.
                       
                </span>
                
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
    var urlformsubmit = "{{url('admin/my_forms/update_sitting')}}"
    var urlformsucessredirect = "{{url('admin/my_forms/')}}"
        
    var calenderdaysmap = {!! $data['calenderdaysmap'] !!};
    var designations = {!! $data['designations'] !!};
    Vue.component('Multiselect', VueMultiselect.default);
    // Vue.component('flat-pickr', VueFlatpickr.default);
    Vue.component('date-picker', VueBootstrapDatetimePicker.default);
    Vue.use(VueSweetAlert.default)
    var calenderdays2 = {!! $data['calenderdays2'] !!};
    
  

    window._form = {!! $form->toJson() !!};
    
    </script>

  <script type="text/javascript" src="{{ URL::asset('js/form_sitting.1.5.js') }}"></script>
@stop
