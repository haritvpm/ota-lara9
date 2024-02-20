@extends('layouts.app')
@section('content')

<div class="card p-2">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('quickadmin.attendance.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.attendances.store") }}" enctype="multipart/form-data">
            @csrf
           
            <div class="form-group">
                <label class="required" for="employee">Employee</label>
                <select class="form-control select2 {{ $errors->has('employee') ? 'is-invalid' : '' }}" name="employee_id" id="employee_id">
                    @foreach($employees as $id => $entry)
                        <option value="{{ $id }}" {{ old('employee_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('employee'))
                    <div class="invalid-feedback">
                        {{ $errors->first('employee') }}
                    </div>
                @endif
                
            </div>
           
            <div class="form-group">
                <label for="session_id">{{ trans('quickadmin.attendance.fields.session') }}</label>
                <select class="form-control select2 {{ $errors->has('session') ? 'is-invalid' : '' }}" name="session_id" id="session_id">
                    @foreach($sessions as $id => $entry)
                        <option value="{{ $id }}" {{ old('session_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('session'))
                    <div class="invalid-feedback">
                        {{ $errors->first('session') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('quickadmin.attendance.fields.session_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="present_dates">{{ trans('quickadmin.attendance.fields.present_dates') }}</label>
                <input class="form-control {{ $errors->has('present_dates') ? 'is-invalid' : '' }}" type="text" name="present_dates" id="present_dates" value="{{ old('present_dates', '') }}">
                @if($errors->has('present_dates'))
                    <div class="invalid-feedback">
                        {{ $errors->first('present_dates') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('quickadmin.attendance.fields.present_dates_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="total">{{ trans('quickadmin.attendance.fields.total') }}</label>
                <input class="form-control {{ $errors->has('total') ? 'is-invalid' : '' }}" type="number" name="total" id="total" value="{{ old('total', '0') }}" step="1" required>
                @if($errors->has('total'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('quickadmin.attendance.fields.total_helper') }}</span>
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