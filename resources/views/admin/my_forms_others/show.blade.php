@extends('layouts.app')

@section('content')

<style type="text/css">
@media print {
  a[href]:after {
    content: none !important;
  }
 
  @if($form->overtime_slot == 'Sittings')
  @page {size: landscape;  margin: .25in;
  }
  @else
   @page { size : landscape;  margin: .25in;}
  
  @endif
 

}

th{
    font-weight: normal;
}
</style>

<h4 class="page-title" style="text-align: center">
    SECRETARIAT OF THE KERALA LEGISLATURE
    
</h4>
<h5 class="page-title" style="text-align: center">
    OTA STATEMENT FOR OTHER DEPARTMENT
    
</h5>

<div class="panel panel-default" >
    <div class="panel-heading">
    @if($form->overtime_slot == 'Sittings')
    Sitting-days Form
    @else
    Duty Form
    @endif  

     <div class = "pull-right">
        <small> Updated : 
        {{ date('d-m-Y', strtotime($form->updated_at)) }},
        No.{{ $form->id }}, {{$form->MD5Clipped}}
        </small>
    </div>


    </div>

    <div class="panel-body table-responsive">
        <div class="row">
            <div class="col-md-12">
                <table class="table  table-condensed">
                    <tr>
                        
                       <td>
                       {!! html_entity_decode($romankla) !!} KLA,
                       <strong>{!! html_entity_decode( $sessionnumber) !!}</strong> session
                       
                       </td>
                       
                    </tr>

                    <tr>
                        <th>Created by</th>
                        <td field-key='creator'>{{ $form->created_by->displayname }}  {{ $form->created_by->name }}
                        </td>

                        @if($form->overtime_slot == 'Sittings')
                        <th>Period</th>
                        <td field-key='date_from'>From {{$form->date_from}} to {{$form->date_to}}</td>
                        @else
                           <th>@lang('quickadmin.forms.fields.duty-date')</th>
                           <td field-key='duty_date'>{{ $form->duty_date }} ({{$descriptionofday  or $daytype}})</td>
                        @endif

                    </tr>
                    <tr>
                        <th>Status</th>
                        <td field-key='owner'>
                             
                            @if($form->owner == 'admin')
                            Submitted to Accounts
                            
                            @else
                            Draft
                            
                            @endif
                        </td>

                        <th>@lang('quickadmin.forms.fields.overtime-slot')</th>
                        <td field-key='overtime_slot'> <strong> {{ $form->overtime_slot }}</strong></td>   

                    </tr>
                    
                   <!--  <tr>
                        <th>Submitted by</th>
                        <td>{{ $submmittedby }}</td>
                    </tr> -->
                                       
                    

                </table>
            </div>
            
        </div><!-- Nav tabs -->
<hr class="hidden-print">
       <!--  <table class="table table-bordered table-striped {{ count($overtimes) > 0 ? 'datatable' : '' }}"> -->
         <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>PEN</th>
                    <th>Desig</th>
                    <!-- <th>@lang('quickadmin.overtimes.fields.designation')</th> -->
                    @if($form->overtime_slot == 'Sittings')
                    <th>Period from</th>
                    <th>Period to</th>
                    <th class="text-center">Sittings</th>
                    
                    @else
                    <th>@lang('quickadmin.overtimes.fields.from')</th>
                    <th>@lang('quickadmin.overtimes.fields.to')</th>
                    <th>Nature of Work</th>
                    
                    @endif
                    <th>IFSC , Account</th>
                    <th>Mob</th>

                </tr>
            </thead>

            <tbody>
                @if (count($overtimes) > 0)
                @foreach ($overtimes as $overtime)
                <?php
                $pen_actual =   substr( $overtime->pen,0, strpos( $overtime->pen,'-' ) );
                $name =   substr( $overtime->pen,strpos( $overtime->pen,'-' )+1 );
                $desig =  substr( $name,strpos( $name,',' )+1);
                $name =   substr( $name,0, strpos( $name,',' ) );

                ?>
                <tr data-entry-id="{{ $overtime->id }}">

                    <td class="small" style="width:1px;">{{  $loop->iteration }}</td>
                   
                    <td class="small">{{$name}}</td>
                    <td field-key='pen' class="small">{{$pen_actual}}</td>
                    <td class="small">{{$desig}}</td>
                  

                   @if($form->overtime_slot == 'Sittings')
                    <td field-key='from' class="small text-nowrap">{{ $overtime->from }}</td>
                    <td field-key='to' class="small text-nowrap">{{ $overtime->to }}</td>
                    <td  style="width:1px;" class="text-center" field-key='count'>{{ $overtime->count }}</td>
                    @else
                    <td field-key='from' class="text-nowrap">{{ date("h:i a", strtotime($overtime->from)) }}</td>
                    <td field-key='to' class="text-nowrap">{{ date("h:i a", strtotime($overtime->to)) }}</td>
                    <td class="small" field-key='worknature'>{{ $overtime->worknature }}</td>
                    @endif

                  
                    <td class="small" field-key='account_no'>{{ optional($overtime->employeesother)->account_type != 'TSB' ? optional($overtime->employeesother)->ifsc : 'TSB' }} , {{ optional($overtime->employeesother)->account_no }}</td>
                    <td class="small" field-key='mobile_no'> {{ optional($overtime->employeesother)->mobile }}</td>

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

        <!-- <div class="row">
            <div class="col-md-12 form-group">
                
                <p ><strong>Remarks</strong> : {{ $form->remarks }} </p>
            </div>
        </div>     -->  

    </div>

    

