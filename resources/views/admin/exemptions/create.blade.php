@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.exemptions.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.exemptions.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('pen', trans('quickadmin.exemptions.fields.pen').'*', ['class' => 'control-label']) !!}
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
                    {!! Form::label('designation', trans('quickadmin.exemptions.fields.designation').'*', ['class' => 'control-label']) !!}
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
                    {!! Form::label('worknature', trans('quickadmin.exemptions.fields.worknature').'', ['class' => 'control-label']) !!}
                    {!! Form::text('worknature', old('worknature'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('worknature'))
                        <p class="help-block">
                            {{ $errors->first('worknature') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('exemptionform_id', trans('quickadmin.exemptions.fields.exemptionform').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('exemptionform_id', $exemptionforms, old('exemptionform_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('exemptionform_id'))
                        <p class="help-block">
                            {{ $errors->first('exemptionform_id') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

