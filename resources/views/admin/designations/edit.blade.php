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

            <div class="form-group {{ $errors->has('punching') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="punching" value="0">
                                <input type="checkbox" name="punching" id="punching" value="1" {{ $designation->punching || old('punching', 0) === 1 ? 'checked' : '' }}>
                                <label for="punching" style="font-weight: 400">{{ trans('cruds.designation.fields.punching') }}</label>
                            </div>
                            @if($errors->has('punching'))
                                <span class="help-block" role="alert">{{ $errors->first('punching') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.designation.fields.punching_helper') }}</span>
            </div>
            <div class="form-group {{ $errors->has('normal_office_hours') ? 'has-error' : '' }}">
                <label for="normal_office_hours">{{ trans('cruds.designation.fields.normal_office_hours') }}</label>
                <input class="form-control" type="number" name="normal_office_hours" id="normal_office_hours" value="{{ old('normal_office_hours', $designation->normal_office_hours) }}" step="1">
                @if($errors->has('normal_office_hours'))
                    <span class="help-block" role="alert">{{ $errors->first('normal_office_hours') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.designation.fields.normal_office_hours_helper') }}</span>
        </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

