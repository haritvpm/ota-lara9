<!DOCTYPE html>
<html lang="en">

<head>
 
    @include('partials.head')

</head>


<!-- also change in layouts.app.blade -->
@if(!env('VPS',0))
<body class="hold-transition skin-blue sidebar-mini {{ (  isset($collapse_sidebar) ? 'sidebar-collapse' : '') }} ">
@else
<body class="hold-transition skin-yellow sidebar-mini {{ (  isset($collapse_sidebar) ? 'sidebar-collapse' : '') }} ">
@endif

<div id="wrapper">

@include('partials.topbar')
@include('partials.sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" >
        <!-- Main content -->
        <section class="content">
            @if(isset($siteTitle))
                <h3 class="page-title">
                    {{ $siteTitle }}
                </h3>
            @endif

            <div class="row">
                <div class="col-md-12">
               

                    <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('message-' . $msg))
                        <div class="alert alert-{{ $msg }} alert-dismissable hidden-print">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>	
                        {{ Session::get('message-' . $msg) }}
                        </div>
                        @endif
                        {{Session::forget('message-' . $msg)}}
                    @endforeach
                    
                    </div>

                    @if ($errors->count() > 0)
                        <div class="alert alert-danger alert-dismissable hidden-print">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')

                </div>
            </div>
        </section>

      

    </div>

</div>

{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">Logout</button>
{!! Form::close() !!}


@include('partials.javascripts')



</body>




</html>