@extends('layouts.app')

@section('content')
	<h3 class="page-title">Edit Profile</h3>

	@if(session('success'))
		<!-- If password successfully show message -->
		<div class="row">
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		</div>
	@else
		{!! Form::open(['method' => 'PATCH', 'route' => ['auth.change_displayname']]) !!}
		<!-- If no success message in flash session show change password form  -->
		<div class="panel panel-default">
			<div class="panel-heading">
				@lang('quickadmin.qa_edit') 
			</div>

			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 form-group">
						{!! Form::label('displayname', 'Name of Officer', ['class' => 'control-label']) !!}
						{!! Form::text('displayname', $displayname ?: old('displayname'), ['class' => 'form-control', 'placeholder' => '']) !!}
						<p class="help-block"></p>
						@if($errors->has('displayname'))
							<p class="help-block">
								{{ $errors->first('displayname') }}
							</p>
						@endif
						<p>
						Under Secretary and above : enter only name. <br>
						Section Officer : enter name followed by a coma and 'Section Officer' like <i>yourname, Section Officer</i>. <br>
						Others : enter <i>yourname, Asst/C.A/Supt (abbrev of post)</i>

						
					</p>
					</div>

					

				</div>


			</div>
		</div>

		{!! Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']) !!}
		{!! Form::close() !!}
	@endif
@stop

