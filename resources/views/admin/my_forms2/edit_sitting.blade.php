@extends('layouts.app')

    <style>
         [v-cloak] { display:none;  opacity: 0.1;}
    </style>

@section('content')


    <h4 class="page-title">Edit Sitting-Days Form</h4>
    
    @if(count($sessions) > 0)

    <input type="hidden" name="_token" value="{{ csrf_token() }}">


    <div class=" " id="app">
        <div class="">
           
            <div class = "pull-right">
             <small> Created, Updated : 
            {{ date('d-m-Y', strtotime($form->created_at)) }}, {{ date('d-m-Y', strtotime($form->updated_at)) }}, No.{{ $form->id }}
            </small>
             </div>

        </div>
                

        <div class="">
        
            <div class="row">
                <div class="col-md-4 form-group">           
                    Created:  By <strong>{{$form->created_by->Title}}</strong>
                </div>
               
                
            </div>


            <?php
            // $readonly = "disabled";
            $readonly = "";
            ?> 

            @include('admin.my_forms2.form_sitting')
 
        </div>

        <div class="">
            <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
            <button class="btn btn-primary" @click.prevent="update" :disabled="isProcessing"><i class="fa fa-save"></i> Save <i  v-show="isProcessing" class="fa fa-spinner fa-spin"></i></button>
             <small>&nbsp; (click Cancel if you have not made any changes)</small>
        </div>

    </div>

    <div class="row">
            <div class="col-md-12 form-group">
               
                <span>
                    @unless( \Config::get('custom.check_attendance')) 
                    <br> For each person, enter <b>leaves/late comings on sitting days</b> if any, correctly (NOT total leave count). e.g:  <i> 30/12, 31/12. </i>
                    @endunless
                    <br> If person was transferred to/from this section, set his <b>Period-From</b> /<b>Period-To</b> accordingly.
                       
                </span>
                
            </div>
    </div>      

     @else
    
        Sorry, no sessions available for data entry
         <a href="{{route('admin.my_forms2.index')}}" class="btn btn-primary">OK</a>
    @endif  

@stop

<!-- <script type="text/javascript" src="{{ URL::asset('js/vue-sweetalert.js') }}"></script> -->
<!-- <script type="text/javascript" src="{{ URL::asset('js/flatpickr.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/vue-flatpickr.min.js') }}"></script> -->

@section('javascript')
    @parent



    <script type="text/javascript">

    var urlajaxpen = "{{url('admin/employees/ajaxfind')}}"
    var urlformsubmit = "{{url('admin/my_forms2/update_sitting')}}"
    var urlformsucessredirect = "{{url('admin/my_forms2/')}}"
    var urlajaxgetpunchsittings = "{{url('admin/punchings/ajaxgetpunchsittings')}}"
        
    var calenderdaysmap = {!! $data['calenderdaysmap'] !!};
   
    Vue.component('Multiselect', VueMultiselect.default);
    // Vue.component('flat-pickr', VueFlatpickr.default);
    Vue.component('date-picker', VueBootstrapDatetimePicker.default);
    Vue.use(VueSweetAlert.default)
    var calenderdays2 = {!! $data['calenderdays2'] !!};
     // register modal component

    Vue.component("modal", {
        template: "#my-modal"
    });

    window._form = {!! $form->toJson() !!};
    
    </script>

  <script type="text/javascript" src="{{ URL::asset('js/form_sitting2.js') }}"></script>
@stop
