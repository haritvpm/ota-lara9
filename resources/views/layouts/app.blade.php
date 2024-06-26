<!DOCTYPE html>
<html>

<head>
    
    @include('partials.head')


    @yield('styles')
</head>

<body class="sidebar-mini layout-fixed" style="height: auto;">
    <div class="wrapper" >
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-auto-collapse-size="768" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
            </ul>
        <!-- Right navbar links -->
      
                <ul class="navbar-nav ml-auto">
               
                <li class="nav-item">
                    <a  class="nav-link" href="#logout" onclick="$('#logout').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    
                    @lang('quickadmin.qa_logout')
                    
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa fa-user"></i>({{Auth::user()->DispNameWithName}})</a>
                </li>
                </ul>
 
        </nav>

        @include('partials.sidebar')
        <div class="content-wrapper" style="min-height: 917px;">
            <!-- Main content -->
            <section class="content" style="padding-top: 20px">
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
                @if($errors->count() > 0)
                    <div class="alert alert-danger"  role="alert">
                        <ul class="list-unstyled">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </section>
            <!-- /.content -->
        </div>

        <form id="logoutform" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>

   

    @yield('scripts')
    @include('partials.javascripts')
    @yield('javascript')
 
</body>

</html>