@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.employees-other.title')</h3>
    
    {!! Form::model($employees_other, ['method' => 'PUT', 'route' => ['admin.employees_others.update', $employees_other->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-2 form-group">
                    {!! Form::label('srismt', trans('quickadmin.employees-other.fields.srismt').'', ['class' => 'control-label']) !!}
                    {!! Form::select('srismt', $enum_srismt, old('srismt'), ['class' => 'form-control select2']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('srismt'))
                        <p class="help-block">
                            {{ $errors->first('srismt') }}
                        </p>
                    @endif
                </div>
           
                <div class="col-xs-6 form-group">
                    {!! Form::label('name', trans('quickadmin.employees-other.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
           
                <div class="col-xs-4 form-group">
                    {!! Form::label('pen', trans('quickadmin.employees-other.fields.pen').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('pen', old('pen'), ['class' => 'form-control', 'readonly' => 'readonly', 'placeholder' => '', 'required' => '', 'readonly' => '' ]) !!}
                    <p class="help-block"></p>
                    @if($errors->has('pen'))
                        <p class="help-block">
                            {{ $errors->first('pen') }}
                        </p>
                    @endif
                </div>
            </div>
            <!-- not using select2 for designation as it breaks tabbing through forms -->
            <div class="row">
                <div class="col-xs-6 form-group">
                    {!! Form::label('designation_id', trans('quickadmin.employees-other.fields.designation').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('designation_id', $designations, old('designation_id'), ['class' => 'form-control', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('designation_id'))
                        <p class="help-block">
                            {{ $errors->first('designation_id') }}
                        </p>
                    @endif
                </div>
           
                <div class="col-xs-4 form-group">
                    {!! Form::label('mobile', trans('quickadmin.employees-other.fields.mobile').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('mobile', old('mobile'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('mobile'))
                        <p class="help-block">
                            {{ $errors->first('mobile') }}
                        </p>
                    @endif
                </div>

                <div class="col-xs-2 form-group">
                    {!! Form::label('department_idno', trans('quickadmin.employees-other.fields.department-idno').'', ['class' => 'control-label']) !!}
                    {!! Form::text('department_idno', old('department_idno'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('department_idno'))
                        <p class="help-block">
                            {{ $errors->first('department_idno') }}
                        </p>
                    @endif
                </div>
            </div>
           
            <div class="row">
                <div class="col-xs-4 form-group">
                    {!! Form::label('account_type', trans('quickadmin.employees-other.fields.account-type').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('account_type', $enum_account_type, old('account_type'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('account_type'))
                        <p class="help-block">
                            {{ $errors->first('account_type') }}
                        </p>
                    @endif
                </div>
           
                <div class="col-xs-3 form-group">
                    {!! Form::label('ifsc', trans('quickadmin.employees-other.fields.ifsc').'', ['class' => 'control-label']) !!}
                    {!! Form::text('ifsc', old('ifsc'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('ifsc'))
                        <p class="help-block">
                            {{ $errors->first('ifsc') }}
                        </p>
                    @endif
                </div>
           
                <div class="col-xs-5 form-group">
                    {!! Form::label('account_no', trans('quickadmin.employees-other.fields.account-no').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('account_no', old('account_no'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('account_no'))
                        <p class="help-block">
                            {{ $errors->first('account_no') }}
                        </p>
                    @endif
                </div>
            </div>

            
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

