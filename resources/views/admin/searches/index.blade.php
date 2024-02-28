@extends('layouts.app')


@section('content')

    <!-- <h3 class="page-title">Search</h3> -->
    
    <div class="" id="app">
        <div class="">
            Search
        </div>

        <div class="">
            <p>Note: only the <i>Name</i> field is required. Other fields are optional.</p>

	<form action="" method="get" id="filter" class="form-inline">
		<div class="form-group">
        <select class="form-control" name="session">
				
				@foreach($sessions as $session)
                @if($session == \Request('session'))
                   <option selected>{{$session}}</option>
                @else
                    <option>{{$session}}</option>
                @endif
                @endforeach
				        
        </select>
        </div>

       

        <div class="form-group">                                
        <select class="form-control" name="created_by">
       			@if(Auth::user()->isAdminorAudit())
       			 	@foreach($added_bies as $added_by)
				    <option  {{ \Request('created_by') == $added_by ? 'selected' : '' }} >{{$added_by}}</option>
					@endforeach
       			@else
                <option value="any">Created By: Any</option>
                <option value="Us" {{ \Request('created_by') == 'Us' ? 'selected' : '' }}>Created By: Us</option>
                @endif
        </select>
        </div>
       

         @if(\Auth::user()->isAdminorAudit())
	    	<input  class="form-control" placeholder="Name/PEN" type="text" name = "namefilter" value="{{\Request('namefilter')}}" rel="filter">
    	@else
    		<input  class="form-control" placeholder="Name/PEN" type="text" name = "namefilter" value="{{\Request('namefilter')}}" rel="filter" required>
    	@endif


    	 <input  class="form-control" placeholder="Designation" type="text" name = "desigfilter" value="{{ \Request('desigfilter')  }}"  list="desiglist">


         <div class="form-group">     
        	<input  class="form-control" placeholder="dd-mm-yyyy" type="search" name = "datefilter" value="{{ \Request('datefilter')}}" >
		</div>
        

        <div class="form-group"> 

        @if(!Auth::user()->isAudit())
        <select class="form-control" name="status">
            <option value="" {{ \Request('status') == '' ? 'selected' : '' }}>All</option>
            <option value="Draft" {{  \Request('status')  == 'Draft' ? 'selected' : '' }}>Draft</option>
            <option value="Pending" {{ \Request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="To_approve" {{ \Request('status')== 'To_approve' ? 'selected' : '' }}>To approve</option>
            <option value="Submitted" {{ \Request('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
        
        </select>
        @endif
        
        <!-- @if(Auth::user()->isAdmin())
        <input  class="form-control" placeholder="WorkNature" type="text" name = "worknaturefilter" value="{{\Request('worknaturefilter')}}" rel="filter">
        @endif -->


        <button type="submit" class="btn btn-danger" rel="filter"> <i class="fas fa-fw  fa-search"></i></button>
        <!-- <a href="{{url('admin/searches/')}}" data-toggle="tooltip" title="reset" class="btn btn-default pull-right"><i class="fa fa-minus-circle" aria-hidden="true"></i></a> -->
        </div>
    </form>





  
    

    @if( isset($overtimes))

        @if(isset($user_count) && $user_count)

        @if( $user_count > 1)
        Employees: <strong>{{ isset($user_count)  ? $user_count : ''}} </strong> &emsp;
        @endif
        
        Overtimes: <strong>{{ $total_overtimes }} </strong> &emsp;<br> 
        OT Allowance* :
        @if( $total_amount != $total_amount_submitted ) 
         ₹ <strong>{{$total_amount }}</strong>&nbsp;(all), 
        @endif
        ₹ <strong> <span class="text-success" > {{$total_amount_submitted}}</span></strong>&nbsp;(submitted)
        
               
        * <span class="text-muted small" > (Subject to approval and cross-checking with absentee report).</span> 

    @endif

    <div class="">
        <table class="table table-bordered table-striped table-sm }}">
            <thead>
                <tr>
                   	
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Created by</th>
                   
                    <th>Duty Date</th>
                    <th>From - To</th>
                    <th>Status</th>
                    @if(Auth::user()->isAdmin())
                    <th>Submitted by</th>
                    <th>On</th>
                    @endif
    				<th>OTA</th>
                    <!-- <th>Rate</th> -->
                    <!-- <th>&nbsp;</th> -->
                   
                </tr>
            </thead>
            
            <tbody>
                @if( count($overtimes) > 0)
                      	@foreach ($overtimes as $overtime)
                    	 
			            <?php
			            $form = $overtime->form;
			            ?> 
                        <tr>

                            <td class="text-nowrap">{{ $overtime->PENName }}</td>
                            <td class="small">{{ $overtime->designation }}</td>
                            <td >
                            @if( $form->creator == Auth::user()->username )
                                Me
                            @else
                                 @if(optional($form->created_by)->Title != null)
                                    {{ optional($form->created_by)->Title }}<small> ({{$form->creator}})</small>
                                @else
                                    {{$form->creator}}
                                @endif
                            @endif
                            </td>
                           
                            
                            <td>
                            @if($form->overtime_slot == 'Sittings')
                            
                                <!-- {{ $form->date_from }} to {{ $form->date_to }}   -->    
                            
                            @else
                            
                                {{ $form->duty_date }}
                            
                            @endif
                            </td>

                            <td>
                             @if($form->overtime_slot == 'Sittings')
                                {{ $overtime->from }} to 
                                {{ $overtime->to }}
                                @else
                                {{ date("h:i a", strtotime($overtime->from)) }} to 
                                {{ date("h:i a", strtotime($overtime->to)) }}
                                @endif

                            </td>


                                                       
                            <td>
                            @if(Auth::user()->isAdminorAudit())
								@if($form->owner == 'admin')
								 <span class="text-default"><i class="fa fa-thumbs-up" style="color:green"></i>  at Admin</span>  
								@elseif($form->owner == $form->creator)
								<span class="text-default"><i class="fa fa-edit"></i> Draft</span>
								@else
								<i class="fa fa-mail-forward" style="color:blue"></i> <small>
                                at <span class="text-default">{{ $form->OwnedbyName }}</span></small>
								@endif    

                            @elseif($form->owner == Auth::user()->username)
                                @if($form->owner != $form->creator)
                               <span class="text-default"><i class="fa fa-eye" style="color:red"></i> To approve</span> 
                                @else
                               <span class="text-default"><i class="fa fa-edit"></i> Draft</span>
                                @endif    
                            @else
                                @if($form->owner == 'admin')
                                   <span class="text-default"><i class="fa fa-thumbs-up" style="color:green"></i> <small>Submitted to Accounts </small></span> 
                                @else
                               <i class="fa fa-mail-forward" style="color:blue"></i>  <small>Now at {{ $form->OwnedbyName }} </small></span>                  
                                @endif
                            
                            @endif

                            @if($form->form_no < 0)
                            <span class="small" style="color:red"> (Withheld)</span>
                            @endif

                            </td>

                            @if(Auth::user()->isAdmin())
                            <td> <small>{{ $form->SubmitedbyName }}  </small></td>
                            <td> <small>{{ $form->submitted_on }} </small>
                            </td>
                            @endif

                            <td class="text-center">
                                <a href="{{ route('admin.my_forms2.show',[$form->id]) }}">
                                 {{ $overtime->count }}

                                </a>   
                                                   
                            </td>
                            <!-- <td >₹ {{ $overtime->rate }}</td> -->
                            <!-- <td>
                            	@if(Auth::user()->isAdmin())	
                            	<a href="{{ route('admin.my_forms2.show',[$form->id]) }}">View</a>
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

        {!! $overtimes->links() !!}
         @if ($overtimes->total() > 0)
        <div><small>Showing {{($overtimes->currentpage()-1)*$overtimes->perpage()+1}} to {{(($overtimes->currentpage()-1)*$overtimes->perpage())+$overtimes->count()}}
        of  {{$overtimes->total()}} entries
        </small></div>          
         @endif       

        </div>
   
  	@endif

  
  
  </div>
  </div>

<!-- export -->

<!-- 
cannot trust form no, as a user might have started a form, but waited long to submit it. so submit date is the key. -->


@if( \Auth::user()->isAdmin() )

<div class="card p-2" id="app1">
    <div class="card-title">
        Overtime Data
    </div>

    <div class="card-body">



        <form action="{{url('admin/searches/download')}}" method="get" class="form-inline">
            <div class="form-group ">
                Session <select class="form-control" name="session">

                    @foreach($sessions as $session)
                    <option>{{$session}}</option>
                    @endforeach

                </select>
            </div>

           <!--  <div class="form-group">                                
                Created By <select class="form-control" name="created_by">
                    @if(Auth::user()->isAdmin())
                    @foreach($added_bies as $added_by)
                    <option>{{$added_by}}</option>
                    @endforeach
                    @else
                    <option value="" selected>Any</option>
                    <option value="Us">Us</option>
                    @endif
                </select>
            </div> -->


           <!--  <div class="form-group">                                
                OT <select class="form-control" name="overtime_slot">
                    <option value="">All</option>
                    <option value="First" {{  \Request('overtime_slot')  == 'First' ? 'selected' : '' }}>First</option>
                    <option value="Second" {{ \Request('overtime_slot') == 'Second' ? 'selected' : '' }}>Second</option>
                    <option value="Third"  {{ \Request('overtime_slot') == 'Third' ? 'selected' : '' }}>Third</option>

                    <option value="Sittings"  {{ \Request('overtime_slot') == 'Sittings' ? 'selected' : '' }}>Sittings</option>
                    <option value="Non-Sittings"  {{ \Request('overtime_slot') == 'Non-Sittings' ? 'selected' : '' }}>Non-Sittings</option>

                </select>
            </div> -->
            <div class="form-group">     
                Submitted upto <input  class="form-control" placeholder="dd-mm-yyyy (<=)" type="text" name = "submitted_before" value="{{ \Request('submitted_before')}}" >
            </div>
            <div class="form-group">     
                Submitted on or After <input  class="form-control" placeholder="dd-mm-yyyy (>=)" type="text" name = "submitted_after" value="{{ \Request('submitted_after')}}" >
            </div>

            <div class="form-group">     
                Form No upto <input  class="form-control" placeholder=" (<=)" type="text" name = "formno_before" value="{{ \Request('formno_before')}}" >
            </div>
            <div class="form-group">     
                Form No After <input  class="form-control" placeholder=" (>=)" type="text" name = "formno_after" value="{{ \Request('formno_after')}}" >
            </div>



         <!-- <div class="form-group">                                
            Status <select class="form-control" name="status">
            <option value="">All</option>
            <option value="Draft" {{  \Request('status')  == 'Draft' ? 'selected' : '' }}>Draft</option>
            <option value="Pending" {{ \Request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="To_approve" {{ \Request('status')== 'To_approve' ? 'selected' : '' }}>To approve</option>
            <option value="Submitted" selected {{ \Request('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
        
        </select>
    </div> -->
    <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
    <button type="submit" class="btn btn-success" rel="filter"><i class="fas fa-fw  fa-download"></i>Download </button>

</form>

</div>

</div>


<!-- calender -->
<div class="row">
<div class="col">
<div class="card p-2" id="app2">
    <div class="card-title">
        Calender
    </div>

    <div class="card-body">

        <form action="{{url('admin/searches/download_calender')}}" method="get" class="form-inline">
            <div class="form-group">
                <select class="form-control" name="session">

                    @foreach($sessions as $session)
                    <option>{{$session}}</option>
                    @endforeach

                </select>
            </div>



            <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
            <button type="submit" class="btn btn-info" rel="filter"><i class="fas fa-fw  fa-download"></i> Download</button>

        </form>

    </div>
</div>
</div>
<!-- Designations -->
<div class="col">
<div class="card p-2" id="app3">
    <div class="card-title">
        Designation and Rates
    </div>

    <div class="card-body">

        <form action="{{url('admin/searches/download_desig')}}" method="get" class="form-inline">
            
            <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
            <button type="submit" class="btn btn-warning" rel="filter"><i class="fas fa-fw  fa-download"></i>Download </button>

        </form>

    </div>
</div>
</div>
<!-- Employees -->

<div class="col">
<div class="card p-2" id="app3">
    <div class="card-title">
        Employee Data 
    </div>

    <div class="card-body">

        <form action="{{url('admin/searches/download_emp')}}" method="get" class="form-inline">
           
            <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
            <button type="submit" class="btn btn-primary" rel="filter"><i class="fas fa-fw  fa-download"></i> Download</button>

        </form>

    </div>
</div>
</div> <!-- downloads -->

@endif 


@stop


@section('javascript') 


<script type="text/javascript">



</script>


@endsection