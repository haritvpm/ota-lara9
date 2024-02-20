@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.forms.title')</h3>
    Form no {{$form->id}}<br>
    {!! Form::model($form, ['method' => 'PUT', 'route' => ['admin.forms.update', $form->id]]) !!}

    <div class="card">
        <div class="card-title">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('session', trans('quickadmin.forms.fields.session').'*', ['class' => 'control-label']) !!}
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
                <div class="col-xs-12 form-group">
                    {!! Form::label('creator', trans('quickadmin.forms.fields.creator').'*', ['class' => 'control-label']) !!}
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
                <div class="col-xs-12 form-group">
                    {!! Form::label('owner', trans('quickadmin.forms.fields.owner').'*', ['class' => 'control-label']) !!}
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
                <div class="col-xs-12 form-group">
                    {!! Form::label('form_no', trans('quickadmin.forms.fields.form-no').'*', ['class' => 'control-label']) !!}
                    {!! Form::number('form_no', old('form_no'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('form_no'))
                        <p class="help-block">
                            {{ $errors->first('form_no') }}
                        </p>
                    @endif
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('overtime_slot', trans('quickadmin.forms.fields.overtime-slot').'*', ['class' => 'control-label']) !!}
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
                <div class="col-xs-12 form-group">
                    {!! Form::label('duty_date', trans('quickadmin.forms.fields.duty-date').'', ['class' => 'control-label']) !!}
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
                <div class="col-xs-12 form-group">
                    {!! Form::label('date_from', trans('quickadmin.forms.fields.date-from').'', ['class' => 'control-label']) !!}
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
                <div class="col-xs-12 form-group">
                    {!! Form::label('date_to', trans('quickadmin.forms.fields.date-to').'', ['class' => 'control-label']) !!}
                    {!! Form::text('date_to', old('date_to'), ['class' => 'form-control date', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('date_to'))
                        <p class="help-block">
                            {{ $errors->first('date_to') }}
                        </p>
                    @endif
                </div>
            </div>

             <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('submitted_names', 'submitted-names', ['class' => 'control-label']) !!}
                    {!! Form::text('submitted_names', old('submitted_names'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('submitted_names'))
                        <p class="help-block">
                            {{ $errors->first('submitted_names') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('submitted_by', 'submitted-by', ['class' => 'control-label']) !!}
                    {!! Form::text('submitted_by', old('submitted_by'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('submitted_by'))
                        <p class="help-block">
                            {{ $errors->first('submitted_by') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('submitted_on', 'submitted-on', ['class' => 'control-label']) !!}
                    {!! Form::text('submitted_on', old('submitted_on'), ['class' => 'form-control date', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('submitted_on'))
                        <p class="help-block">
                            {{ $errors->first('submitted_on') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('remarks', 'remarks', ['class' => 'control-label']) !!}
                    {!! Form::text('remarks', old('remarks'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('remarks'))
                        <p class="help-block">
                            {{ $errors->first('remarks') }}
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
    <!-- we are editing dates directly. since we use dd-mm-yyyy and sql expects yyyy-mm-dd, we should use text field only -->
    <!-- <script>
        $('.date').datepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}"
        });
    </script> -->

@stop