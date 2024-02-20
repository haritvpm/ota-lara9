@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">Presets</h3>
  
   
        

 <p>
        Presets let you load a predefined list of employees every time while creating forms. To create a preset, go to My Forms > New Duty Form. After adding all the employee names, click <i>Save As Preset</i> instead of saving the form.
        
    </p>
    <p>
   
        <a href="{{ route('admin.presets.create') }}" class="btn btn-success">Add</a>
       
    </p>

    <div class="">
           

        <div class="">
            <table class="table table-striped {{ count($presets) > 0 ? 'datatable' : '' }} ">
                <thead>
                    <tr>
                      

                        @if(Auth::user()->isAdmin())
                        <th>User</th>
                        @endif
                        <th>Name</th>
                        <th>PENs</th>
                        <th>Updated</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($presets) > 0)
                        @foreach ($presets as $preset)
                            <tr data-entry-id="{{ $preset->id }}">
                                        
                                              
                              
                                @if(Auth::user()->isAdmin())   
                                <td field-key='user'>{{ $preset->user->username . ' / ' . $preset->user->Title }}</td>
                                 @endif
                                <td field-key='name'>{{ $preset->name }}</td>
                                <td field-key='pens'>
                                    @php
                                    echo str_replace(',', ', ',$preset->pens);
                                    @endphp
                                    
                                </td>
                                 <td >
                               {{$preset->updated_at->timezone('Asia/Kolkata')->format('d-m-Y')}}
                                </td>
                                <td class="text-nowrap">
                                  
                                    <a href="{{ route('admin.presets.show',[$preset->id]) }}" class="btn btn-sm btn-primary">@lang('quickadmin.qa_view')</a>
                                  
                                    <a href="{{ route('admin.presets.edit',[$preset->id]) }}" class="btn btn-sm btn-info">@lang('quickadmin.qa_edit')</a>
                                   
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.presets.destroy', $preset->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-sm btn-danger')) !!}
                                    {!! Form::close() !!}
                                   
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
@stop

@section('javascript') 
    <script>
       
        window.route_mass_crud_entries_destroy = '{{ route('admin.presets.mass_destroy') }}';
       
    
    </script>
@endsection