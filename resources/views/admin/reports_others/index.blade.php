@extends('layouts.app')


@section('content')

<style type="text/css">
@media print {
  a[href]:after {
    content: none !important;
}
   
@page {size: portrait;}


/*
@font-face {
    font-family:'Rachana-Regular';
    src:url("{{URL::asset('fonts/Rachana-Regular.woff')}}") format('woff');
    font-weight: normal;
    font-style: normal;
    font-size:9px;

}

.malfont {
  font-family: 'Rachana-Regular';
}*/
}

</style>


    <h3 class="page-title hidden-print">Reports</h3>
    
    <div class="panel panel-default hidden-print" id="app">
        <div class="panel-heading">
            Report of overtimes
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

        @if(Auth::user()->isAdmin())
        <div class="form-group">                                
        Created By <select class="form-control" name="created_by">
        
                <!-- <option value="all">All</option> -->

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
<!-- 
        <div class="form-group">
            <select class="form-control" name="report_type">
                <option  value="Simple" @if($report_type == 'Simple') selected @endif>Simple</option>
                <option  value="Detailed" @if($report_type == 'Detailed') selected @endif>Detailed</option>
                <option  value="SubmittedbyMe" @if($report_type == 'SubmittedbyMe') selected @endif>Approved by Me</option>
            </select>
        </div> -->


        @if(auth()->user()->isAdminorAudit())

       <!--  <div class="form-group">     
            Submitted upto <input  class="form-control" placeholder="dd-mm-yyyy (<=)" type="text" name = "submitted_before" value="{{ \Request('submitted_before')}}" id="submitted_bef" >
        </div>
        <div class="form-group">     
             on or After <input  class="form-control" placeholder="dd-mm-yyyy (>=)" type="text" name = "submitted_after" value="{{ \Request('submitted_after')}}"  id="submitted_aft" >
        </div> -->

        @endif

        <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
        <button type="submit" class="btn btn-danger" rel="filter"><span class="glyphicon glyphicon-search"></span></button>
       
    </form>

    </div>
     

    </div>

   <hr class="hidden-print">
   @if( /*$report_type == 'Simple' || $report_type == 'Detailed'*/1 )
   @php
   $totalamount = 0;
   setlocale(LC_MONETARY, 'en_IN');
   @endphp
   @if( count($rows) > 0)
	<!-- <h4 class="page-title" style="text-align: center">
	    SECRETARIAT OF THE KERALA LEGISLATURE
	    
	</h4> -->

    <h5 class="page-title" style="text-align: center">
        @php
            $createdbyusername = \Request('created_by');
            $office = isset($added_bies[$createdbyusername]) ? $added_bies[$createdbyusername] : 'All';
            @endphp
       {{ $office }}
      ,&nbsp;
         @if($romankla != null && $sessionnumber_th != null)
         <strong>{!! html_entity_decode($romankla) !!}</strong> KLA,
         <strong>{!! html_entity_decode( $sessionnumber_th) !!}</strong> Session
         @endif

          @if(auth()->user()->isAdmin())
          <br>

          <div class="hidden-print">
            Loaded in {{$timetaken}}<br>
          Total amount : {{ money_format( "%!.0n",$totalamountfromcontroller) }}
          </div>
          @endif
   </h5>


    <div class="table-responsive" style="width: 90%">
        <table class="table table-bordered   table-condensed">
            <thead>

                <tr>
                  	
                    <th>Sl.</th>
                    <th>Name</th>
                     
                    <th>Designation</th>
                                        
                    <th class="text-center">Non-Sitting</th>
                    <th class="text-center">Sitting</th>
                    <th class="text-center">Total OT</th>
                    <th class="text-center">Rate</th>
                    <th class="text-right">Amount</th>
                                       
                </tr>
            </thead>
            
            <tbody>
                           	                        
                      	@foreach ($rows as $key => $value)
						
                        <?php 
					       $name_desig = substr($key,strpos($key,'-')+1);
					    ?>
                           

                        <tr>


                            <td >{{$loop->iteration}}</td>

                            <td >
                            	<span class="text-nowrap">
                                                              

						        {{$rows[$key]['name']}}
                                                             
					        
                                
					    		</span>
                            </td>
                             
                             <td>
                                  {{ $rows[$key]['desig'] }}
                             </td>
                             
                            <td class="text-center">{{ $rows[$key]['ns'] }}</td>
                            <td class="text-center">{{ $rows[$key]['s'] }}</td>
                            <td class="text-center">{{ $rows[$key]['s'] + $rows[$key]['ns'] }}</td>
                            <td class="text-center">{{ $rows[$key]['rate'] }} </td>

                            @php
                              $amountforthis = ($rows[$key]['s'] + $rows[$key]['ns'])*$rows[$key]['rate'];
                              $totalamount += $amountforthis;
                            @endphp

                            <td class="text-right">{{ money_format( "%!.0n",$amountforthis) }}</td>
                                                  
                        </tr>
                    @endforeach
                   
                    <tr>
                        <td colspan="6">
                        </td>
                        <td class="text-center">Total</td>
                        <td class="text-right">
                           <b> {{ money_format( "%.0n",$totalamount)}} </b>
                        </td>

                    </tr>
                 
              
                 

            </tbody>
        </table>
    </div>
    

         
    <br> <br>       
    


     
  <button class="btn btn-primary hidden-print" onClick="window.print()">Print</button>

    @else
    

   @endif 
   <!-- count(rows) -->
 @endif 
 <!-- simple or detailed -->
 
@stop


@section('javascript') 

    @parent


@endsection