@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.preset.title')</h3>
<!-- <p>
    <strong>Reserved names</strong> : 
    <br> default_worknature,<br> default_holiday_firstot_starttime, default_holiday_firstot_endtime,<br>
    default_holiday_secondot_starttime, default_holiday_secondot_endtime<br>
    default_workingday_firstot_starttime, default_workingday_firstot_endtime<br>
    default_workingday_secondot_starttime, default_workingday_secondot_endtime<br>
    default_sittingday_secondot_starttime, default_sittingday_secondot_endtime.
</p> -->

    {!! Form::open(['method' => 'POST', 'route' => ['admin.presets.store']]) !!}

    <div class="card">
        <div class="card-title">
            @lang('quickadmin.qa_create')
        </div>
        
        <div class="card-body">
            @if(auth()->user()->isAdmin())
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('user_id', trans('quickadmin.preset.fields.user').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('user_id', $users, old('user_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('user_id'))
                        <p class="help-block">
                            {{ $errors->first('user_id') }}
                        </p>
                    @endif
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', trans('quickadmin.preset.fields.name').'*', ['class' => 'control-label']) !!}
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
                <div class="col-xs-12 form-group">
                    {!! Form::label('pens', trans('quickadmin.preset.fields.pens').'*', ['class' => 'control-label']) !!}
                    {!! Form::textarea('pens', old('pens'), ['class' => 'form-control ', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('pens'))
                        <p class="help-block">
                            {{ $errors->first('pens') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

