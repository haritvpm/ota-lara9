@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.designations-other.title')</h3>
    @can('designations_other_create')
    <p>
        <a href="{{ route('admin.designations_others.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>

         @if(\Auth::user()->isAdmin())
         <a href="#" class="btn btn-warning" style="margin-left:5px;" data-toggle="modal" data-target="#myModal">@lang('quickadmin.qa_csvImport')</a>
        @include('csvImport.modal', ['model' => 'DesignationsOther'])
        @endif
        
        
    </p>
    @endcan

    

    <div class="">


        <div >
            <table class="table table-bordered table-striped {{ count($designations_others) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                       <th>Id</th>
                        <th>@lang('quickadmin.designations-other.fields.designation')</th>
                        
                        <th>@lang('quickadmin.designations-other.fields.rate')</th>

                        @if(Auth::user()->isAdmin())  
                        <th>User</th>
                        <th>Max Allowed</th>
                        @endif
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($designations_others) > 0)
                        @foreach ($designations_others as $designations_other)
                            <tr data-entry-id="{{ $designations_other->id }}">
                                <td field-key='id'>{{ $designations_other->id }}</td>
                                <td field-key='designation'>{{ $designations_other->designation }}</td>
                                <td field-key='rate'>{{ $designations_other->rate }}</td>

                                @if(Auth::user()->isAdmin())  
                                <td field-key='user'>{{ $designations_other->user->name }}</td>
                                <td field-key='max_persons'>{{ $designations_other->max_persons }}</td>
                                @endif
                              
                                <td>
                                    @can('designations_other_view')
                                    <a href="{{ route('admin.designations_others.show',[$designations_other->id]) }}" class="btn btn-sm btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('designations_other_edit')
                                    <a href="{{ route('admin.designations_others.edit',[$designations_other->id]) }}" class="btn btn-sm btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('designations_other_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.designations_others.destroy', $designations_other->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-sm btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">@lang('quickadmin.qa_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>


 @if(\Auth::user()->isAdmin())
 <br>

        <form action="{{url('admin/designations_others/download_desig')}}" method="get" class="form-inline">
            Download Designations (OD)
            <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
            <button type="submit" class="btn btn-primary" rel="filter"><i class="fas fa-fw  fa-download"></i> </button>

        </form>

   
@endif

@stop

@section('javascript') 
    <script>
       
    </script>
@endsection