@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.designations.title')</h3>
    @can('designation_create')
    <p>
        <a href="{{ route('admin.designations.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>

         @if(\Auth::user()->isAdmin())
         <a href="#" class="btn btn-warning" style="margin-left:5px;" data-toggle="modal" data-target="#myModal">@lang('quickadmin.qa_csvImport')</a>
        @include('csvImport.modal', ['model' => 'Designation'])
        @endif
        
    </p>
    @endcan

    

    <div class="">
      

        <div class="">
            <table class="table table-borderless table-striped {{ count($designations) > 0 ? 'datatable' : '' }}">
                <thead>
                    <tr>
                       
                        <th>@lang('quickadmin.designations.fields.designation')</th>
                        <th>@lang('quickadmin.designations.fields.rate')</th>
                        <th>
                            {{ trans('cruds.designation.fields.punching') }}
                        </th>
                        <th>
                            {{ trans('cruds.designation.fields.normal_office_hours') }}
                        </th>
			
                       <th>
                                        {{ trans('cruds.designation.fields.type') }}
                                    </th>
				    <th>
                                        {{ trans('cruds.designation.fields.office_time') }}
                                    </th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($designations) > 0)
                        @foreach ($designations as $designation)
                            <tr data-entry-id="{{ $designation->id }}">
                               

                                <td field-key='designation'>{{ $designation->designation }}</td>
                                <td field-key='rate'>{{ $designation->rate }}</td>
                                <td>
                                    <span style="display:none">{{ $designation->punching ?? '' }}</span>
                                    <input type="checkbox" disabled="disabled" {{ $designation->punching ? 'checked' : '' }}>
                                </td>
                                <td>
                                    {{ $designation->normal_office_hours ?? '' }}
                                </td>
				 <td>
                                            {{ App\Designation::TYPE_SELECT[$designation->type] ?? '' }}
                                        </td>
                                  
                                        <td>
                                            {{ $designation->office_time->groupname ?? '' }}
                                        </td>
                                <td>
                                    @can('designation_view')
                                    <a href="{{ route('admin.designations.show',[$designation->id]) }}" class="btn btn-sm btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('designation_edit')
                                    <a href="{{ route('admin.designations.edit',[$designation->id]) }}" class="btn btn-sm btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('designation_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.designations.destroy', $designation->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-sm btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">@lang('quickadmin.qa_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>



 @if(\Auth::user()->isAdmin())
 <br>

        <form action="{{url('admin/designations/download_desig')}}" method="get" class="form-inline">
            Download Designations 
            <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
            <button type="submit" class="btn btn-primary" rel="filter"><i class="fas fa-fw  fa-download"></i> </button>

        </form>

   
@endif
@stop

@section('javascript') 
    <script>
        // @can('designation_delete')
        //     window.route_mass_crud_entries_destroy = '{{ route('admin.designations.mass_destroy') }}';
        // @endcan

    </script>
@endsection