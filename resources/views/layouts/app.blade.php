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
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
            </ul>
        <!-- Right navbar links -->
      
                <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-user"></i>({{Auth::user()->DispNameWithName}})</a>
                </li>
                </ul>
 
        </nav>

        @include('partials.sidebar')
        <div class="content-wrapper" style="min-height: 917px;">
            <!-- Main content -->
            <section class="content" style="padding-top: 20px">
                @if(session('message'))
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                        </div>
                    </div>
                @endif
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