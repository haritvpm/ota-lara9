@extends('layouts.app')


@section('content')

<style type="text/css">
@media print {
  a[href]:after {
    content: none !important;
}
   
@page {size: portrait;}



@font-face {
    font-family:'Rachana-Regular';
    src:url("{{URL::asset('fonts/Rachana-Regular.woff')}}") format('woff');
    font-weight: normal;
    font-style: normal;
    font-size:9px;

}

.malfont {
  font-family: 'Rachana-Regular';
}
}

</style>


    <h3 class="page-title hidden-print">Reports</h3>
    
    <div class="panel panel-default hidden-print" id="app">
        <div class="panel-heading">
            Make reports of <i>submitted</i> overtime forms
        </div>

    <div class="panel-body">

	<form action="" method="get" id="filter" class="form-inline">
		<div class="form-group">
            Session <select class="form-control" name="session">

                @foreach($sessions as $sess)
                @if($sess == \Request('session'))
                <option selected>{{$sess}}</option>
                @else
                <option>{{$sess}}</option>
                @endif
                @endforeach

            </select>
        </div>

        @if(Auth::user()->isAdminorAudit())
        <div class="form-group">                                
        Created By <select class="form-control" name="created_by">
        
                <option value="all">All</option>

                @foreach($added_bies as $val => $added_by)
                @if($val == \Request('created_by'))
                <option value="{{$val}}" selected>{{$added_by}} ({{$val}})</option>
                @else
                     <option value="{{$val}}">{{$added_by}} ({{$val}})</option>
                @endif
                @endforeach
                              
        </select>
        </div>
        @endif

        <div class="form-group">
            <select class="form-control" name="report_type">
                <option  value="Simple" @if($report_type == 'Simple') selected @endif>Simple</option>
                <option  value="Detailed" @if($report_type == 'Detailed') selected @endif>Detailed</option>
                <option  value="SubmittedbyMe" @if($report_type == 'SubmittedbyMe') selected @endif>Approved by Me</option>
            </select>
        </div>



        @if(auth()->user()->isAdminorAudit())

        <div class="form-group">     
            Submitted upto <input  class="form-control" placeholder="dd-mm-yyyy (<=)" type="text" name = "submitted_before" value="{{ \Request('submitted_before')}}" id="submitted_bef" >
        </div>
        <div class="form-group">     
             on or After <input  class="form-control" placeholder="dd-mm-yyyy (>=)" type="text" name = "submitted_after" value="{{ \Request('submitted_after')}}"  id="submitted_aft" >
        </div>

        @endif

        <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
        <button type="submit" class="btn btn-danger" rel="filter"><span class="glyphicon glyphicon-search"></span></button>
       
    </form>

    </div>
     

    </div>

   <hr class="hidden-print">
   @if( /*$report_type == 'Simple' || $report_type == 'Detailed'*/1 )
   @if( count($rows) > 0)
	<h4 class="page-title" style="text-align: center">
	    SECRETARIAT OF THE KERALA LEGISLATURE
	    
	</h4>
	<p>
        @if($romankla != null && $sessionnumber_th != null)
		 <strong>{!! html_entity_decode($romankla) !!}</strong> KLA,
         <strong>{!! html_entity_decode( $sessionnumber_th) !!}</strong> Session
        @endif
        <br> 
        @if(!auth()->user()->isAdminorAudit())
		Section/Office: {{\Auth::user()->Title}}
        @else
            @php
            $createdbyusername = \Request('created_by');
            $office = isset($added_bies[$createdbyusername]) ? $added_bies[$createdbyusername] 
                                                                                : 'All';
            @endphp
        Section/Office: {{ $office }}
        @endif
	</p>
    

    <div class="table-responsive">
        <table class="table table-bordered  table-condensed">
            <thead>
                <tr>
                   	
                    <th>Name</th>
                     @if( $report_type == 'Detailed' )
                    <th>Designation</th>
                    @endif
                    
                    @if( $report_type == 'Simple' || $report_type == 'SubmittedbyMe')
                    <th>OT (except Sittings)</th>
                    @endif
                    <th class="text-center">NS</th>
                    <th class="text-center">Sit.</th>
                    <th class="text-center">Tot.</th>
                                       
                </tr>
            </thead>
            
            <tbody style="font-size:12px;">
                @if( count($rows) > 0)
                	                        
                      	@foreach ($rows as $key => $value)
						
                        <?php 
					       $name_desig = substr($key,strpos($key,'-')+1);
					    ?>
                           

                        <tr>

                            <td >
                            	<span class="text-nowrap">
                                     {{$loop->iteration}}. 
	                            <?php 

						          //echo substr($name_desig,0,strpos($name_desig,','));
                                    echo $rows[$key]['name'];
                                  if( $report_type == 'Simple' || $report_type == 'SubmittedbyMe'){
                                     echo '<br>' . '&emsp;';
                                  }
                                  

						        ?>
                                @if( $report_type == 'Simple')
                                {{ $rows[$key]['desig'] }}
                                @endif
					    		</span>
                            </td>
                             @if( $report_type == 'Detailed' )
                             <td>
                                  {{ $rows[$key]['desig'] }}
                             </td>
                             @endif

                            @if( $report_type == 'Simple' || $report_type == 'SubmittedbyMe' )
                            <td>
                             	@foreach ($value as $m => $date)
                             		@if( $m != 's' && $m != 'ns' && $m != 'desig' && $m != 'name')
						                <strong>{{ $m }}</strong> : 
						                <?php 
                                            $str = '';
                                            ksort($date);
                                        ?>
                                 

						                @foreach ($date as $d => $v) 
						                
                                        <?php 

                                        if($report_type != 'SubmittedbyMe'){
                                            
                                            $ns = '0';

                                            if($v & F_SITTING){
                                                $ns = 'S';
                                            }
                                            else
                                            if($v & F_FIRST){
                                                $ns = '1';
                                            }
                                            
                                      
                                            $ns .= $v & F_SECOND ? '<small>+</small>1' : '<small>+</small>0';
                                            
                                            if($v & F_THIRD){
                                                $ns .=  '<small>+</small>1';
                                            }
                                            if($v & F_ADDITIONAL){
                                                $ns .=  '<small>+</small>1';
                                            }

                                            if($v & F_SITTING){
						                      $str .= " <u>$d</u>($ns),  ";
                                            }
                                            else{
                                              $str .= " $d($ns),  ";   
                                            }
						                    
                                        } else {
                                            $ns = '';

                                            if($v & F_SITTING){
                                                $ns = '';
                                            }
                                            else
                                            if($v & F_FIRST){
                                                $ns = '1<sup>st</sup>';
                                            }
                                            
                                      
                                            $ns .= $v & F_SECOND ? '2<sup>nd</sup>' : '';
                                            
                                            if($v & F_THIRD){
                                                $ns .=  '3<sup>rd</sup>';
                                            }
                                            if($v & F_ADDITIONAL){
                                                $ns .=  'A';
                                            }

                                            

                                            if($v & F_SITTING){
                                              $str .= " <u>$d</u>($ns),  ";
                                            }
                                            else{
                                              $str .= " $d($ns),  ";   
                                            }
                                        }

						            	?>				                
						                @endforeach

						                
										<?php echo rtrim($str," ,");?>
										
						                
					            	
					            	@endif
					            @endforeach
                            </td>
                            @endif

                            <td class="text-center">{{ $rows[$key]['ns'] }}</td>
                            <td class="text-center">{{ $rows[$key]['s'] }}</td>
                            <td class="text-center">{{ $rows[$key]['s'] + $rows[$key]['ns'] }}</td>
                         
                         
                        </tr>
                    @endforeach
                   

                 
                @else
                    <tr>
                        <td colspan="11">@lang('quickadmin.qa_no_entries_in_table')</td>
                    </tr>
                @endif

                              

            </tbody>
        </table>
    </div>
    
    @if(isset($overtimes ) && $report_type == 'Detailed')<!-- detailed report -->

    <h4 class="text-center">Detailed Report</h4>

    <div class="panel-body table-responsive">
        <table class="table table-bordered  table-condensed }}">
            <thead style="font-size:11px;">
                <tr>
                    
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Created</th>
                    <th>Type</th>
                    <th>Duty Date</th>
                    <th>From - To</th>
                  
                    <th class="text-center">OTA</th>
                    <th>Nature of work</th>
                    <!-- <th>&nbsp;</th> -->
                   
                </tr>
            </thead>
            
            <tbody style="font-size:11px;">
                @if( count($overtimes) > 0)
                        @foreach ($overtimes as $overtime)
                         
                        <?php
                        $form = $overtime->form;
                        ?> 
                        <tr>

                            <td  class="text-nowrap">{{ $overtime->NameOnly }}</td>
                            <td  class="text-nowrap">{{ $overtime->designation }}</td>
                            <td  class="text-nowrap small">{{ $form->CreatorSection }}</td>
                           
                            <td > 

                                <a href="{{ route('admin.my_forms.show',[$form->id]) }}">
                                    {{$form->overtime_slot}}
                                   

                                </a>

                            </td>
                            <td>
                            @if($form->overtime_slot == 'Sittings')
                            
                                {{ $form->date_from }} to {{ $form->date_to }}      
                            
                            @else
                            
                                {{ $form->duty_date }}
                            
                            @endif
                            </td>

                            <td>
                            @if( $overtime->from != '')
                                {{ $overtime->from }} to {{ $overtime->to }}
                            @endif

                            </td>


                           
                            <td class="text-center">{{ $overtime->count }}</td>
                            <td  class="text-nowrap">
                            
                                {{ $overtime->worknature }}
                           

                            </td>

                            <!-- <td >₹ {{ $overtime->rate }}</td> -->
                            <!-- <td>
                                @if(Auth::user()->isAdmin())    
                                <a href="{{ route('admin.my_forms.show',[$form->id]) }}">View</a>
                                @endif

                            </td> -->

                        </tr>
                    @endforeach
                 
                @else
                    <tr>
                        <td colspan="11">@lang('quickadmin.qa_no_entries_in_table')</td>
                    </tr>
                @endif

                              

            </tbody>
        </table>

            

       </div>
     
        
    @endif <!-- detailed report -->
   


    <!-- submitted by me report -->




    
    @if(!auth()->user()->isAdmin())
    
    <br> <br>       
    <div style="font-size:9px;" class="visible-print pull-right"><br><br><br>Signature, Name and Designation of Officer</div>

    <div style="font-size:9px;" class="visible-print"><br><br><br>Countersigned by</div>
    @endif

 

  <button class="btn btn-primary hidden-print" onClick="window.print()">Print</button>

    @else
    
    Nothing to show
   @endif 
   <!-- count(rows) -->
 @endif 
 <!-- simple or detailed -->
 
@stop


@section('javascript') 

    @parent
<script type="text/javascript">

   $('#submitted_bef').datepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}"
        });
    $('#submitted_aft').datepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}"
        });

</script>


@endsection