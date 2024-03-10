@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.officerMapping.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.officer-mappings.update", [$officerMapping->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="section_or_officer_user_id">{{ trans('cruds.officerMapping.fields.section_or_officer_user') }}</label>
                <select class="form-control select2 {{ $errors->has('section_or_officer_user') ? 'is-invalid' : '' }}" name="section_or_officer_user_id" id="section_or_officer_user_id">
                    @foreach($section_or_officer_users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('section_or_officer_user_id') ? old('section_or_officer_user_id') : $officerMapping->section_or_officer_user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('section_or_officer_user'))
                    <span class="text-danger">{{ $errors->first('section_or_officer_user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.officerMapping.fields.section_or_officer_user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="controlling_officer_user_id">{{ trans('cruds.officerMapping.fields.controlling_officer_user') }}</label>
                <select class="form-control select2 {{ $errors->has('controlling_officer_user') ? 'is-invalid' : '' }}" name="controlling_officer_user_id" id="controlling_officer_user_id" required>
                    @foreach($controlling_officer_users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('controlling_officer_user_id') ? old('controlling_officer_user_id') : $officerMapping->controlling_officer_user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('controlling_officer_user'))
                    <span class="text-danger">{{ $errors->first('controlling_officer_user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.officerMapping.fields.controlling_officer_user_helper') }}</span>
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