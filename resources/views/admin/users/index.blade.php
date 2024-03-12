@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.users.title')</h3>

    @if( auth()->user()->isAdminorITAdmin())
    <p>
    <strong>US, DS with no displaynames:</strong> <br> 
    
    {{ $nodisplnameusers->implode(', ') }}
   
    </p>

    
    <p>
    <strong>JS, AS with designation discrepancy in empl and user table:</strong> <br>
    To enable this, user's Name should be in the format (JS,AS,SS)|PEN
    <br> 
    
    @php
    echo implode( ', ',  $conflictdesignempl);
    @endphp
   
    </p>
     
    @endif

    <p>
        To hide a user from forwardable usernames, change role to hidden.
    </p>    


    @can('user_create')
    <p>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan

    

    <div class="">
   

        <div class="">
            <table class="table table-bordered table-striped {{ count($users) > 0 ? 'datatable' : '' }} ">
                <thead>
                    <tr>
                      <th>Id</th>
                        <th>@lang('quickadmin.users.fields.name')</th>
                        <!-- <th>@lang('quickadmin.users.fields.email')</th> -->
                        <th>@lang('quickadmin.users.fields.role')</th>
                        <th>@lang('quickadmin.users.fields.username')</th>
                        <th>@lang('quickadmin.users.fields.displayname')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($users) > 0)
                        @foreach ($users as $user)
                            <tr data-entry-id="{{ $user->id }}">
                               
                                <td field-key='id'>{{ $user->id }}</td>

                                <td field-key='name' class="text-nowrap">{{ $user->getAttributes()['name'] }}</td>
                                <!-- <td field-key='email'>{{ $user->email }}</td> -->
                                <td field-key='role'>
                                @foreach($user->roles as $key => $item)
                                    <span class="badge badge-info">{{ $item->title }}</span>
                                @endforeach

                                </td>
                                <td field-key='username' class="text-nowrap">{{ $user->username }}</td>
                                <td field-key='displayname' class="text-nowrap">{{ $user->displayname }}</td>
                                <td class="text-nowrap">
                                    @can('user_view')
                                    <a href="{{ route('admin.users.show',[$user->id]) }}" class="btn btn-sm btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('user_edit')
                                    <a href="{{ route('admin.users.editsimple',[$user->id]) }}" class="btn btn-sm btn-success">SimpleEdit</a>
                                    <a href="{{ route('admin.users.password_reset',[$user->id]) }}" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure to reset password of {{$user->username}}?')">Reset PW</a>
                                    

                                    <a href="{{ route('admin.users.edit',[$user->id]) }}" class="btn btn-sm btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    
                                    @can('user_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.users.destroy', $user->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-sm btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan

                                    @can('user_edit')
                                    @if( $user->isSectionOfficer() || $user->isDSorAbove() )
                                    <a href="{{ route('admin.users.create_dataentry',[$user->id]) }}" class="btn btn-default btn-sm">Create DE</a>
                                    @endif

                                    @endcan
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
        </div>
    </div>


<!-- users -->

@if( auth()->user()->isAdmin())
<div>
        
        <form action="{{url('admin/searches/download_user')}}" method="get" class="form-inline">
           Download User Data 
            <!-- <input class="form-control" type="submit" value="Filter" rel="filter"> -->
            <button type="submit" class="btn btn-primary" rel="filter"><i class="fas fa-fw  fa-download"></i> </button>

        </form>

 </div>       
<br>
<form action="{{url('admin/users/clearold')}}" method="get" class="form-inline">
       List unused User-ids
        <button type="submit" class="btn btn-danger" >Dump</button>
                                 
</form>
@endif
@stop

@section('javascript') 
    <script>
     

    </script>
@endsection