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


<div class="card">
    <div class="card-title">
       
    PA to MLA Form
   
     <small> No.{{ $form->id }} </small>
    </div>

    <div class="panel-body table-responsive">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-condensed">
                    <tr>
                        <th>@lang('quickadmin.forms.fields.session')</th>
                        <td field-key='session'>{{ $form->session }}</td>
                        <th>Created/Updated</th>
                        <td>{{ date('d-m-Y', strtotime($form->created_at)) }}, {{ date('d-m-Y', strtotime($form->updated_at)) }}</td>
                    </tr>

                    <tr>
                        <th>Created by</th>
                        <td field-key='creator'>Admin
                        </td>

                        <th>Period</th>
                        <td field-key='date_from'>From {{$form->date_from}} to {{$form->date_to}}</td>
                       

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
                   
                   
                    <th>Total days attended</th>
                    
                   
                   
                    <th>Remarks</th>
                   


                </tr>
            </thead>

            <tbody>
                @if (count($overtimes) > 0)
                @foreach ($overtimes as $overtime)
                <tr data-entry-id="{{ $overtime->id }}">

                    <td>{{  $loop->iteration }}</td>
                    <td field-key='pen'>{{ $overtime->pen }}</td>
                    <td field-key='designation'>{{ $overtime->designation }}</td>
                   
                   
                    <td class="text-center" field-key='count'>{{ $overtime->count }}</td>
                    
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
 <div >
    <a href="{{route('admin.pa2mlaforms.index')}}" class="btn btn-default hidden-print"><i class="fa fa-arrow-left"></i>&nbsp;@lang('quickadmin.qa_back_to_list')</a>

    @if( Auth::user()->username == $form->owner)
                                      
    {!! Form::open(array(
      
        'style' => 'display: inline-block;',
        'method' => 'DELETE',
        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
        'route' => ['admin.pa2mlaforms.destroy', $form->id])) !!}
    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-default')) !!}
    {!! Form::close() !!}

     <a href="{{ route('admin.pa2mlaforms.edit',[$form->id]) }}" class="btn btn-default">@lang('quickadmin.qa_edit')</a>
     
    @endif

 </div>
</div>



@stop



@section('javascript') 


@endsection