

@extends('layouts.auth')

<style>

body {

background-color: #000000;
/*
background: url("images/bg/pexels-adrien-olichon-2387793.jpg") no-repeat center center fixed; 
background: url("images/bg/pexels-eva-bronzini-6087685.jpg") no-repeat center center fixed; */
background: url("images/bg/pexels-jakub-novacek-924824.jpg") no-repeat center center fixed; 

  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;


}




</style>

@section('content')
<div class="bg"></div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">{{ ucfirst(config('app.name')) }} - @lang('quickadmin.qa_login')

                </div>
                
                <div class="panel-body">
                    
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were problems with input:
                            <br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="form-horizontal"
                          role="form"
                          method="POST"
                          action="{{ url('login') }}">
                        <input type="hidden"
                               name="_token"
                               value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-3 control-label">User ID</label>

                            <div class="col-md-8">
                                <input type="text"
                                       class="form-control"
                                       name="username"
                                       value="{{ old('username') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">@lang('quickadmin.qa_password')</label>

                            <div class="col-md-8">
                                <input type="password"
                                       class="form-control"
                                       name="password" autocomplete="on">
                            </div>
                        </div>

                       <!--  <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ route('auth.password.reset') }}">@lang('quickadmin.qa_forgot_password')</a>
                            </div>
                        </div> -->


                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-3">
                                <label>
                                    <input type="checkbox"
                                           name="remember"> @lang('quickadmin.qa_remember_me')
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-3">
                                <button type="submit"
                                        class="btn btn-danger  btn-lg"
                                        style="margin-right: 15px;">
                                    @lang('quickadmin.qa_login')
                                </button>

                                <!-- <p class="text-muted navbar-text navbar-right">
                                    
                                <i class="fa fa-phone-square"></i> 251-2422
    
                                </p> -->

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@php
date_default_timezone_set('Asia/Kolkata');
@endphp

<nav style="max-height:.8em;background-color: transparent;border-width: 0px;" class="navbar navbar-default navbar-fixed-bottom">
  <div class="container-fluid">
    <p class="navbar-text pull-right">
        Ph: 251-2422 &nbsp;&nbsp;
        {{ date('l, M j  (d-m-y h:i a)')  }}
    </p>
    
     <p class="navbar-text">
      <small><span class="text-muted">
                Last Updated: 24-11-22.&nbsp;
                 @if(\Config::get('custom.vps_name'))
                 @ {{\Config::get('custom.vps_name')}} &nbsp;
                 @endif
       </span>Recommended Browser: Firefox 25+. 
       @if(config('app.debug'))
        (debugmode)
       @endif
       
       </small>
    </p>

   
    
  </div>
</nav>


@endsection

