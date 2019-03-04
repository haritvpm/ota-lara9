<meta charset="utf-8">
<title>
    @lang('quickadmin.quickadmin_title')
</title>

<meta http-equiv="X-UA-Compatible"
      content="IE=edge">
<meta content="width=device-width, initial-scale=1.0"
      name="viewport"/>
<meta http-equiv="Content-type"
      content="text/html; charset=utf-8">

<meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://cdnjs.cloudflare.com ">


<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Font Awesome -->

<link rel="stylesheet"
      href="{{ url('quickadmin/css') }}/font-awesome.min.css"/>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- <link href="{{ URL::asset('css/flatpickr.min.css') }}" rel="stylesheet"> -->

<link rel="stylesheet"
      href="{{ url('quickadmin/css') }}/select2.min.css"/>
<link href="{{ url('adminlte/css/AdminLTE.min.css') }}" rel="stylesheet">
<link href="{{ url('adminlte/css/custom.css') }}" rel="stylesheet">

<!-- also change in layouts.app.blade -->

if(!\Config::get('custom.vps'))
<!-- @if(1) -->
<link href="{{ URL::asset('css/ionicons.min.css') }}" rel="stylesheet">

<link href="{{ url('adminlte/css/skins/skin-blue.min.css') }}" rel="stylesheet">

<!-- this is needed for jquery datepicker. we use dp in admin form edit -->
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"> -->
<link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet">

<!-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css"/> -->
<link href="{{ URL::asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">

<!-- <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css"/> -->
<link href="{{ URL::asset('css/select.dataTables.min.css') }}" rel="stylesheet">


<!-- <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.0.0/dist/vue-multiselect.min.css"> -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" /> -->
<link href="{{ URL::asset('css/vue-multiselect.min.css') }}" rel="stylesheet">


<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css"/> -->
<link href="{{ URL::asset('css/bootstrap-datepicker.standalone.min.css') }}" rel="stylesheet">


<link href="{{ url('adminlte/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

<link href="{{ URL::asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">


@else

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">


<link href="{{ url('adminlte/css/skins/skin-yellow.min.css') }}" rel="stylesheet">
<!-- this is needed for jquery datepicker. we use dp in admin form edit -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">

<link rel="stylesheet" href="//cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css"/>

<link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css"/>


<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.0.0/dist/vue-multiselect.min.css">
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" /> -->


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css"/>


<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/css/bootstrap-datetimepicker.min.css">


@endif





<!-- <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css"/> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css"/> -->



<link rel="shortcut icon" href="{{ URL::asset('favicon.ico') }}" type="image/x-icon">

<!-- <script src="{{ URL::asset('js/pace.min.js') }}"></script>
<link href="{{ URL::asset('css/pace_flash.css') }}" rel="stylesheet"> -->





<style type="text/css">
textarea {
   resize: none;
 }

/*th { font-size: 0.9em; }*/
/*td { font-size: 0.9em; }*/

</style>