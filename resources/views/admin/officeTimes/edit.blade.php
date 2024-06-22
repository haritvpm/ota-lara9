@extends('layouts.app')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.officeTime.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.office-times.update", [$officeTime->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('groupname') ? 'has-error' : '' }}">
                            <label class="required" for="groupname">{{ trans('cruds.officeTime.fields.groupname') }}</label>
                            <input class="form-control" type="text" name="groupname" id="groupname" value="{{ old('groupname', $officeTime->groupname) }}" required>
                            @if($errors->has('groupname'))
                                <span class="help-block" role="alert">{{ $errors->first('groupname') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.groupname_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('fn_from') ? 'has-error' : '' }}">
                            <label for="fn_from">{{ trans('cruds.officeTime.fields.fn_from') }}</label>
                            <input class="form-control timepicker" type="text" name="fn_from" id="fn_from" value="{{ old('fn_from', $officeTime->fn_from) }}">
                            @if($errors->has('fn_from'))
                                <span class="help-block" role="alert">{{ $errors->first('fn_from') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.fn_from_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('an_to') ? 'has-error' : '' }}">
                            <label for="an_to">{{ trans('cruds.officeTime.fields.an_to') }}</label>
                            <input class="form-control timepicker" type="text" name="an_to" id="an_to" value="{{ old('an_to', $officeTime->an_to) }}">
                            @if($errors->has('an_to'))
                                <span class="help-block" role="alert">{{ $errors->first('an_to') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.an_to_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('minutes_for_ot_workingday') ? 'has-error' : '' }}">
                            <label class="required" for="minutes_for_ot_workingday">{{ trans('cruds.officeTime.fields.minutes_for_ot_workingday') }}</label>
                            <input class="form-control" type="number" name="minutes_for_ot_workingday" id="minutes_for_ot_workingday" value="{{ old('minutes_for_ot_workingday', $officeTime->minutes_for_ot_workingday) }}" step="1" required>
                            @if($errors->has('minutes_for_ot_workingday'))
                                <span class="help-block" role="alert">{{ $errors->first('minutes_for_ot_workingday') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.minutes_for_ot_workingday_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('minutes_for_ot_holiday') ? 'has-error' : '' }}">
                            <label class="required" for="minutes_for_ot_holiday">{{ trans('cruds.officeTime.fields.minutes_for_ot_holiday') }}</label>
                            <input class="form-control" type="number" name="minutes_for_ot_holiday" id="minutes_for_ot_holiday" value="{{ old('minutes_for_ot_holiday', $officeTime->minutes_for_ot_holiday) }}" step="1" required>
                            @if($errors->has('minutes_for_ot_holiday'))
                                <span class="help-block" role="alert">{{ $errors->first('minutes_for_ot_holiday') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.minutes_for_ot_holiday_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('max_ot_workingday') ? 'has-error' : '' }}">
                            <label for="max_ot_workingday">{{ trans('cruds.officeTime.fields.max_ot_workingday') }}</label>
                            <input class="form-control" type="number" name="max_ot_workingday" id="max_ot_workingday" value="{{ old('max_ot_workingday', $officeTime->max_ot_workingday) }}" step="1">
                            @if($errors->has('max_ot_workingday'))
                                <span class="help-block" role="alert">{{ $errors->first('max_ot_workingday') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.max_ot_workingday_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('max_ot_sittingday') ? 'has-error' : '' }}">
                            <label for="max_ot_sittingday">{{ trans('cruds.officeTime.fields.max_ot_sittingday') }}</label>
                            <input class="form-control" type="number" name="max_ot_sittingday" id="max_ot_sittingday" value="{{ old('max_ot_sittingday', $officeTime->max_ot_sittingday) }}" step="1">
                            @if($errors->has('max_ot_sittingday'))
                                <span class="help-block" role="alert">{{ $errors->first('max_ot_sittingday') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.max_ot_sittingday_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('max_ot_holiday') ? 'has-error' : '' }}">
                            <label for="max_ot_holiday">{{ trans('cruds.officeTime.fields.max_ot_holiday') }}</label>
                            <input class="form-control" type="number" name="max_ot_holiday" id="max_ot_holiday" value="{{ old('max_ot_holiday', $officeTime->max_ot_holiday) }}" step="1">
                            @if($errors->has('max_ot_holiday'))
                                <span class="help-block" role="alert">{{ $errors->first('max_ot_holiday') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.max_ot_holiday_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('office_minutes') ? 'has-error' : '' }}">
                            <label class="required" for="office_minutes">{{ trans('cruds.officeTime.fields.office_minutes') }}</label>
                            <input class="form-control" type="number" name="office_minutes" id="office_minutes" value="{{ old('office_minutes', $officeTime->office_minutes) }}" step="1" required>
                            @if($errors->has('office_minutes'))
                                <span class="help-block" role="alert">{{ $errors->first('office_minutes') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.office_minutes_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('sittingday_duration_min_for_second_ot') ? 'has-error' : '' }}">
                            <label for="sittingday_duration_min_for_second_ot">{{ trans('cruds.officeTime.fields.sittingday_duration_min_for_second_ot') }}</label>
                            <input class="form-control" type="number" name="sittingday_duration_min_for_second_ot" id="sittingday_duration_min_for_second_ot" value="{{ old('sittingday_duration_min_for_second_ot', $officeTime->sittingday_duration_min_for_second_ot) }}" step="1">
                            @if($errors->has('sittingday_duration_min_for_second_ot'))
                                <span class="help-block" role="alert">{{ $errors->first('sittingday_duration_min_for_second_ot') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.sittingday_duration_min_for_second_ot_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('workingday_duration_min_for_first_ot') ? 'has-error' : '' }}">
                            <label for="workingday_duration_min_for_first_ot">{{ trans('cruds.officeTime.fields.workingday_duration_min_for_first_ot') }}</label>
                            <input class="form-control" type="number" name="workingday_duration_min_for_first_ot" id="workingday_duration_min_for_first_ot" value="{{ old('workingday_duration_min_for_first_ot', $officeTime->workingday_duration_min_for_first_ot) }}" step="1">
                            @if($errors->has('workingday_duration_min_for_first_ot'))
                                <span class="help-block" role="alert">{{ $errors->first('workingday_duration_min_for_first_ot') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.workingday_duration_min_for_first_ot_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('sitting_ot_time_str') ? 'has-error' : '' }}">
                            <label for="sitting_ot_time_str">{{ trans('cruds.officeTime.fields.sitting_ot_time_str') }}</label>
                            <input class="form-control" type="text" name="sitting_ot_time_str" id="sitting_ot_time_str" value="{{ old('sitting_ot_time_str', $officeTime->sitting_ot_time_str) }}">
                            @if($errors->has('sitting_ot_time_str'))
                                <span class="help-block" role="alert">{{ $errors->first('sitting_ot_time_str') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.sitting_ot_time_str_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('sitting_ot_initial_leeway_min') ? 'has-error' : '' }}">
                            <label for="sitting_ot_initial_leeway_min">{{ trans('cruds.officeTime.fields.sitting_ot_initial_leeway_min') }}</label>
                            <input class="form-control" type="number" name="sitting_ot_initial_leeway_min" id="sitting_ot_initial_leeway_min" value="{{ old('sitting_ot_initial_leeway_min', $officeTime->sitting_ot_initial_leeway_min) }}" step="1">
                            @if($errors->has('sitting_ot_initial_leeway_min'))
                                <span class="help-block" role="alert">{{ $errors->first('sitting_ot_initial_leeway_min') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.sitting_ot_initial_leeway_min_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection