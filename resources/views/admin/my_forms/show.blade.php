@extends('layouts.app')

@section('content')

<style type="text/css">
@media print {
  a[href]:after {
    content: none !important;
  }
 
  @page {size: landscape; }


th{
    font-weight: normal;
}

}

th{
    font-weight: normal;
}

@font-face {
    font-family:Rachana-Regular;
    src:url("{{URL::asset('fonts/Rachana-Regular.woff')}}") format('woff');
    font-weight: normal;
    font-style: normal;
   


}

.malfont {
  font-family: 'Rachana-Regular';
}

</style>

<style type="text/css">
    .checkbox {
  padding-left: 20px; }
  .checkbox label {
    display: inline-block;
    position: relative;
    padding-left: 5px; }
    .checkbox label::before {
      content: "";
      display: inline-block;
      position: absolute;
      width: 17px;
      height: 17px;
      left: 0;
      margin-left: -20px;
      border: 1px solid #cccccc;
      border-radius: 3px;
      background-color: #fff;
      -webkit-transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
      -o-transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
      transition: border 0.15s ease-in-out, color 0.15s ease-in-out; }
    .checkbox label::after {
      display: inline-block;
      position: absolute;
      width: 16px;
      height: 16px;
      left: 0;
      top: 0;
      margin-left: -20px;
      padding-left: 3px;
      padding-top: 1px;
      font-size: 11px;
      color: #555555; }
  .checkbox input[type="checkbox"] {
    opacity: 0; }
    .checkbox input[type="checkbox"]:focus + label::before {
      outline: thin dotted;
      outline: 5px auto -webkit-focus-ring-color;
      outline-offset: -2px; }
    .checkbox input[type="checkbox"]:checked + label::after {
      font-family: 'FontAwesome';
      content: "\f00c"; }
    .checkbox input[type="checkbox"]:disabled + label {
      opacity: 0.65; }
      .checkbox input[type="checkbox"]:disabled + label::before {
        background-color: #eeeeee;
        cursor: not-allowed; }
  .checkbox.checkbox-circle label::before {
    border-radius: 50%; }
  .checkbox.checkbox-inline {
    margin-top: 0; }

.checkbox-primary input[type="checkbox"]:checked + label::before {
  background-color: #428bca;
  border-color: #428bca; }
.checkbox-primary input[type="checkbox"]:checked + label::after {
  color: #fff; }

.checkbox-danger input[type="checkbox"]:checked + label::before {
  background-color: #d9534f;
  border-color: #d9534f; }
.checkbox-danger input[type="checkbox"]:checked + label::after {
  color: #fff; }

.checkbox-info input[type="checkbox"]:checked + label::before {
  background-color: #5bc0de;
  border-color: #5bc0de; }
.checkbox-info input[type="checkbox"]:checked + label::after {
  color: #fff; }

.checkbox-warning input[type="checkbox"]:checked + label::before {
  background-color: #f0ad4e;
  border-color: #f0ad4e; }
.checkbox-warning input[type="checkbox"]:checked + label::after {
  color: #fff; }

.checkbox-success input[type="checkbox"]:checked + label::before {
  background-color: #5cb85c;
  border-color: #5cb85c; }
.checkbox-success input[type="checkbox"]:checked + label::after {
  color: #fff; }
</style>


<h4 class="page-title" style="text-align: center">
    SECRETARIAT OF THE KERALA LEGISLATURE
    
</h4>
    @if($prev != null || $next != null)
    <?php
    $prevdisabled = $prev == null ? 'disabled' : '';
    $nextdisabled = $next == null ? 'disabled' : '';

    ?>
  
    <a href="{{ route('admin.my_forms.show',[$prev]) }}"class="btn  {{$prevdisabled}} hidden-print"><i class="fa fa-chevron-left"></i> Previous</a>
   
    <a href="{{ route('admin.my_forms.show',[$next]) }}"class="btn  {{$nextdisabled}} hidden-print">Next <i class="fa fa-chevron-right"></i></a>
    @endif
    
  


<div class="panel panel-default">
    <div class="panel-heading">
         @if($form->overtime_slot == 'Sittings')
    <small>Sitting-days Form </small>
    @else
    <small>Duty Form </small>
    @endif  

    <div class = "pull-right">
        <small> Created,Updated : 
        {{ date('d-m-Y', strtotime($form->created_at)) }}, {{ date('d-m-Y h:i a', strtotime($form->updated_at->timezone('Asia/Kolkata'))) }}, No.{{ $form->id }}
        </small>
    </div>

    </div>

    <div class="panel-body table-responsive">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-responsive table-condensed">
                    <tr>
                        <td>
                       <strong>{!! html_entity_decode($romankla) !!}</strong> KLA,
                       <strong>{!! html_entity_decode( $sessionnumber_th) !!}</strong> session
                       </td>
                       
                        
                    </tr>

                    <tr>
                        
                        
                        @if($form->overtime_slot == 'Sittings')
                        <th>
                        Period
                        </th>
                         <td>
                        <small> From</small> {{$form->date_from}} <small>to</small> {{$form->date_to}}
                        <span style="display:inline-block; width: 20;"></span>
                         @lang('quickadmin.forms.fields.overtime-slot')
                        <strong> {{ $form->overtime_slot }}</strong> 
                         </td>
                        @else
                            <th>
                           @lang('quickadmin.forms.fields.duty-date')
                           </th>
                            <td>
                           {{ $form->duty_date }} ({{ $descriptionofday  or $daytype}})
                           <span style="display:inline-block; width: 20;"></span>
                         @lang('quickadmin.forms.fields.overtime-slot')
                        <strong> {{ $form->overtime_slot }}</strong> 
                            </td>
                        @endif
                

                       
                    </tr>

                    <tr>
                        <th>Created by</th>
                                         
                       
                        <td field-key='creator' colspan="12">{{ $createdby }}
                        </td>
                                                
                    </tr>

                    @if($submmittedby != '')
                    <tr>
                        <th>Approved by</th>
                        <td colspan="12">
                            @php
                            $submittedby_array = explode('|',$submmittedby);
                            for ($x = 0; $x < count($submittedby_array); $x++) {
                             if($x%3 == 2)
                                echo  "<span style='color:darkred'>";
                            else if($x%2)
                                echo  "<span style='color:darkgreen'>";
                            else
                                echo  "<span style='color:darkblue'>";

                            echo $submittedby_array[$x];
                            echo "</span>";
                            if($x < count($submittedby_array)-1)
                                echo ", ";
                           
                            }
                            
                            @endphp
                        </td>
                    </tr>
                    @endif
                    <tr>
                    
                    <th><small>Status</small></th>
                        <td field-key='owner'>
                          <small>
                            @if($form->owner == Auth::user()->username)
                                @if($form->owner != $form->creator)
                                    @if($form->owner == 'admin')
                                        Submitted on {{ date('d-m-Y', strtotime($form->submitted_on)) }}
                                    @else
                                        To approve 
                                    @endif
                                @else
                                    Draft
                                @endif    
                            @else
                                @if($form->owner == 'admin')
                                    Submitted to Accounts on {{ date('d-m-Y', strtotime($form->submitted_on)) }}
                                @elseif($form->owner != $form->creator)
                                    Pending approval by {{ optional($form->owned_by)->DispNameWithNameShort ?? ($form->owner .' (userid not found)' ) }} 
                                @else
                                    Draft
                                @endif                                
                            @endif
                            @if($form->form_no < 0)
                            <span style="color:red"> (Withheld)</span>
                            @endif
                          </small>

                    </td>
                    
                    </tr>
                    
                    

                </table>
            </div>
            
        </div><!-- Nav tabs -->
<hr class="hidden-print">
       <!--  <table class="table table-bordered table-striped {{ count($overtimes) > 0 ? 'datatable' : '' }}"> -->
         <table class="table table-bordered table-striped table-condensed">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>PEN-Name</th>
                    <th>@lang('quickadmin.overtimes.fields.designation')</th>
                    @if($form->overtime_slot == 'Sittings')
                    <th class="text-center">Period from</th>
                    <th class="text-center">Period to</th>
                    <th class="text-center">Sittings days attended</th>
                    <th>Leave details if any</th>
                    @else
                    <th>@lang('quickadmin.overtimes.fields.from')</th>
                    <th>@lang('quickadmin.overtimes.fields.to')</th>
                    <th>@lang('quickadmin.overtimes.fields.worknature')</th>
                    @endif


                </tr>
            </thead>

            <tbody>
                @if (count($overtimes) > 0)
                @foreach ($overtimes as $overtime)


                <tr data-entry-id="{{ $overtime->id }}">

                    <td style="width:1px;"><small>{{  $loop->iteration }}</small></td>
                    <td field-key='pen' class="text-nowrap">
                        <a href="<?=URL::to('admin/searches?session='.$form->session.'&created_by=&status=&namefilter='.  substr( $overtime->pen,0,strpos( $overtime->pen,'-' )) )?>" >
                        <small>{{substr( $overtime->pen,0,strpos( $overtime->pen,'-' ))}}</small></a>&nbsp;{{ substr( $overtime->pen,strpos( $overtime->pen,'-' )+1)}}
                    </td>
                    <td field-key='designation' class="text-nowrap"><small>{{ $overtime->designation }}</small></td>
                   
                    @if($form->overtime_slot == 'Sittings')
                    <td field-key='from' class="small text-center text-nowrap">{{ $overtime->from }}</td>
                    <td field-key='to' class="small text-center text-nowrap">{{ $overtime->to }}</td>
                    @else
                    <td field-key='from' class=" text-nowrap">{{ date("h:i a", strtotime($overtime->from)) }}</td>
                    <td field-key='to' class=" text-nowrap">{{ date("h:i a", strtotime($overtime->to)) }}</td>
                    @endif


                    @if($form->overtime_slot == 'Sittings')
                    <td class="text-center" field-key='count'>{{ $overtime->count }}</td>
                    <td field-key='worknature'> <small> {{ $overtime->worknature }}</small></td>
                    @else
                    <td field-key='worknature'> <small> {{ $overtime->worknature or 'assly rel work'}}</small></td>
                    @endif


                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="12">@lang('quickadmin.qa_no_entries_in_table')</td>
                </tr>
                @endif
            </tbody>
        </table>

        <br>

        @if($form->remarks != '')
        <div class="row">
            <div class="col-md-12 form-group">
                
                <p ><strong>Remarks</strong> : {{ $form->remarks }} </p>
            </div>
        </div>
        @endif

    </div>
</div>

<div id="app">

    @if( Auth::user()->username == $form->owner)
       
       @if($cansubmittoaccounts || $canforward)
       
         
         <div class="hidden-print checkbox checkbox-success">
              <input id="checkbox2" type="checkbox" v-model="agreechecked">
              <label for="checkbox2" >
                <p class="malfont"  v-html="approvaltext+' - <strong>{{Auth::user()->DispNameWithName}}</strong>'">
                 
                </p>
                                              
              </label>
          </div>
         
     
        <br>
       @endif

    @endif

    @if( strpos(Auth::user()->username,'oo.') !== 0)
        @if($form->overtime_slot == 'Sittings')
            <p><small>
           Note: Statement may be submitted by an officer not below the rank of <strong><i>Under Secretary</i></strong>
            </small></p>
        @else
            <p><small>
            Note: Statement may be submitted by an officer not below the rank of <strong><i>Deputy Secretary</i></strong>
            </small></p>
        @endif
    @endif


 <div class ="btn-toolbar">
    <a href="{{route('admin.my_forms.index')}}" class="btn btn-default hidden-print"><i class="fa fa-arrow-left"></i>&nbsp;@lang('quickadmin.qa_back_to_list')</a>

   <button class="btn btn-default hidden-print" onClick="window.print()">Print</button>


    @if($form->creator == auth()->user()->username && !Auth::user()->isAdminorAudit() && $form->overtime_slot != 'Sittings')
    
      <a href="{{ route('admin.my_forms.create_copy',[$form->id]) }}" class="btn btn-default hidden-print">  Copy to New Form</a>
    
    @endif

    @if( Auth::user()->username == $form->owner || Auth::user()->isAdmin())
              
       <!-- admin need not edit,delete forms -->
        
        &nbsp;
        @if(!Auth::user()->isAdmin())        
        {!! Form::open(array(
          
            'style' => 'display: inline-block;',
            'method' => 'DELETE',
            'onsubmit' => "return confirm('Are you sure you want to delete this entire form?');",
            'route' => ['admin.my_forms.destroy', $form->id])) !!}

        {!! Form::submit('Delete Form', array('class' => 'btn btn-default hidden-print')) !!}
        {!! Form::close() !!}
        @endif

        @if(!Auth::user()->isAdmin()) 
        &nbsp;<a href="{{ route('admin.my_forms.edit',[$form->id]) }}" class="btn btn-default hidden-print"><i class="fa fa-edit"></i>&nbsp;@lang('quickadmin.qa_edit')</a>
        @endif


        @if( $form->owner != $form->creator)
         
         &nbsp;<button class="btn btn-default hidden-print" @click="sendbackClick" data-toggle="tooltip" title="Send this form back to the section which created it"><i class="fa fa-mail-reply"></i>&nbsp;Send Back</button>

        @if(Auth::user()->isAdmin()) 
          &nbsp;<button class="btn btn-default hidden-print" @click="sendonelevelbackClick" data-toggle="tooltip" title="Send this form back to the last person who approved it"><i class="fa fa-mail-reply"></i>&nbsp;Send Back 1-Level</button>
        @endif

        

    <!-- no withhold for sitting forms. except for admin -->

         @if(!($form->form_no < 0))
           @if((Auth::user()->isDSorAbove() && 
                    $form-> overtime_slot != 'Sittings') || 
                 Auth::user()->isAdmin())
             &nbsp;<button class="btn btn-default hidden-print" @click="ignoreClick(true)" data-toggle="tooltip" title="Withold this form indefinitely">
              <i class="fa fa-ban"></i>&nbsp;
             </button>
           @endif
         @elseif($form->form_no < 0 && $form->owner == 'admin')
           @if(Auth::user()->isAdmin())
             &nbsp;<button class="btn btn-default hidden-print" @click="ignoreClick(false)" >
              &nbsp;Release
             </button>
           @endif
         @endif

        @endif <!-- if( $form->owner != $form->creator) -->
        
        @php
         
          $usertitle = Auth::user()->displayname;
          if($usertitle == ''){
            if( Auth::user()->isDataEntryLevel()){
              $usertitle = 'Asst';
            }else if ( Auth::user()->isSectionOfficer()){

            }
          }

        @endphp    
          
        @if($cansubmittoaccounts)
        &nbsp;<button class="btn btn-success pull-right hidden-print" @click="submitClick('{{$usertitle}}')" data-toggle="tooltip" title="Send to Accounts D" :disabled="!agreechecked"><i class="fa fa-envelope"></i>&nbsp;Submit to Accounts</button>
        <!-- due to max length of 255 of submitted_names, prevent too many forwarding -->
        @endif
     

        @if($canforward)
          @if(!$cansubmittoaccounts)
          &nbsp;<button class="btn btn-danger pull-right hidden-print" @click="forwardClick('{{$usertitle}}')" data-toggle="tooltip" title="Send this form to a higher official for approval" id="btn_forward" :disabled="!agreechecked"><i class="fa fa-mail-forward"></i>&nbsp;Forward</button>
          @elseif($form->overtime_slot != 'Sittings' && $cansubmittoaccounts)
          &nbsp;<button class="btn btn-default hidden-print" @click="forwardClick('{{$usertitle}}')" data-toggle="tooltip" title="Send this form to a higher official for approval" id="btn_forward" :disabled="!agreechecked"><i class="fa fa-mail-forward"></i>&nbsp;</button>
          @endif
        @endif

        

    @endif


    </div>

    
</div>

 
@stop





@section('javascript') 

<script type="text/javascript" src="{{ URL::asset('js/vue-sweetalert.js') }}"></script>


<script type="text/javascript">
 var editurl = "{{ route('admin.my_forms.edit',[$form->id]) }}";

  window.addEventListener("keydown", function (event) {
  if (event.defaultPrevented) {
    return; // Should do nothing if the default action has been cancelled
  }

  var handled = false;
  if (event.key !== undefined) {
    // Handle the event with KeyboardEvent.key and set handled true.
    if(event.key == 'F9'){
        //vm.sendbackClick();
        document.location.href= editurl
        handled = true;
    } else if(event.key == 'F8'){
       // vm.sendbackClick();
        
        handled = true;
    }

  } else if (event.keyIdentifier !== undefined) {
    // Handle the event with KeyboardEvent.keyIdentifier and set handled true.
  } else if (event.keyCode !== undefined) {
    // Handle the event with KeyboardEvent.keyCode and set handled true.


  }
 
  if (handled) {
    // Suppress "double action" if event handled
    event.preventDefault();
  }
}, true);


</script>



<script type="text/javascript">
    var urlformforward = "{{url('admin/my_forms/forward/')}}"
    var urlformsubmittoaccounts = "{{url('admin/my_forms/submittoaccounts/')}}"
    var urlformsendback = "{{url('admin/my_forms/sendback/')}}"
    var urlformsendonelevelback = "{{url('admin/my_forms/sendonelevelback/')}}"
    var urlformignore = "{{url('admin/my_forms/ignore/')}}"
    var urlredirect = "{{url('admin/my_forms/')}}"
</script>


 <script type="text/javascript" src="{{ URL::asset('js/form_show.1.0.js') }}"></script>


@endsection