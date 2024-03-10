@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.officerEmployee.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.officer-employees.update", [$officerEmployee->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="officer_id">{{ trans('cruds.officerEmployee.fields.officer') }}</label>
                <select class="form-control select2 {{ $errors->has('officer') ? 'is-invalid' : '' }}" name="officer_id" id="officer_id" required>
                    @foreach($officers as $id => $entry)
                        <option value="{{ $id }}" {{ (old('officer_id') ? old('officer_id') : $officerEmployee->officer->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('officer'))
                    <span class="text-danger">{{ $errors->first('officer') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.officerEmployee.fields.officer_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="employee_id">{{ trans('cruds.officerEmployee.fields.employee') }}</label>
                <select class="form-control select2 {{ $errors->has('employee') ? 'is-invalid' : '' }}" name="employee_id" id="employee_id" required>
                    @foreach($employees as $id => $entry)
                        <option value="{{ $id }}" {{ (old('employee_id') ? old('employee_id') : $officerEmployee->employee->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('employee'))
                    <span class="text-danger">{{ $errors->first('employee') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.officerEmployee.fields.employee_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="date_from">{{ trans('cruds.officerEmployee.fields.date_from') }}</label>
                <input class="form-control date {{ $errors->has('date_from') ? 'is-invalid' : '' }}" type="text" name="date_from" id="date_from" value="{{ old('date_from', $officerEmployee->date_from) }}">
                @if($errors->has('date_from'))
                    <span class="text-danger">{{ $errors->first('date_from') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.officerEmployee.fields.date_from_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="date_to">{{ trans('cruds.officerEmployee.fields.date_to') }}</label>
                <input class="form-control date {{ $errors->has('date_to') ? 'is-invalid' : '' }}" type="text" name="date_to" id="date_to" value="{{ old('date_to', $officerEmployee->date_to) }}">
                @if($errors->has('date_to'))
                    <span class="text-danger">{{ $errors->first('date_to') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.officerEmployee.fields.date_to_helper') }}</span>
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