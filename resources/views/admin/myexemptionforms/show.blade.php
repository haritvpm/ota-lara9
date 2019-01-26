@extends('layouts.app')

@section('content')

<style type="text/css">
@media print {
  a[href]:after {
    content: none !important;
  }
 
  @page {size: landscape; }


}


</style>

<h4 class="page-title" style="text-align: center">
    SECRETARIAT OF THE KERALA LEGISLATURE
    
</h4>


<div class="panel panel-default">
    <div class="panel-heading">
       
    Exemption
   
     <div class = "pull-right">
        <small> Created,Updated : 
        {{ date('d-m-Y', strtotime($form->created_at)) }}, {{ date('d-m-Y', strtotime($form->updated_at)) }}, No.{{ $form->id }}
        </small>
    </div>


    </div>

    <div class="panel-body table-responsive">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-condensed">
                     <tr>
                        <td>
                       <strong>{!! html_entity_decode($romankla) !!}</strong> KLA,
                       <strong>{!! html_entity_decode( $sessionnumber_th) !!}</strong> session
                       </td>
                       
                        
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
                                    Submitted to Services on {{ date('d-m-Y', strtotime($form->submitted_on)) }}
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
                    <th>@lang('quickadmin.overtimes.fields.pen')</th>
                    <th>@lang('quickadmin.overtimes.fields.designation')</th>
                    <th>Reason</th>
                   


                </tr>
            </thead>

            <tbody>
                @if (count($overtimes) > 0)
                @foreach ($overtimes as $overtime)
                <tr data-entry-id="{{ $overtime->id }}">

                    <td  style="width:1px;"><small>{{  $loop->iteration }}</small></td>
                    <td field-key='pen'>{{ $overtime->pen }}</td>
                    <td field-key='designation'>{{ $overtime->designation }}</td>
                   
                                       
                    <td field-key='worknature'>{{ $overtime->worknature }}</td>


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

        <div class="row">
            <div class="col-md-12 form-group">
                
                <p ><strong>Remarks</strong> : {{ $form->remarks }} </p>
            </div>
        </div>      

    </div>
</div>


<div id="app">

    


 <div class ="btn-toolbar">
    <a href="{{route('admin.myexemptionforms.index')}}" class="btn btn-default hidden-print"><i class="fa fa-arrow-left"></i>&nbsp;@lang('quickadmin.qa_back_to_list')</a>

   <button class="btn btn-default hidden-print" onClick="window.print()">Print</button>

   

    @if( Auth::user()->username == $form->owner || Auth::user()->isAdmin())
              
       <!-- admin need not edit forms -->
        
        &nbsp;
                
        {!! Form::open(array(
          
            'style' => 'display: inline-block;',
            'method' => 'DELETE',
            'onsubmit' => "return confirm('Are you sure you want to delete this entire form?');",
            'route' => ['admin.exemptionforms.destroy', $form->id])) !!}

        {!! Form::submit('Delete Form', array('class' => 'btn btn-default hidden-print')) !!}
        {!! Form::close() !!}

        &nbsp;<a href="{{ route('admin.myexemptionforms.edit',[$form->id]) }}" class="btn btn-default hidden-print"><i class="fa fa-edit"></i>&nbsp;@lang('quickadmin.qa_edit')</a>

        @if( $form->owner != $form->creator)

        
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
        &nbsp;<button class="btn btn-success pull-right hidden-print" @click="submitClick('{{$usertitle}}')" data-toggle="tooltip" title="Send to Accounts D" ><i class="fa fa-envelope"></i>&nbsp;Submit to Services A</button>
        <!-- due to max length of 255 of submitted_names, prevent too many forwarding -->
        @elseif($canforward)
        &nbsp;<button class="btn btn-danger pull-right hidden-print" @click="forwardClick('{{$usertitle}}')" data-toggle="tooltip" title="Send this form to a higher official for approval" id="btn_forward"><i class="fa fa-mail-forward"></i>&nbsp;Forward</button>
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
        //document.location.href= editurl
        handled = true;
    } else if(event.key == 'F8'){
        //vm.sendbackClick();
        
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
    var urlformforward = "{{url('admin/myexemptionforms/forward/')}}"
    var urlformsubmittoaccounts = "{{url('admin/myexemptionforms/submittoaccounts/')}}"
   
    var urlredirect = "{{url('admin/myexemptionforms/')}}"
</script>


 <script type="text/javascript" src="{{ URL::asset('js/formexemption_show.1.0.js') }}"></script>




@endsection