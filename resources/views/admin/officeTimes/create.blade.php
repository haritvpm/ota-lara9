@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.officeTime.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.office-times.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('groupname') ? 'has-error' : '' }}">
                            <label class="required" for="groupname">{{ trans('cruds.officeTime.fields.groupname') }}</label>
                            <input class="form-control" type="text" name="groupname" id="groupname" value="{{ old('groupname', '') }}" required>
                            @if($errors->has('groupname'))
                                <span class="help-block" role="alert">{{ $errors->first('groupname') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.groupname_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('fn_from') ? 'has-error' : '' }}">
                            <label for="fn_from">{{ trans('cruds.officeTime.fields.fn_from') }}</label>
                            <input class="form-control timepicker" type="text" name="fn_from" id="fn_from" value="{{ old('fn_from') }}">
                            @if($errors->has('fn_from'))
                                <span class="help-block" role="alert">{{ $errors->first('fn_from') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.fn_from_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('an_to') ? 'has-error' : '' }}">
                            <label for="an_to">{{ trans('cruds.officeTime.fields.an_to') }}</label>
                            <input class="form-control timepicker" type="text" name="an_to" id="an_to" value="{{ old('an_to') }}">
                            @if($errors->has('an_to'))
                                <span class="help-block" role="alert">{{ $errors->first('an_to') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.an_to_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('minutes_for_ot_workingday') ? 'has-error' : '' }}">
                            <label class="required" for="minutes_for_ot_workingday">{{ trans('cruds.officeTime.fields.minutes_for_ot_workingday') }}</label>
                            <input class="form-control" type="number" name="minutes_for_ot_workingday" id="minutes_for_ot_workingday" value="{{ old('minutes_for_ot_workingday', '150') }}" step="1" required>
                            @if($errors->has('minutes_for_ot_workingday'))
                                <span class="help-block" role="alert">{{ $errors->first('minutes_for_ot_workingday') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.minutes_for_ot_workingday_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('minutes_for_ot_holiday') ? 'has-error' : '' }}">
                            <label class="required" for="minutes_for_ot_holiday">{{ trans('cruds.officeTime.fields.minutes_for_ot_holiday') }}</label>
                            <input class="form-control" type="number" name="minutes_for_ot_holiday" id="minutes_for_ot_holiday" value="{{ old('minutes_for_ot_holiday', '180') }}" step="1" required>
                            @if($errors->has('minutes_for_ot_holiday'))
                                <span class="help-block" role="alert">{{ $errors->first('minutes_for_ot_holiday') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.minutes_for_ot_holiday_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('max_ot_workingday') ? 'has-error' : '' }}">
                            <label for="max_ot_workingday">{{ trans('cruds.officeTime.fields.max_ot_workingday') }}</label>
                            <input class="form-control" type="number" name="max_ot_workingday" id="max_ot_workingday" value="{{ old('max_ot_workingday', '') }}" step="1">
                            @if($errors->has('max_ot_workingday'))
                                <span class="help-block" role="alert">{{ $errors->first('max_ot_workingday') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.max_ot_workingday_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('max_ot_sittingday') ? 'has-error' : '' }}">
                            <label for="max_ot_sittingday">{{ trans('cruds.officeTime.fields.max_ot_sittingday') }}</label>
                            <input class="form-control" type="number" name="max_ot_sittingday" id="max_ot_sittingday" value="{{ old('max_ot_sittingday', '') }}" step="1">
                            @if($errors->has('max_ot_sittingday'))
                                <span class="help-block" role="alert">{{ $errors->first('max_ot_sittingday') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.max_ot_sittingday_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('max_ot_holiday') ? 'has-error' : '' }}">
                            <label for="max_ot_holiday">{{ trans('cruds.officeTime.fields.max_ot_holiday') }}</label>
                            <input class="form-control" type="number" name="max_ot_holiday" id="max_ot_holiday" value="{{ old('max_ot_holiday', '') }}" step="1">
                            @if($errors->has('max_ot_holiday'))
                                <span class="help-block" role="alert">{{ $errors->first('max_ot_holiday') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.max_ot_holiday_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('office_minutes') ? 'has-error' : '' }}">
                            <label class="required" for="office_minutes">{{ trans('cruds.officeTime.fields.office_minutes') }}</label>
                            <input class="form-control" type="number" name="office_minutes" id="office_minutes" value="{{ old('office_minutes', '420') }}" step="1" required>
                            @if($errors->has('office_minutes'))
                                <span class="help-block" role="alert">{{ $errors->first('office_minutes') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.officeTime.fields.office_minutes_helper') }}</span>
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