@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.overtimes-others.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.overtimes_others.store']]) !!}

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('pen', trans('quickadmin.overtimes-others.fields.pen').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('pen', old('pen'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('pen'))
                        <p class="help-block">
                            {{ $errors->first('pen') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('designation', trans('quickadmin.overtimes-others.fields.designation').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('designation', old('designation'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('designation'))
                        <p class="help-block">
                            {{ $errors->first('designation') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('form_id', trans('quickadmin.overtimes-others.fields.form').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('form_id', $forms, old('form_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('form_id'))
                        <p class="help-block">
                            {{ $errors->first('form_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('from', trans('quickadmin.overtimes-others.fields.from').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('from', old('from'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('from'))
                        <p class="help-block">
                            {{ $errors->first('from') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('to', trans('quickadmin.overtimes-others.fields.to').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('to', old('to'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('to'))
                        <p class="help-block">
                            {{ $errors->first('to') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('count', trans('quickadmin.overtimes-others.fields.count').'*', ['class' => 'control-label']) !!}
                    {!! Form::number('count', old('count'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('count'))
                        <p class="help-block">
                            {{ $errors->first('count') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('worknature', trans('quickadmin.overtimes-others.fields.worknature').'', ['class' => 'control-label']) !!}
                    {!! Form::text('worknature', old('worknature'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('worknature'))
                        <p class="help-block">
                            {{ $errors->first('worknature') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

