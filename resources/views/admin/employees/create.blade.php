@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.employees.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.employees.store']]) !!}

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="card-body">
             <div class="row">
                <div class="col-sm-2 form-group">
                    {!! Form::label('srismt', trans('quickadmin.employees.fields.srismt').'', ['class' => 'control-label']) !!}
                    {!! Form::select('srismt', $enum_srismt, old('srismt'), ['class' => 'form-control ']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('srismt'))
                        <p class="help-block">
                            {{ $errors->first('srismt') }}
                        </p>
                    @endif
                </div>
            
                <div class="col-sm-5 form-group">
                    {!! Form::label('name', trans('quickadmin.employees.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            
                <!-- <div class="col-sm-5 form-group">
                    {!! Form::label('name_mal', trans('quickadmin.employees.fields.name-mal').'', ['class' => 'control-label']) !!}
                    {!! Form::text('name_mal', old('name_mal'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name_mal'))
                        <p class="help-block">
                            {{ $errors->first('name_mal') }}
                        </p>
                    @endif
                </div> -->

            </div>

         
            <div class="row">
                 @if(!$temppen)
                <div class="col-sm-6 form-group">
                    {!! Form::label('pen', trans('quickadmin.employees.fields.pen').'*', ['class' => 'control-label']) !!}
                    <i>(e.g.-  <span class="text-danger">799438, P12059, etc. </span>; No editing after Save)</i>
                    {!! Form::text('pen', old('pen'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('pen'))
                        <p class="help-block">
                            {{ $errors->first('pen') }}
                        </p>
                    @endif
                </div>
                 @else
                <input type="hidden" id="myText" name="pen" value="temppen">
                 @endif
                <div class="col-sm-6 form-group">
                    
                    {!! Form::label('aadhaarid', trans('quickadmin.employees.fields.aadhaarid').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('aadhaarid', old('aadhaarid'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('aadhaarid'))
                        <p class="help-block">
                            {{ $errors->first('aadhaarid') }}
                        </p>
                    @endif

                </div>
            </div>
           

           


            <div class="row">
                <div class="col-sm-7 form-group">
                    {!! Form::label('designation_id', trans('quickadmin.employees.fields.designation').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('designation_id', $designations, old('designation_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('designation_id'))
                        <p class="help-block">
                            {{ $errors->first('designation_id') }}
                        </p>
                    @endif
                </div>
            
                <div class="col-sm-5 form-group">
                    {!! Form::label('category', 'Category', ['class' => 'control-label']) !!}
                    {!! Form::select('category', $enum_category, old('category'), ['class' => 'form-control']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('category'))
                        <p class="help-block">
                            {{ $errors->first('category') }}
                        </p>
                    @endif
                </div>
            </div>
           
            @if(\Auth::user()->isAdmin())
             <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('categories_id', trans('quickadmin.employees.fields.categories').'', ['class' => 'control-label']) !!}
                    {!! Form::select('categories_id', $categories, old('categories_id'), ['class' => 'form-control select2']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('categories_id'))
                        <p class="help-block">
                            {{ $errors->first('categories_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                <div class="form-check {{ $errors->has('punching') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="punching" value="0">
                    <input class="form-check-input" type="checkbox" name="punching" id="punching" value="1" {{ old('punching', 0) == 1 || old('punching') === null ? 'checked' : '' }}>
                    <label class="form-check-label" for="punching">{{ trans('cruds.employee.fields.punching') }}</label>
                </div>
                @if($errors->has('punching'))
                    <span class="text-danger">{{ $errors->first('punching') }}</span>
                @endif
                </div>
            </div>
           
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('desig_display', trans('quickadmin.employees.fields.desig-display').'', ['class' => 'control-label']) !!}
                    {!! Form::text('desig_display', old('desig_display'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('desig_display'))
                        <p class="help-block">
                            {{ $errors->first('desig_display') }}
                        </p>
                    @endif
                </div>
            </div>
	    
	    <div>
                                <input type="hidden" name="is_shift" value="0">
                                <input type="checkbox" name="is_shift" id="is_shift" value="1" {{ old('is_shift', 0) == 1 ? 'checked' : '' }}>
                                <label for="is_shift" style="font-weight: 400">{{ trans('cruds.employee.fields.is_shift') }}</label>
                            </div>
                            @if($errors->has('is_shift'))
                                <span class="help-block" role="alert">{{ $errors->first('is_shift') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.employee.fields.is_shift_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('shift_time') ? 'has-error' : '' }}">
                            <label for="shift_time_id">{{ trans('cruds.employee.fields.shift_time') }}</label>
                            <select class="form-control select2" name="shift_time_id" id="shift_time_id">
                                @foreach($shift_times as $id => $entry)
                                    <option value="{{ $id }}" {{ old('shift_time_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('shift_time'))
                                <span class="help-block" role="alert">{{ $errors->first('shift_time') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.employee.fields.shift_time_helper') }}</span>
                        </div>

                     

            @endif
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}

    <script>
    document.getElementById("myText").value  = "TMPPEN"+(new Date()).toISOString() + Math.floor(Math.random() * 100);
    </script>

@stop

