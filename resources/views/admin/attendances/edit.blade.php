@extends('layouts.app')
@section('content')

<div class="card p-2">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('quickadmin.attendance.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.attendances.update", [$attendance->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            
            <div class="form-group">
                <label class="required" for="pen">PEN as per Secretary-Office</label>
                <input class="form-control {{ $errors->has('pen') ? 'is-invalid' : '' }}" type="text" name="pen" id="pen" value="{{ old('pen', $attendance->pen) }}" required readonly>
                @if($errors->has('pen'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pen') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('quickadmin.attendance.fields.pen_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="name">Name as per Secretary-Office</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $attendance->name) }}" required readonly>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('quickadmin.attendance.fields.name_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="session_id">{{ trans('quickadmin.attendance.fields.session') }}</label>
                
                <select class="form-control select2 {{ $errors->has('session') ? 'is-invalid' : '' }}" name="session_id" id="session_id" disabled>
                    @foreach($sessions as $id => $entry)
                        <option value="{{ $id }}" {{ (old('session_id') ? old('session_id') : $attendance->session->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="session_id" value="{{$attendance->session_id}}"/>

                @if($errors->has('session'))
                    <div class="invalid-feedback">
                        {{ $errors->first('session') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('quickadmin.attendance.fields.session_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="employee_id">Employee</label>
                <select class="form-control select2 {{ $errors->has('employee') ? 'is-invalid' : '' }}" name="employee_id" id="employee_id">
                    @foreach($employees as $id => $entry)
                        <option value="{{ $id }}" {{ (old('employee_id') ? old('employee_id') : $attendance->employee->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('employee'))
                    <div class="invalid-feedback">
                        {{ $errors->first('employee') }}
                    </div>
                @endif
                
            </div>

            <div class="form-group">
                <label for="present_dates">{{ trans('quickadmin.attendance.fields.present_dates') }}</label>
                <input class="form-control {{ $errors->has('present_dates') ? 'is-invalid' : '' }}" type="text" name="present_dates" id="present_dates" value="{{ old('present_dates', $attendance->present_dates) }}">
                @if($errors->has('present_dates'))
                    <div class="invalid-feedback">
                        {{ $errors->first('present_dates') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('quickadmin.attendance.fields.present_dates_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="total">{{ trans('quickadmin.attendance.fields.total') }}</label>
                <input class="form-control {{ $errors->has('total') ? 'is-invalid' : '' }}" type="number" name="total" id="total" value="{{ old('total', $attendance->total) }}" step="1" required>
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