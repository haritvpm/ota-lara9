@extends('layouts.app')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.category.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.categories.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('category') ? 'has-error' : '' }}">
                            <label class="required" for="category">{{ trans('cruds.category.fields.category') }}</label>
                            <input class="form-control" type="text" name="category" id="category" value="{{ old('category', '') }}" required>
                            @if($errors->has('category'))
                                <span class="help-block" role="alert">{{ $errors->first('category') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.category.fields.category_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('normal_office_hours') ? 'has-error' : '' }}">
                            <label for="normal_office_hours">{{ trans('cruds.category.fields.normal_office_hours') }}</label>
                            <input class="form-control" type="number" name="normal_office_hours" id="normal_office_hours" value="{{ old('normal_office_hours', '7') }}" step="1">
                            @if($errors->has('normal_office_hours'))
                                <span class="help-block" role="alert">{{ $errors->first('normal_office_hours') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.category.fields.normal_office_hours_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('punching') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="punching" value="0">
                                <input type="checkbox" name="punching" id="punching" value="1" {{ old('punching', 0) == 1 || old('punching') === null ? 'checked' : '' }}>
                                <label for="punching" style="font-weight: 400">{{ trans('cruds.category.fields.punching') }}</label>
                            </div>
                            @if($errors->has('punching'))
                                <span class="help-block" role="alert">{{ $errors->first('punching') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.category.fields.punching_helper') }}</span>
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