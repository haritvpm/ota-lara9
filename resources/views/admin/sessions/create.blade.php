@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.sessions.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['admin.sessions.store']]) !!}

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('name', trans('quickadmin.sessions.fields.name').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('kla', trans('quickadmin.sessions.fields.kla').'*', ['class' => 'control-label']) !!}
                    {!! Form::number('kla', old('kla'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('kla'))
                        <p class="help-block">
                            {{ $errors->first('kla') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('session', trans('quickadmin.sessions.fields.session').'*', ['class' => 'control-label']) !!}
                    {!! Form::number('session', old('session'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
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
                    {!! Form::label('dataentry_allowed', trans('quickadmin.sessions.fields.dataentry-allowed').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('dataentry_allowed', $enum_dataentry_allowed, old('dataentry_allowed'), ['class' => 'form-control ', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('dataentry_allowed'))
                        <p class="help-block">
                            {{ $errors->first('dataentry_allowed') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('show_in_datatable', trans('quickadmin.sessions.fields.show-in-datatable').'', ['class' => 'control-label']) !!}
                    {!! Form::select('show_in_datatable', $enum_show_in_datatable, old('show_in_datatable'), ['class' => 'form-control ']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('show_in_datatable'))
                        <p class="help-block">
                            {{ $errors->first('show_in_datatable') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('sittings_entry', trans('quickadmin.sessions.fields.sittings-entry').'', ['class' => 'control-label']) !!}
                    {!! Form::select('sittings_entry', $enum_sittings_entry, old('sittings_entry'), ['class' => 'form-control select2']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('sittings_entry'))
                        <p class="help-block">
                            {{ $errors->first('sittings_entry') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    <a href="{{route('admin.sessions.index')}}" class="btn btn-default">Cancel</a>

    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

