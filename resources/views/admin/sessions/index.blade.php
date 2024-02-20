@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.sessions.title')</h3>
    @can('session_create')
    <p>
        <a href="{{ route('admin.sessions.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="">
     

        <div class="">
            <table class="table table-bordered table-striped {{ count($sessions) > 0 ? 'datatable' : '' }} ">
                <thead>
                    <tr>
                       

                        <th>@lang('quickadmin.sessions.fields.name')</th>
                        @if(auth()->user()->isAdmin())
                        <th>@lang('quickadmin.sessions.fields.kla')</th>
                        <th>@lang('quickadmin.sessions.fields.session')</th>
                        <th>@lang('quickadmin.sessions.fields.dataentry-allowed')</th>
                        <th>@lang('quickadmin.sessions.fields.show-in-datatable')</th>
                        @endif
                         <th>@lang('quickadmin.sessions.fields.sittings-entry')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($sessions) > 0)
                        @foreach ($sessions as $session)
                            <tr data-entry-id="{{ $session->id }}">
                                

                                <td field-key='name'>{{ $session->name }}</td>
                                @if(auth()->user()->isAdmin())
                                <td field-key='kla'>{{ $session->kla }}</td>
                                <td field-key='session'>{{ $session->session }}</td>
                                <td field-key='dataentry_allowed'>{{ $session->dataentry_allowed }}</td>
                                <td field-key='show_in_datatable'>{{ $session->show_in_datatable }}</td>
                                @endif
                                <td field-key='sittings_entry'>{{ $session->sittings_entry }}</td>
                                <td>
                                    @can('session_view')
                                    <a href="{{ route('admin.sessions.show',[$session->id]) }}" class="btn btn-sm btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('session_edit')
                                    <a href="{{ route('admin.sessions.edit',[$session->id]) }}" class="btn btn-sm btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10">@lang('quickadmin.qa_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        

    </script>
@endsection