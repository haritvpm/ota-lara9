@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.sectionEmployee.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.section-employees.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="section_or_offfice_id">{{ trans('cruds.sectionEmployee.fields.section_or_offfice') }}</label>
                <select class="form-control select2 {{ $errors->has('section_or_offfice') ? 'is-invalid' : '' }}" name="section_or_offfice_id" id="section_or_offfice_id" required>
                    @foreach($section_or_offfices as $id => $entry)
                        <option value="{{ $id }}" {{ old('section_or_offfice_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('section_or_offfice'))
                    <span class="text-danger">{{ $errors->first('section_or_offfice') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sectionEmployee.fields.section_or_offfice_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="employee_id">{{ trans('cruds.sectionEmployee.fields.employee') }}</label>
                <select class="form-control select2 {{ $errors->has('employee') ? 'is-invalid' : '' }}" name="employee_id" id="employee_id" required>
                    @foreach($employees as $id => $entry)
                        <option value="{{ $id }}" {{ old('employee_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('employee'))
                    <span class="text-danger">{{ $errors->first('employee') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sectionEmployee.fields.employee_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="date_from">{{ trans('cruds.sectionEmployee.fields.date_from') }}</label>
                <input class="form-control date {{ $errors->has('date_from') ? 'is-invalid' : '' }}" type="text" name="date_from" id="date_from" value="{{ old('date_from') }}">
                @if($errors->has('date_from'))
                    <span class="text-danger">{{ $errors->first('date_from') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sectionEmployee.fields.date_from_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="date_to">{{ trans('cruds.sectionEmployee.fields.date_to') }}</label>
                <input class="form-control date {{ $errors->has('date_to') ? 'is-invalid' : '' }}" type="text" name="date_to" id="date_to" value="{{ old('date_to') }}">
                @if($errors->has('date_to'))
                    <span class="text-danger">{{ $errors->first('date_to') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sectionEmployee.fields.date_to_helper') }}</span>
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