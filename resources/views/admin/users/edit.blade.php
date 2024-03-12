@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.users.title')</h3>

     <p>
        Name of officers should start with US, DS etc and followed by space.<br>
       To hide a user from forwadables, set role to 'hidden'.<br>
       'oo.','sn.', 'de.' and 'od.' are reserved userid starting chars. <br>
       For officer userids (JS+), avoid dots.<br>
        Try avoiding usernames contained in another username as this could affect search. eg. ram and ramu. Use initials to make them dissimilar.

    </p>


    
    {!! Form::model($user, ['method' => 'PUT', 'route' => ['admin.users.update', $user->id]]) !!}

    <div class="card p-2">
        <div class="card-title">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('name', trans('quickadmin.users.fields.name').'*', ['class' => 'control-label']) !!}
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
                    {!! Form::label('email', trans('quickadmin.users.fields.email').'', ['class' => 'control-label']) !!}
                    {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('email'))
                        <p class="help-block">
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                </div>
            </div>
           <!--  <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('password', trans('quickadmin.users.fields.password').'*', ['class' => 'control-label']) !!}
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>
            </div> -->
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('password', trans('quickadmin.users.fields.password').'*', ['class' => 'control-label']) !!}
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '', 'id' => 'mypassword']) !!} 
                   <!-- An element to toggle between password visibility -->
                    <input type="checkbox" onclick="ShowPassword()">Show Password
                    <a href="javascript:void(0);" onclick="CreateDefaultPassword();"> Create Default Password </a>
                     <a href="javascript:void(0);" onclick="GeneratePassword();">Generate Password </a>
                    <p class="help-block"></p>
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>
                
            </div>
            <div class="row">
            <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <span class="text-danger">{{ $errors->first('roles') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('username', trans('quickadmin.users.fields.username').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('username'))
                        <p class="help-block">
                            {{ $errors->first('username') }}
                        </p>
                    @endif
                </div>
            </div>
             <div class="row">
                <div class="col-sm-12 form-group">
                    {!! Form::label('displayname', trans('quickadmin.users.fields.displayname').'', ['class' => 'control-label']) !!}
                    {!! Form::text('displayname', old('displayname'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('displayname'))
                        <p class="help-block">
                            {{ $errors->first('displayname') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    <a href="{{route('admin.users.index')}}" class="btn btn-default">Cancel</a>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

<script type="text/javascript">
function ShowPassword() {
  var x = document.getElementById("mypassword");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
function CreateDefaultPassword() {
  var x = document.getElementById("mypassword");
  x.value = "pass123"
    
}
function GeneratePassword() {
    var x = document.getElementById("mypassword");
    var length = 6,
        //charset = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789@",
        charset = "abcdefghjkmnpqrstuvwxyz23456789",
        retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    x.value = retVal;
  
 

}
</script>