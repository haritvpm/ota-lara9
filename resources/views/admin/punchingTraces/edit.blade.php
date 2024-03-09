@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.punchingTrace.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.punching-traces.update", [$punchingTrace->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="aadhaarid">{{ trans('cruds.punchingTrace.fields.aadhaarid') }}</label>
                <input class="form-control {{ $errors->has('aadhaarid') ? 'is-invalid' : '' }}" type="text" name="aadhaarid" id="aadhaarid" value="{{ old('aadhaarid', $punchingTrace->aadhaarid) }}" required>
                @if($errors->has('aadhaarid'))
                    <span class="text-danger">{{ $errors->first('aadhaarid') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingTrace.fields.aadhaarid_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="org_emp_code">{{ trans('cruds.punchingTrace.fields.org_emp_code') }}</label>
                <input class="form-control {{ $errors->has('org_emp_code') ? 'is-invalid' : '' }}" type="text" name="org_emp_code" id="org_emp_code" value="{{ old('org_emp_code', $punchingTrace->org_emp_code) }}">
                @if($errors->has('org_emp_code'))
                    <span class="text-danger">{{ $errors->first('org_emp_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingTrace.fields.org_emp_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="device">{{ trans('cruds.punchingTrace.fields.device') }}</label>
                <input class="form-control {{ $errors->has('device') ? 'is-invalid' : '' }}" type="text" name="device" id="device" value="{{ old('device', $punchingTrace->device) }}">
                @if($errors->has('device'))
                    <span class="text-danger">{{ $errors->first('device') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingTrace.fields.device_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attendance_type">{{ trans('cruds.punchingTrace.fields.attendance_type') }}</label>
                <input class="form-control {{ $errors->has('attendance_type') ? 'is-invalid' : '' }}" type="text" name="attendance_type" id="attendance_type" value="{{ old('attendance_type', $punchingTrace->attendance_type) }}">
                @if($errors->has('attendance_type'))
                    <span class="text-danger">{{ $errors->first('attendance_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingTrace.fields.attendance_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="auth_status">{{ trans('cruds.punchingTrace.fields.auth_status') }}</label>
                <input class="form-control {{ $errors->has('auth_status') ? 'is-invalid' : '' }}" type="text" name="auth_status" id="auth_status" value="{{ old('auth_status', $punchingTrace->auth_status) }}">
                @if($errors->has('auth_status'))
                    <span class="text-danger">{{ $errors->first('auth_status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingTrace.fields.auth_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="err_code">{{ trans('cruds.punchingTrace.fields.err_code') }}</label>
                <input class="form-control {{ $errors->has('err_code') ? 'is-invalid' : '' }}" type="text" name="err_code" id="err_code" value="{{ old('err_code', $punchingTrace->err_code) }}">
                @if($errors->has('err_code'))
                    <span class="text-danger">{{ $errors->first('err_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingTrace.fields.err_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="att_date">{{ trans('cruds.punchingTrace.fields.att_date') }}</label>
                <input class="form-control date {{ $errors->has('att_date') ? 'is-invalid' : '' }}" type="text" name="att_date" id="att_date" value="{{ old('att_date', $punchingTrace->att_date) }}" required>
                @if($errors->has('att_date'))
                    <span class="text-danger">{{ $errors->first('att_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingTrace.fields.att_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="att_time">{{ trans('cruds.punchingTrace.fields.att_time') }}</label>
                <input class="form-control timepicker {{ $errors->has('att_time') ? 'is-invalid' : '' }}" type="text" name="att_time" id="att_time" value="{{ old('att_time', $punchingTrace->att_time) }}" required>
                @if($errors->has('att_time'))
                    <span class="text-danger">{{ $errors->first('att_time') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingTrace.fields.att_time_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection