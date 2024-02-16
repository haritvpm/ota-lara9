@extends('layouts.app')

    <style>
        [v-cloak] { display:none; }
    </style>

@section('content')
<div class="content">
Editing will not affect already submitted OT forms, as they have their own fields. This will help autofetch for any new forms
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.punching.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.punchings.update", [$punching->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        
                        
                        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                            <label class="required" for="date">{{ trans('cruds.punching.fields.date') }}</label>
                            <input readonly class="form-control date" type="text" name="date" id="date" value="{{ old('date', $punching->date) }}" required>
                           
                        </div>
                        
                        <div class="form-group {{ $errors->has('aadhaarid') ? 'has-error' : '' }}">
                            <label class="required" for="aadhaarid">AttendanceId</label>
                            <input  readonly class="form-control" type="text" name="aadhaarid" id="aadhaarid" value="{{ old('aadhaarid', $punching->aadhaarid) }}" required>
                        </div>
                        <div class="form-group {{ $errors->has('pen') ? 'has-error' : '' }}">
                            <label class="required" for="pen">{{ trans('cruds.punching.fields.pen') }}</label>
                            <input  class="form-control" type="text" name="pen" id="pen" value="{{ old('pen', $punching->pen) }}" required>
                           
                        </div>
                        <!-- <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">{{ trans('cruds.punching.fields.name') }}</label>
                            <input readonly class="form-control" type="text" name="name" id="name" value="{{ old('name', $punching->name) }}">
                          
                        </div> -->

                        <div class="form-group {{ $errors->has('punch_in') ? 'has-error' : '' }}">
                            <label class="required" for="punch_in">{{ trans('cruds.punching.fields.punch_in') }} {{$punching->punchin_from_aebas ? '(From AEBAS)':'(Manual Entry)'}}</label>
                            <!-- do not allow editing if we got this time from aebas. and also if this time is not null -->
                      
                            <input  {{   $punching->punchin_from_aebas && $punching->punch_in  ? 'readonly' : '' }} class="form-control" type="text" name="punch_in" id="punch_in" value="{{ old('punch_in', $punching->punch_in) }}" required>
                           

                            @if($errors->has('punch_in'))
                                <span class="help-block" role="alert">{{ $errors->first('punch_in') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.punching.fields.punch_in_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('punch_out') ? 'has-error' : '' }}">
                            <label class="required" for="punch_out">{{ trans('cruds.punching.fields.punch_out') }} {{$punching->punchout_from_aebas ? '(From AEBAS)':'(Manual Entry)'}}</label>
                           
                            <input {{   $punching->punchout_from_aebas && $punching->punch_out  ? 'readonly' : '' }}  class="form-control" type="text" name="punch_out" id="punch_out" value="{{ old('punch_out', $punching->punch_out) }}" required>
                          
                            @if($errors->has('punch_out'))
                                <span class="help-block" role="alert">{{ $errors->first('punch_out') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.punching.fields.punch_out_helper') }}</span>
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