</div>

<div id="app">
 <div class ="btn-toolbar">
    <a href="{{route('admin.my_forms_others.index')}}" class="btn btn-default hidden-print"><i class="fa fa-arrow-left"></i>&nbsp;@lang('quickadmin.qa_back_to_list')</a>

   <button class="btn btn-default hidden-print" onClick="window.print()">Print</button>

    @if( Auth::user()->username == $form->owner)
       
       <!-- admin need not edit forms -->
        @if( !Auth::user()->isAdmin())
        &nbsp;<a href="{{ route('admin.my_forms_others.edit',[$form->id]) }}" class="btn btn-primary hidden-print"><i class="fa fa-edit"></i>&nbsp;@lang('quickadmin.qa_edit')</a>
        @endif
        @if( !auth()->user()->isAdmin() )
                                            
        {!! Form::open(array(
          
            'style' => 'display: inline-block;',
            'method' => 'DELETE',
            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
            'route' => ['admin.my_forms_others.destroy', $form->id])) !!}
        {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn  btn-default  hidden-print')) !!}
        {!! Form::close() !!}
        @endif


        @if($cansubmittoaccounts)
        &nbsp;<button class="btn btn-success pull-right hidden-print" @click="submitClick"><i class="fa fa-envelope"></i>&nbsp;Submit to Accounts</button>
        @endif

        @if($canforward)
        &nbsp;<button class="btn btn-danger pull-right hidden-print" @click="forwardClick"><i class="fa fa-mail-forward"></i>&nbsp;Forward</button>
        @endif
       

        @if( $form->owner != $form->creator && !Auth::user()->isAdmin() )
         &nbsp;<button class="btn btn-warning pull-right hidden-print" @click="sendbackClick"><i class="fa fa-mail-reply"></i>&nbsp;Send Back</button>
        @endif
       

    @endif
    </div>
</div>

<hr class="hidden-print">
 @if( Auth::user()->username == $form->owner)
       
        <div class="visible-print pull-right"><br><br>Signature, Name and Designation<br>
            of the Officer forwarding the statement</div>

        <div class="visible-print"><br><br><br>Countersigned by</div>
        
 @endif



@stop





@section('javascript') 

<script type="text/javascript" src="{{ URL::asset('js/vue-sweetalert.js') }}"></script>
<script type="text/javascript">
  window.addEventListener("keydown", function (event) {
  if (event.defaultPrevented) {
    return; // Should do nothing if the default action has been cancelled
  }

  var handled = false;
  if (event.key !== undefined) {
    // Handle the event with KeyboardEvent.key and set handled true.
    if(event.key == 'F9'){
        vm.sendbackClick();
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

    var urlformforward = "{{url('admin/my_forms_others/forward/')}}"
    var urlformsubmittoaccounts = "{{url('admin/my_forms_others/submittoaccounts/')}}"
    var urlformsendback = "{{url('admin/my_forms_others/sendback/')}}"
    var urlredirect = "{{url('admin/my_forms_others/')}}"

</script>

 <script type="text/javascript" src="{{ URL::asset('js/form_other_show.js') }}"></script>


@endsection