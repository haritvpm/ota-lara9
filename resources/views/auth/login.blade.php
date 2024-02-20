

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
    <div class="row justify-content-md-center">
        <div class="col-md-4">
            <div class="card p-2">
              
                
                <div class="card-body">
                    
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

                            <div class="col-md-12">
                                <input type="text"
                                       class="form-control"
                                       name="username"
                                       value="{{ old('username') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">@lang('quickadmin.qa_password')</label>

                            <div class="col-md-12">
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


@endsection

