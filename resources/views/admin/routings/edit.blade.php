@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.routing.title')</h3>
    
    {!! Form::model($routing, ['method' => 'PUT', 'route' => ['admin.routings.update', $routing->id]]) !!}

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('user_id', trans('quickadmin.routing.fields.user').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('user_id', $users, old('user_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('user_id'))
                        <p class="help-block">
                            {{ $errors->first('user_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('route', trans('quickadmin.routing.fields.route').'', ['class' => 'control-label']) !!}
                    {!! Form::text('route', old('route'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('route'))
                        <p class="help-block">
                            {{ $errors->first('route') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('last_forwarded_to', trans('quickadmin.routing.fields.last-forwarded-to').'', ['class' => 'control-label']) !!}
                    {!! Form::text('last_forwarded_to', old('last_forwarded_to'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('last_forwarded_to'))
                        <p class="help-block">
                            {{ $errors->first('last_forwarded_to') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

