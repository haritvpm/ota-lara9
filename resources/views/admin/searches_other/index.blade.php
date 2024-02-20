@extends('layouts.app')


@section('content')



    <!-- <h3 class="page-title">Search</h3> -->
    
    <div class="card p-2" id="app">
        <div class="card-title">
            Search Other Dept
        </div>

        <div class="card-body">


	<form action="" method="get" id="filter" class="form-inline">
		<div class="form-group">
        Session <select class="form-control" name="session">
				
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
        Created By <select class="form-control" name="created_by">
       			@if(Auth::user()->isAdmin())
       			 	@foreach($added_bies as $added_by)
				    <option>{{$added_by}}</option>
					@endforeach
       			@else
                <option value="">Any</option>
                <option value="Us" selected>Us</option>
                @endif
        </select>
        </div>
       

         @if(\Auth::user()->isAdmin())
	    	<input  class="form-control" placeholder="Name/Pen" type="text" name = "namefilter" value="{{\Request('namefilter')}}" rel="filter">
    	@else
    		<input  class="form-control" placeholder="Name/Pen" type="text" name = "namefilter" value="{{\Request('namefilter')}}" rel="filter" required>
    	@endif


    	 <!-- <input  class="form-control" placeholder="Designation" type="text" name = "desigfilter" value="{{ \Request('desigfilter')  }}" rel="filter"> -->


        <div class="form-group">                                
        OT <select class="form-control" name="overtime_slot">
                <option value="">All</option>
                <option value="First" {{  \Request('overtime_slot')  == 'First' ? 'selected' : '' }}>First</option>
                <option value="Second" {{ \Request('overtime_slot') == 'Second' ? 'selected' : '' }}>Second</option>
                <option value="Third"  {{ \Request('overtime_slot') == 'Third' ? 'selected' : '' }}>Third</option>
                <option value="Sittings"  {{ \Request('overtime_slot') == 'Sittings' ? 'selected' : '' }}>Sittings</option>
                 <option value="Non-Sittings"  {{ \Request('overtime_slot') == 'Non-Sittings' ? 'selected' : '' }}>Non-Sittings</option>
        </select>
        </div>
         <div class="form-group">     
        	Date <input  class="form-control" placeholder="dd-mm-yyyy" type="search" name = "datefilter" value="{{ \Request('datefilter')}}" >
		</div>
        
         <div class="form-group">                                
        	Status <select class="form-control" name="status">
            <option value="">All</option>
            <option value="Draft" {{  \Request('status')  == 'Draft' ? 'selected' : '' }}>Draft</option>
            <option value="Pending" {{ \Request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="To_approve" {{ \Request('status')== 'To_approve' ? 'selected' : '' }}>To approve</option>
            <option value="Submitted" {{ \Request('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
        
        </select>
          
        <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
        <button type="submit" class="btn btn-danger" rel="filter"><i class="fas fa-fw  fa-search"></i></button>
        <!-- <a href="{{url('admin/searches/')}}" data-toggle="tooltip" title="reset" class="btn btn-default pull-right"><span class="glyphicon glyphicon-remove-circle"></span></a> -->
        </div>
    </form>



    <hr>

  
    

    @if( isset($overtimes))
    <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped table-condensed }}">
            <thead>
                <tr>
                   	
                    <th>Name</th>
                    <!-- <th>Designation</th> -->
                    <th>Created by</th>
                    <th>Slot</th>
                    <th>Duty Date</th>
                    <th>From - To</th>
                    <th>Status</th>
                    <th>Submitted by</th>
                    <th>On</th>
    				<th>OT</th>
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

                            <td >{{ $overtime->pen }}</td>
                            <!-- <td class="small">{{ $overtime->designation }}</td> -->
                            <td >
                            @if( $form->creator == Auth::user()->username )
                                Me
                            @else
                                {{ $form->created_by->name }}
                            @endif
                            </td>
                           
                            <td > 

                                <a href="{{ route('admin.my_forms_others.show',[$form->id]) }}">

                                     @if( $form->overtime_slot == 'First')
                                        1<sup>st</sup>
                                    @elseif( $form->overtime_slot == 'Second')
                                        2<sup>nd</sup>
                                    @elseif( $form->overtime_slot == 'Third')
                                        3<sup>rd</sup>
                                   
                                    @else
                                        Sitting
                                    @endif

                                </a>

                            </td>
                            <td>
                            @if($form->overtime_slot == 'Sittings')
                            
                                {{ $form->date_from }} to {{ $form->date_to }}      
                            
                            @else
                            
                                {{ $form->duty_date }}
                            
                            @endif
                            </td>

                             <td>{{ $overtime->from }} to {{ $overtime->to }}</td>


                                                       
                            <td>
                            @if(Auth::user()->isAdmin())
								@if($form->owner == 'admin')
								 <span class="text-default"><i class="fa fa-thumbs-up" style="color:green"></i> Now at Admin</span> 
								@elseif($form->owner == $form->creator)
								<span class="text-default"><i class="fa fa-edit"></i> Draft</span>
								@else
								<i class="fa fa-mail-forward" style="color:blue"></i> Now at <span class="text-default">{{ $form->OwnedbyName }}</span>
								@endif    

                            @elseif($form->owner == Auth::user()->username)
                                @if($form->owner != $form->creator)
                                <span class="text-default"><i class="fa fa-eye" style="color:red"></i> To approve</span> 
                                @else
                               <span class="text-default"><i class="fa fa-edit"></i> Draft</span>
                                @endif    
                            @else
                                @if($form->owner == 'admin')
                                   <span class="text-default"><i class="fa fa-thumbs-up" style="color:green"></i> Submitted to Accounts</span> 
                                @else
                                <i class="fa fa-mail-forward" style="color:blue"></i> Now at {{ $form->OwnedbyName }}</span>                  
                                @endif
                            
                            @endif

                            </td>
                            <td>{{ $form->SubmitedbyName }}</td>
                            <td>{{ $form->submitted_on }}</td>
                            <td class="text-center">{{ $overtime->count }}</td>
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

        {!! $overtimes->links() !!}          

        </div>
   
  	@endif

  	@if(isset($user_count) && $user_count)

	  	@if( $user_count > 1)
	  	Employees: <strong>{{ isset($user_count)  ? $user_count : ''}} </strong> &emsp;
	  	@endif
	  	Overtimes: <strong>{{ isset($user_count)  ? $total_overtimes : ''}} </strong> &emsp;
	  <!-- 	OT Allowance: ₹ <strong> {{ isset($user_count)  ? $total_amount : ''}} </strong> &emsp; -->
  	@endif
  
  </div>
  </div>

<!-- export -->

<!-- 
cannot trust form no, as a user might have started a form, but waited long to submit it. so submit date is the key. -->


@if( \Auth::user()->isAdmin() )

<div class="card p-2" id="app1">
    <div class="card-title">
        Overtime Data - Others
    </div>

    <div class="card-body">



        <form action="{{url('admin/searches_other/download')}}" method="get" class="form-inline">
            <div class="form-group">
                Session <select class="form-control" name="session">

                    @foreach($sessions as $session)
                    <option>{{$session}}</option>
                    @endforeach

                </select>
            </div>

            <div class="form-group">                                
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
            </div>


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

        
    <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
    <button type="submit" class="btn btn-success" rel="filter"><i class="fas fa-fw  fa-download"></i>Download </button>

</form>

</div>

</div>


<!-- Designations -->

<div class="row">
<div class="col">
<div class="card p-2" id="app2">
    <div class="card-title">
        Designation and Rates - Others
    </div>

    <div class="card-body">

        <form action="{{url('admin/searches_other/download_desig')}}" method="get" class="form-inline">
            

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
        Employee Data Others
    </div>

    <div class="card-body">

        <form action="{{url('admin/searches_other/download_emp')}}" method="get" class="form-inline">
           
            <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
            <button type="submit" class="btn btn-primary" rel="filter"><i class="fas fa-fw  fa-download"></i>Download </button>

        </form>

    </div>
    </div>
</div>
</div>
@endif 


@stop


@section('javascript') 


<script type="text/javascript">



</script>


@endsection