@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.designations.title')</h3>
    
    {!! Form::model($designation, ['method' => 'PUT', 'route' => ['admin.designations.update', $designation->id]]) !!}

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 form-group">
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
                <div class="col-sm-12 form-group">
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
            

	 <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.designation.fields.type') }}</label>
                            <select class="form-control" name="type" id="type">
                                <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Designation::TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('type', $designation->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <span class="help-block" role="alert">{{ $errors->first('type') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.designation.fields.type_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('has_additional_ot') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="has_additional_ot" value="0">
                                <input type="checkbox" name="has_additional_ot" id="has_additional_ot" value="1" {{ $designation->has_additional_ot || old('has_additional_ot', 0) === 1 ? 'checked' : '' }}>
                                <label for="has_additional_ot" style="font-weight: 400">{{ trans('cruds.designation.fields.has_additional_ot') }}</label>
                            </div>
                            @if($errors->has('has_additional_ot'))
                                <span class="help-block" role="alert">{{ $errors->first('has_additional_ot') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.designation.fields.has_additional_ot_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('office_time') ? 'has-error' : '' }}">
                            <label for="office_time_id">{{ trans('cruds.designation.fields.office_time') }}</label>
                            <select class="form-control select2" name="office_time_id" id="office_time_id">
                                @foreach($office_times as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('office_time_id') ? old('office_time_id') : $designation->office_time->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('office_time'))
                                <span class="help-block" role="alert">{{ $errors->first('office_time') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.designation.fields.office_time_helper') }}</span>
        		</div>
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

