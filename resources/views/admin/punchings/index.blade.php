@extends('layouts.app')


@section('content')



  <div class="card p-2" id="app">
        <div class="card-title">
            Fetch Punching API
        </div>

        <div class="card-body">

	<form action="{{url('admin/punchings/fetchApi')}}" method="get" id="filter" class="form-inline">
        
        <div class="form-group">     
            ApiNum <input required class="form-control" placeholder="1 for empl/5 for atten/6 for trace" name = "apinum" value="{{ \Request('apinum')}}" >
		</div>

        <div class="form-group">     
        	Date (for 5/6) <input class="form-control" placeholder="dd-mm-yyyy" name = "reportdate" value="{{ \Request('reportdate')}}" >
		</div>
	  
        <div class="form-group">                                
                  
        <button type="submit" class="btn btn-danger" rel="filter"><span class="glyphicon glyphicon-refresh"></span></button>

        </div>
    </form>

  </div>
  </div>
  
  <div class="card p-2" id="app">
    <!-- <h3 class="page-title">Search</h3> -->
     
        <div class="card-title">
            Search Punching
        </div>

        <div class="card-body">


	<form action="" method="get" id="filter" class="form-inline">
	       
        <div class="form-group">     
        	Date <input required class="form-control" placeholder="dd-mm-yyyy" type="search" name = "datefilter" value="{{ \Request('datefilter')}}" >
		</div>
              

        @if(\Auth::user()->isAdmin())
	    	<input  class="form-control" placeholder="AttendanceId/PEN" type="text" name = "namefilter" value="{{\Request('namefilter')}}" rel="filter">
    	@else
    		<input  class="form-control" placeholder="AttendanceId/PEN" type="text" name = "namefilter" value="{{\Request('namefilter')}}" rel="filter" required>
    	@endif
    	
    


    	 <!-- <input  class="form-control" placeholder="Designation" type="text" name = "desigfilter" value="{{ \Request('desigfilter')  }}" rel="filter"> -->

      
         <div class="form-group">                                
                  
        <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
        <button type="submit" class="btn btn-primary" rel="filter"><i class="fas fa-fw  fa-search"></i></button>
        <!-- <a href="{{url('admin/searches/')}}" data-toggle="tooltip" title="reset" class="btn btn-default pull-right"><span class="glyphicon glyphicon-remove-circle"></span></a> -->
        </div>
    </form>



    <hr>

  
    

    @if( isset($punchings))
    <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped table-condensed }}">
            <thead>
                <tr>
                   
                    <th>PEN</th>
                    <th>AttendanceId</th>
                    <th>Duty Date</th>
                    <th>Punchin</th>
                    <th>Punchout</th>
                    <th>Created by</th>
                    <th>
                                        &nbsp;
                                    </th>
                </tr>
            </thead>
            
            <tbody>
                @if( count($punchings) > 0)
                      	@foreach ($punchings as $punching)
                     
			          
                        <tr>

                            <td >{{ $punching->pen }}</td>
                            <td >{{ $punching->aadhaarid }}</td>
                            <td >{{ $punching->date }}</td>
                            <td >{{ $punching->punch_in }}</td>
                            <td >{{ $punching->punch_out }}</td>
                            <!-- <td class="small">{{ $punching->designation }}</td> -->
                            <td >
                           <!-- $form->creator == Auth::user()->username -->
                               
                               {{ $punching->creator }}
                            
                            </td>
                            <td>
                                           
                                            
                                                <a class="btn btn-sm btn-info" href="{{ route('admin.punchings.edit', $punching->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                           

                                           
                                                <form action="{{ route('admin.punchings.destroy', $punching->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                          

                                        </td>
                         
                        </tr>
                    @endforeach
                 
                @else
                    <tr>
                        <td colspan="11">@lang('quickadmin.qa_no_entries_in_table')</td>
                    </tr>
                @endif

                              

            </tbody>
        </table>

        {!! $punchings->links() !!}          

        </div>
   
  	@endif

  
  </div>
  </div>
  

<!-- export -->

<!-- 
cannot trust form no, as a user might have started a form, but waited long to submit it. so submit date is the key. -->


@stop


@section('javascript') 


<script type="text/javascript">



</script>


@endsection