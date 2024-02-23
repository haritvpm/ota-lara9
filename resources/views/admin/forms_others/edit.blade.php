@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.forms-others.title')</h3>
    
    {!! Form::model($forms_other, ['method' => 'PUT', 'route' => ['admin.forms_others.update', $forms_other->id]]) !!}

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('session', trans('quickadmin.forms-others.fields.session').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('session', old('session'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('session'))
                        <p class="help-block">
                            {{ $errors->first('session') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('creator', trans('quickadmin.forms-others.fields.creator').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('creator', old('creator'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('creator'))
                        <p class="help-block">
                            {{ $errors->first('creator') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('owner', trans('quickadmin.forms-others.fields.owner').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('owner', old('owner'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('owner'))
                        <p class="help-block">
                            {{ $errors->first('owner') }}
                        </p>
                    @endif
                </div>
            </div>
            
             <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('form_no', trans('quickadmin.forms-others.fields.form-no').'*', ['class' => 'control-label']) !!}
                    {!! Form::number('form_no', old('form_no'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('form_no'))
                        <p class="help-block">
                            {{ $errors->first('form_no') }}
                        </p>
                    @endif
                </div>
            </div>
           
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('overtime_slot', trans('quickadmin.forms-others.fields.overtime-slot').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('overtime_slot', $enum_overtime_slot, old('overtime_slot'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('overtime_slot'))
                        <p class="help-block">
                            {{ $errors->first('overtime_slot') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('duty_date', trans('quickadmin.forms-others.fields.duty-date').'', ['class' => 'control-label']) !!}
                    {!! Form::text('duty_date', old('duty_date'), ['class' => 'form-control date', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('duty_date'))
                        <p class="help-block">
                            {{ $errors->first('duty_date') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('date_from', trans('quickadmin.forms-others.fields.date-from').'', ['class' => 'control-label']) !!}
                    {!! Form::text('date_from', old('date_from'), ['class' => 'form-control date', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('date_from'))
                        <p class="help-block">
                            {{ $errors->first('date_from') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('date_to', trans('quickadmin.forms-others.fields.date-to').'', ['class' => 'control-label']) !!}
                    {!! Form::text('date_to', old('date_to'), ['class' => 'form-control date', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('date_to'))
                        <p class="help-block">
                            {{ $errors->first('date_to') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

@section('javascript')
    @parent
    <script>
        $('.date').datepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}"
        });
    </script>

@stop