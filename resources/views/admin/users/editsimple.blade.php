@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.users.title')</h3>

     <p>
        Name of officers should start with US, DS etc and followed by space.<br>
        Avoid dots in userid. <br>
        'oo.','sn.', 'de.','od.', 'us.' and 'ds.' are reserved userid starting chars. <br>
        Try to avoid usernames contained within another username. eg. ram and ramu. Use initials to make them dissimilar.


    </p>
<p>
        To hide a user from forwardable usernames (say, on retirement), change role to hidden.
    </p>  
    
    {!! Form::model($user, ['method' => 'PUT', 'route' => ['admin.users.update', $user->id]]) !!}

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', trans('quickadmin.users.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
                       
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('username', trans('quickadmin.users.fields.username').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('username'))
                        <p class="help-block">
                            {{ $errors->first('username') }}
                        </p>
                    @endif
                </div>
            </div>
             <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('displayname', trans('quickadmin.users.fields.displayname').'', ['class' => 'control-label']) !!}
                    {!! Form::text('displayname', old('displayname'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('displayname'))
                        <p class="help-block">
                            {{ $errors->first('displayname') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('role_id', trans('quickadmin.users.fields.role').'*', ['class' => 'control-label']) !!}
                   
                    {!! Form::text('role_id',  old('role_id'), ['class' => 'form-control', 'readonly' => 'true', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('role_id'))
                        <p class="help-block">
                            {{ $errors->first('role_id') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

