@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.designations.title')</h3>
    
    {!! Form::model($designation, ['method' => 'PUT', 'route' => ['admin.designations.update', $designation->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('designation', trans('quickadmin.designations.fields.designation').'*', ['class' => 'control-label']) !!}
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
                    {!! Form::label('rate', trans('quickadmin.designations.fields.rate').'*', ['class' => 'control-label']) !!}
                    {!! Form::number('rate', old('rate'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('rate'))
                        <p class="help-block">
                            {{ $errors->first('rate') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('designation_mal', trans('quickadmin.designations.fields.designation-mal').'', ['class' => 'control-label']) !!}
                    {!! Form::text('designation_mal', old('designation_mal'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('designation_mal'))
                        <p class="help-block">
                            {{ $errors->first('designation_mal') }}
                        </p>
                    @endif
                </div>
            </div> -->
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

