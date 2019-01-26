

@extends('layouts.auth')

<style>
body {

<?php
$patterns = array('gplay.png','hypnotize.png','paisley.png','skulls.png','swirl.png','topography.png','geometry2.png');
$patternimage = $patterns[rand(0, 6)];
?>

background-color: #ffffff;
background-image: url("images/patterns/{{$patternimage}}");

/*padding-bottom: 70px;*/

}
</style>

@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
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
                            <label class="col-md-4 control-label">User ID</label>

                            <div class="col-md-6">
                                <input type="text"
                                       class="form-control"
                                       name="username"
                                       value="{{ old('username') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">@lang('quickadmin.qa_password')</label>

                            <div class="col-md-6">
                                <input type="password"
                                       class="form-control"
                                       name="password">
                            </div>
                        </div>

                       <!--  <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ route('auth.password.reset') }}">@lang('quickadmin.qa_forgot_password')</a>
                            </div>
                        </div> -->


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <label>
                                    <input type="checkbox"
                                           name="remember"> @lang('quickadmin.qa_remember_me')
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit"
                                        class="btn btn-primary"
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

<nav style="max-height:.8em" class="navbar navbar-default navbar-fixed-bottom">
  <div class="container-fluid">
    <p class="navbar-text pull-right">
        <i class="fa fa-phone-square fa-2x"></i> 2422 &nbsp;&nbsp;
        <i class="fa fa-calendar fa-2x" ></i> {{ date('l, M j  (d-m-y h:i a)')  }}&nbsp;
    </p>
    
     <p class="navbar-text">
      <small><span class="text-muted">
                <i>
                  Last Updated: 24-01-19.&nbsp;</i>
       </span>Recommended Browser: Firefox 25+. 
       @if(config('app.debug'))
        (debugmode)
       @endif
       
       </small>
    </p>

   
    
  </div>
</nav>


@endsection


