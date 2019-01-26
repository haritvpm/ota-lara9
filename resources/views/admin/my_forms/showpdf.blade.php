

<body>



<style type="text/css">
.table-fit {
  white-space: nowrap;
  width: 1%;
}


</style>

<style type="text/css">
@media print {
  a[href]:after {
    content: none !important;
  }
 


th{
    font-weight: normal;
}

}


</style>

<h4 class="page-title" style="text-align: center">
   <u> SECRETARIAT OF THE KERALA LEGISLATURE </u>
    
</h4>
  

<div  style="margin-left: 20px; margin-right: 20px;">
    <div class="panel-heading">
     <!-- <span  style="color: green" >Sl.No  <strong>{{$index}}</strong> </span>  :   -->
       

    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-responsive table-condensed table-fit">
                    <tr>
                        <td>
                      <strong>{!! html_entity_decode($romankla) !!}</strong> KLA,
                       <strong>{!! html_entity_decode( $sessionnumber_th) !!}</strong> session
                       </td>
                      
                        
                    </tr>

                    <tr>
                       
                        @if($form->overtime_slot == 'Sittings')
                        <th>Period</th>
                        <td field-key='date_from'>From {{$form->date_from}} to {{$form->date_to}}
                             <span style="display:inline-block; width: 20;"></span>
                         <strong>@lang('quickadmin.forms.fields.overtime-slot')</strong> 
                        {{ $form->overtime_slot }}
                        </td>
                        @else
                           <th>@lang('quickadmin.forms.fields.duty-date')</th>
                           <td field-key='duty_date'>{{ $form->duty_date }} ({{ $descriptionofday  or $daytype}})
                             <span style="display:inline-block; width: 20;"></span>
                         <strong>@lang('quickadmin.forms.fields.overtime-slot')</strong> 
                        {{ $form->overtime_slot }}
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
                        <!-- <td><span style="color:blue">{{ $submmittedby }}</span></td> -->
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
                        <th> <small>Status</small></th>
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
                                    Submitted  to Accounts on {{ date('d-m-Y', strtotime($form->submitted_on)) }}
                                @elseif($form->owner != $form->creator)
                                    Pending approval by {{ $form->owned_by->displayname }} {{ $form->owned_by->Title }}
                                @else
                                    Draft
                                @endif                                
                            @endif 

                            </small>
                            <small> (form no {{ $form->form_no}})</small>
                        </td>
                    </tr>
                   
                    
                    

                </table>
            </div>
            
        </div><!-- Nav tabs -->
<hr class="hidden-print">
       <!--  <table class="table table-bordered table-striped {{ count($overtimes) > 0 ? 'datatable' : '' }}"> -->
         <table class="table  table-striped table-condensed table-fit">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>PEN-Name</th>
                    <th>@lang('quickadmin.overtimes.fields.designation')</th>
                    @if($form->overtime_slot == 'Sittings')
                    <th>Period from</th>
                    <th>Period to</th>
                    <th>Sittings days attended</th>
                    <th>Leave/ Transfer details if any</th>
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

                    <td>{{  $loop->iteration }}</td>
                    <td field-key='pen'>{{   $overtime->pen }}</td>
                    <td field-key='designation'>{{ $overtime->designation }}</td>

                    @if($form->overtime_slot == 'Sittings')
                    <td field-key='from' class="text-nowrap">{{ $overtime->from }}</td>
                    <td field-key='to' class="text-nowrap">{{ $overtime->to }}</td>
                    @else
                    <td field-key='from' class="text-nowrap">{{ date("h:i a", strtotime($overtime->from)) }}</td>
                    <td field-key='to' class="text-nowrap">{{ date("h:i a", strtotime($overtime->to)) }}</td>
                    @endif
                    @if($form->overtime_slot == 'Sittings')
                    <td class="text-center" field-key='count'>{{ $overtime->count }}</td>
                    <td field-key='worknature'> <small> {{ $overtime->worknature }} </small> </td>
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


 

<!-- <p style="page-break-after: always;">&nbsp;</p> -->

</body>





