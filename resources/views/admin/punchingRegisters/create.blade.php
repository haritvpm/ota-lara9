@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.punchingRegister.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.punching-registers.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="date">{{ trans('cruds.punchingRegister.fields.date') }}</label>
                <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date') }}" required>
                @if($errors->has('date'))
                    <span class="text-danger">{{ $errors->first('date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingRegister.fields.date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="employee_id">{{ trans('cruds.punchingRegister.fields.employee') }}</label>
                <select class="form-control select2 {{ $errors->has('employee') ? 'is-invalid' : '' }}" name="employee_id" id="employee_id" required>
                    @foreach($employees as $id => $entry)
                        <option value="{{ $id }}" {{ old('employee_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('employee'))
                    <span class="text-danger">{{ $errors->first('employee') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingRegister.fields.employee_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="punchin_id">{{ trans('cruds.punchingRegister.fields.punchin') }}</label>
                <select class="form-control select2 {{ $errors->has('punchin') ? 'is-invalid' : '' }}" name="punchin_id" id="punchin_id">
                    @foreach($punchins as $id => $entry)
                        <option value="{{ $id }}" {{ old('punchin_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('punchin'))
                    <span class="text-danger">{{ $errors->first('punchin') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingRegister.fields.punchin_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="duration">{{ trans('cruds.punchingRegister.fields.duration') }}</label>
                <input class="form-control {{ $errors->has('duration') ? 'is-invalid' : '' }}" type="text" name="duration" id="duration" value="{{ old('duration', '') }}">
                @if($errors->has('duration'))
                    <span class="text-danger">{{ $errors->first('duration') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingRegister.fields.duration_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.punchingRegister.fields.flexi') }}</label>
                <select class="form-control {{ $errors->has('flexi') ? 'is-invalid' : '' }}" name="flexi" id="flexi">
                    <option value disabled {{ old('flexi', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\PunchingRegister::FLEXI_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('flexi', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('flexi'))
                    <span class="text-danger">{{ $errors->first('flexi') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingRegister.fields.flexi_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="grace_min">{{ trans('cruds.punchingRegister.fields.grace_min') }}</label>
                <input class="form-control {{ $errors->has('grace_min') ? 'is-invalid' : '' }}" type="text" name="grace_min" id="grace_min" value="{{ old('grace_min', '') }}">
                @if($errors->has('grace_min'))
                    <span class="text-danger">{{ $errors->first('grace_min') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingRegister.fields.grace_min_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="extra_min">{{ trans('cruds.punchingRegister.fields.extra_min') }}</label>
                <input class="form-control {{ $errors->has('extra_min') ? 'is-invalid' : '' }}" type="text" name="extra_min" id="extra_min" value="{{ old('extra_min', '') }}">
                @if($errors->has('extra_min'))
                    <span class="text-danger">{{ $errors->first('extra_min') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.punchingRegister.fields.extra_min_helper') }}</span>
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