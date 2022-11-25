@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('quickadmin.attendance.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.attendances.update", [$attendance->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="dates_present">{{ trans('quickadmin.attendance.fields.dates_present') }}</label>
                <input class="form-control {{ $errors->has('dates_present') ? 'is-invalid' : '' }}" type="text" name="dates_present" id="dates_present" value="{{ old('dates_present', $attendance->dates_present) }}">
                @if($errors->has('dates_present'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dates_present') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('quickadmin.attendance.fields.dates_present_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pen">{{ trans('quickadmin.attendance.fields.pen') }}</label>
                <input class="form-control {{ $errors->has('pen') ? 'is-invalid' : '' }}" type="text" name="pen" id="pen" value="{{ old('pen', $attendance->pen) }}" required>
                @if($errors->has('pen'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pen') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('quickadmin.attendance.fields.pen_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="session_id">{{ trans('quickadmin.attendance.fields.session') }}</label>
                <select class="form-control select2 {{ $errors->has('session') ? 'is-invalid' : '' }}" name="session_id" id="session_id">
                    @foreach($sessions as $id => $entry)
                        <option value="{{ $id }}" {{ (old('session_id') ? old('session_id') : $attendance->session->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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