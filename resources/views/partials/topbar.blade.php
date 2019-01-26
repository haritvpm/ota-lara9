<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/admin/home') }}" class="logo"
       style="font-size: 16px;">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
           @lang('quickadmin.quickadmin_title')</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
           @lang('quickadmin.quickadmin_title')</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <div class="container-fluid">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
          <p class="navbar-text text-default"> &nbsp;<small>SECRETARIAT OF THE KERALA LEGISLATURE</small></p>
          


        <!-- Collect the nav links, forms, and other content for toggling -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                
                <p class="navbar-text pull-right" style="color:#F7F700;"> <i class="fa fa-user"></i><b> &nbsp;{{Auth::user()->DispNameWithName}}</b><span class="caret"></span></p> </a>
              <ul class="dropdown-menu pull-right" role="menu">
                
                <li><a href="{{ route('auth.change_displayname') }}">Profile</a></li>
                <li><a href="{{ route('auth.change_password') }}">
                    <i class="fa fa-key"></i>
                    <span class="title">@lang('quickadmin.qa_change_password')</span>
                </a></li>

                <li class="divider"></li>
               <li>
                <a href="#logout" onclick="$('#logout').submit();">
                    <i class="fa fa-arrow-left"></i>
                    <span class="title">@lang('quickadmin.qa_logout') ({{Auth::user()->username}})</span>
                </a>
               </li>
              </ul>

              @if(!Auth::user()->isAdminorAudit() )
              <a class="navbar-text pull-right" style="color:#F7F7F7;" href="<?=URL::to('help/guide.html')?>"  target="_blank" >User-Guide</a>
              @endif

               
           
        </div><!-- /.container-fluid -->
     </nav>
</header>


{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">@lang('quickadmin.logout')</button>
{!! Form::close() !!}
