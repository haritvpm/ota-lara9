@extends('layouts.app')

@section('content')
    <h3 class="page-title">Presets</h3>
    
    {!! Form::model($preset, ['method' => 'PUT', 'route' => ['admin.presets.update', $preset->id]]) !!}

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="card-body">
            <div class="row">
                @if(Auth::user()->isAdmin())     
                <div class="col-xs-12 form-group">
                    {!! Form::label('user_id', 'User*', ['class' => 'control-label']) !!}
                    {!! Form::select('user_id', $users, old('user_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('user_id'))
                        <p class="help-block">
                            {{ $errors->first('user_id') }}
                        </p>
                    @endif
                </div>
                 @endif
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', 'Name of Preset*', ['class' => 'control-label']) !!}
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
                    {!! Form::label('pens','PENs (comma seperated)*', ['class' => 'control-label']) !!}
                    {!! Form::textarea('pens', old('pens'), ['class' => 'form-control ', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('pens'))
                        <p class="help-block">
                            {{ $errors->first('pens') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

