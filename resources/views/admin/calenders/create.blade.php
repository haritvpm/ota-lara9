@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.calenders.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.calenders.store']]) !!}

    <div class="panel panel-default"  id="app">
        <div class="panel-heading">
            @lang('quickadmin.qa_create')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('session_id', trans('quickadmin.calenders.fields.session').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('session_id', $sessions, old('session_id'), ['class' => 'form-control ', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('session_id'))
                        <p class="help-block">
                            {{ $errors->first('session_id') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('date', trans('quickadmin.calenders.fields.date').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('date', $would_be_date, ['class' => 'form-control date', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('date'))
                        <p class="help-block">
                            {{ $errors->first('date') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('day_type', trans('quickadmin.calenders.fields.day-type').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('day_type', $enum_day_type, old('day_type'), ['class' => 'form-control', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('day_type'))
                        <p class="help-block">
                            {{ $errors->first('day_type') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('description', 'Description', ['class' => 'control-label']) !!}
                    {!! Form::text('description', old('description'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('description'))
                        <p class="help-block">
                            {{ $errors->first('description') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                <label for="daylength_multiplier">{{ trans('cruds.calender.fields.daylength_multiplier') }}</label>
                            <input class="form-control" type="number" name="daylength_multiplier" id="daylength_multiplier" value="{{ old('daylength_multiplier', '1.0') }}" step="0.01" max="1">
                            @if($errors->has('daylength_multiplier'))
                                <span class="help-block" role="alert">{{ $errors->first('daylength_multiplier') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.calender.fields.daylength_multiplier_helper') }}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                <label>Punching</label>
                            <select class="form-control" name="punching" id="punching">
                                <option value disabled {{ old('punching', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Calender::PUNCHING_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('punching', 'AEBAS_FETCH_PENDING') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('punching'))
                                <span class="help-block" role="alert">{{ $errors->first('punching') }}</span>
                            @endif
                            
             </div>
            </div>
    
            
        </div>
    </div>

    <a href="{{route('admin.calenders.index')}}" class="btn btn-default">Cancel</a>
    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger', 'name' => 'submitbutton']) !!}
    {!! Form::submit('Save & New' , ['class' => 'btn btn-primary', 'name' => 'submitbutton']) !!}

    {!! Form::close() !!}
@stop
<!-- 
<script type="text/javascript" src="{{ URL::asset('js/flatpickr.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/vue-flatpickr.min.js') }}"></script>
 -->
@section('javascript')
    @parent
    <script>
        $('.date').datepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}"
        });
    </script>


<!-- <script>
  //Initialize as global component
  Vue.component('flat-pickr', VueFlatpickr.default);
  
  new Vue({
    el: '#app',
    data: {
      date: null
    },    
  });
</script>
 -->

@stop