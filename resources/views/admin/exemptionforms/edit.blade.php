@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.exemptionforms.title')</h3>
    
    {!! Form::model($exemptionform, ['method' => 'PUT', 'route' => ['admin.exemptionforms.update', $exemptionform->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('session', trans('quickadmin.exemptionforms.fields.session').'*', ['class' => 'control-label']) !!}
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
                    {!! Form::label('creator', trans('quickadmin.exemptionforms.fields.creator').'*', ['class' => 'control-label']) !!}
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
                    {!! Form::label('owner', trans('quickadmin.exemptionforms.fields.owner').'*', ['class' => 'control-label']) !!}
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
                    {!! Form::label('form_no', trans('quickadmin.exemptionforms.fields.form-no').'*', ['class' => 'control-label']) !!}
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
                <div class="col-xs-12 form-group">
                    {!! Form::label('submitted_names', trans('quickadmin.exemptionforms.fields.submitted-names').'', ['class' => 'control-label']) !!}
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
                    {!! Form::label('submitted_by', trans('quickadmin.exemptionforms.fields.submitted-by').'', ['class' => 'control-label']) !!}
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
                    {!! Form::label('submitted_on', trans('quickadmin.exemptionforms.fields.submitted-on').'', ['class' => 'control-label']) !!}
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
                    {!! Form::label('remarks', trans('quickadmin.exemptionforms.fields.remarks').'', ['class' => 'control-label']) !!}
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
    <script>
        $('.date').datepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}"
        });
    </script>

@stop