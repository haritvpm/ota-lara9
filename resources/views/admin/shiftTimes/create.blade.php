@extends('layouts.app')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.shiftTime.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.shift-times.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('groupname') ? 'has-error' : '' }}">
                            <label class="required" for="groupname">{{ trans('cruds.shiftTime.fields.groupname') }}</label>
                            <input class="form-control" type="text" name="groupname" id="groupname" value="{{ old('groupname', '') }}" required>
                            @if($errors->has('groupname'))
                                <span class="help-block" role="alert">{{ $errors->first('groupname') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.shiftTime.fields.groupname_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('shift_minutes') ? 'has-error' : '' }}">
                            <label class="required" for="shift_minutes">{{ trans('cruds.shiftTime.fields.shift_minutes') }}</label>
                            <input class="form-control" type="number" name="shift_minutes" id="shift_minutes" value="{{ old('shift_minutes', '360') }}" step="1" required>
                            @if($errors->has('shift_minutes'))
                                <span class="help-block" role="alert">{{ $errors->first('shift_minutes') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.shiftTime.fields.shift_minutes_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('minutes_for_ot') ? 'has-error' : '' }}">
                            <label for="minutes_for_ot">{{ trans('cruds.shiftTime.fields.minutes_for_ot') }}</label>
                            <input class="form-control" type="number" name="minutes_for_ot" id="minutes_for_ot" value="{{ old('minutes_for_ot', '150') }}" step="1">
                            @if($errors->has('minutes_for_ot'))
                                <span class="help-block" role="alert">{{ $errors->first('minutes_for_ot') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.shiftTime.fields.minutes_for_ot_helper') }}</span>
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