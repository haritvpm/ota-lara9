@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.designations-other.title')</h3>
    
    {!! Form::model($designations_other, ['method' => 'PUT', 'route' => ['admin.designations_others.update', $designations_other->id]]) !!}

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="card-body">
              @if(Auth::user()->isAdmin())     
             <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('user_id', 'User*', ['class' => 'control-label']) !!}
                    {!! Form::select('user_id', $users, old('user_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('user_id'))
                        <p class="help-block">
                            {{ $errors->first('user_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('max_persons', 'max_persons', ['class' => 'control-label']) !!}
                    {!! Form::text('max_persons', old('max_persons'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('max_persons'))
                        <p class="help-block">
                            {{ $errors->first('max_persons') }}
                        </p>
                    @endif
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('designation', trans('quickadmin.designations-other.fields.designation').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('designation', old('designation'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('designation'))
                        <p class="help-block">
                            {{ $errors->first('designation') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('rate', trans('quickadmin.designations-other.fields.rate').'*', ['class' => 'control-label']) !!}
                    {!! Form::number('rate', old('rate'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('rate'))
                        <p class="help-block">
                            {{ $errors->first('rate') }}
                        </p>
                    @endif
                </div>
            </div>
           
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

