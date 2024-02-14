@inject('request', 'Illuminate\Http\Request')
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">

            <li class="{{ $request->segment(1) == 'home' ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <i class="fa fa-bolt"></i>
                    <span class="title">@lang('quickadmin.qa_dashboard')</span>
                </a>
            </li>

            @if( \Config::get('custom.show_legsectt'))
            @can('my_form_access')
            <li class="{{ $request->segment(2) == 'my_forms' ? 'active' : '' }}">
                <a href="{{ route('admin.my_forms.index') }}">
                    <i class="fa fa-file-o"></i>
                    <span class="title">My forms</span>
                </a>
            </li>
            @endcan

           
        


            @can('pa2mlaform_access')
            <li class="{{ $request->segment(2) == 'forms' ? 'active' : '' }}">
                <a href="{{ route('admin.pa2mlaforms.index') }}">
                    <i class="fa fa-files-o"></i>
                    <span class="title">PA2MLA Forms</span>
                </a>
            </li>
            @endcan
            @endif

            @can('my_form_others_access')
            <li class="{{ $request->segment(2) == 'my_forms_others' ? 'active' : '' }}">
                <a href="{{ route('admin.my_forms_others.index') }}">
                    <i class="fa fa-file-o"></i>
                    <span class="title">Forms Other Dept</span>
                </a>
            </li>
            @endcan

             @if(  \Config::get('custom.show_legsectt'))
            @can('search_access')
            <li class="{{ $request->segment(2) == 'searches' ? 'active' : '' }}">
                <a href="{{ route('admin.searches.index') }}">
                    <i class="fa fa-search"></i>
                    <span class="title">Search</span>
                </a>
            </li>
            @endcan
            @endif

            @can('search_other_access')
             <li class="{{ $request->segment(2) == 'searches_other' ? 'active' : '' }}">
                <a href="{{ route('admin.searches_other.index') }}">
                    <i class="fa fa-search"></i>
                    <span class="title">Search Other Dept</span>
                </a>
            </li>
            @endcan

            @can('user_management_access')
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span class="title">Employee Management</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                

            @if(  \Config::get('custom.show_legsectt'))
            @can('employee_access')
            
            <li class="{{ $request->segment(2) == 'employees' ? 'active active-sub' : '' }}">
                <a href="{{ route('admin.employees.index') }}">
                    <i class="fa fa-user"></i>
                    <span class="title">@lang('quickadmin.employees.title')</span>
                </a>
            </li>
            @endcan
            @endif
             
            @can('employees_other_access')
            <li class="{{ $request->segment(2) == 'employees_others' ? 'active active-sub' : '' }}">
                <a href="{{ route('admin.employees_others.index') }}">
                    <i class="fa fa-user"></i>
                    <span class="title">@lang('quickadmin.employees-other.title')</span>
                </a>
            </li>
            @endcan

            @if(  \Config::get('custom.show_legsectt'))
                @can('designation_access')
                <li class="{{ $request->segment(2) == 'designations' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.designations.index') }}">
                            <i class="fa fa-rupee"></i>
                            <span class="title">
                                @lang('quickadmin.designations.title')
                            </span>
                        </a>
                    </li>
                @endcan
                @endif

                 @can('designations_other_access')
                <li class="{{ $request->segment(2) == 'designations_others' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.designations_others.index') }}">
                            <i class="fa fa-rupee"></i>
                            <span class="title">
                                @lang('quickadmin.designations-other.title')
                            </span>
                        </a>
                    </li>
                @endcan
                @can('category_access')
                <li class="{{ $request->segment(2) == 'categories' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.categories.index') }}">
                            <i class="fa fa-gears"></i>
                            <span class="title">
                                @lang('quickadmin.categories.title')
                            </span>
                        </a>
                    </li>
                @endcan



            </ul>
            </li>
            @endcan
           
            @can('attendance_access')
            <li class="{{ $request->segment(2) == 'punching' ? 'active' : '' }}">
                <a href="{{ route('admin.punchings.index') }}">
                    <i class="fa fa-list"></i>
                    <span class="title">Punching</span>
                </a>
            </li>
            @endcan

            @can('attendance_access')
            <li class="{{ $request->segment(2) == 'attendances' ? 'active' : '' }}">
                <a href="{{ route('admin.attendances.index') }}">
                    <i class="fa fa-check-square-o"></i>
                    <span class="title">@lang('quickadmin.attendance.title')</span>
                </a>
            </li>
            @endcan

            
        
            
            @can('user_management_access')
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span class="title">@lang('quickadmin.user-management.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                @can('user_access')
                <li class="{{ $request->segment(2) == 'users' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fa fa-user"></i>
                            <span class="title">
                                @lang('quickadmin.users.title')
                            </span>
                        </a>
                    </li>
                @endcan
                @can('role_access')
                <li class="{{ $request->segment(2) == 'roles' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">
                                @lang('quickadmin.roles.title')
                            </span>
                        </a>
                    </li>
                @endcan
              
                @can('routing_access')
                <li class="{{ $request->segment(2) == 'routings' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.routings.index') }}">
                            <i class="fa fa-mail-forward"></i>
                            <span class="title">
                                @lang('quickadmin.routing.title')
                            </span>
                        </a>
                    </li>
                @endcan
                
                
                </ul>
            </li>
            @endcan
            
            
            @can('preset_access')

            <li class="{{ $request->segment(2) == 'presets' ? 'active' : '' }}">
                <a href="{{ route('admin.presets.index') }}">
                    <i class="fa fa-gears"></i>
                    <span class="title">Presets</span>
                </a>
            </li>
            @endcan

            @can('session_access')
            <li class="{{ $request->segment(2) == 'sessions' ? 'active' : '' }}">
                <a href="{{ route('admin.sessions.index') }}">
                    <i class="fa fa-university"></i>
                    <span class="title">@lang('quickadmin.sessions.title')</span>
                </a>
            </li>
            @endcan
            
            @can('calender_access')
            <li class="{{ $request->segment(2) == 'calenders' ? 'active' : '' }}">
                <a href="{{ route('admin.calenders.index') }}">
                    <i class="fa fa-calendar"></i>
                    <span class="title">@lang('quickadmin.calenders.title')</span>
                </a>
            </li>
            @endcan
           
                       
           
            @if(  \Config::get('custom.show_legsectt'))
            @can('report_access')
            <li class="{{ $request->segment(2) == 'reports' ? 'active' : '' }}">
                <a href="{{ route('admin.reports.index') }}">
                    <i class="fa fa-print"></i>
                    <span class="title">Reports</span>
                </a>
            </li>
            @endcan
            @endif

            @can('search_other_access')
            <li class="{{ $request->segment(2) == 'reports_others' ? 'active' : '' }}">
                <a href="{{ route('admin.reports_others.index') }}">
                    <i class="fa fa-print"></i>
                    <span class="title">Reports OtherDept</span>
                </a>
            </li>
            @endcan

            @if( \Config::get('custom.show_rawdata') )
            @can('raw_datum_access')

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span class="title">@lang('quickadmin.raw-data.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                
                @if(  \Config::get('custom.show_legsectt'))
                @can('form_access')
                <li class="{{ $request->segment(2) == 'forms' ? 'active active-sub' : '' }}">
                    <a href="{{ route('admin.forms.index') }}">
                        <i class="fa fa-files-o"></i>
                        <span class="title">@lang('quickadmin.forms.title')</span>
                    </a>
                </li>
                @endcan
                
                @can('overtime_access')
                <li class="{{ $request->segment(2) == 'overtimes' ? 'active active-sub' : '' }}">
                    <a href="{{ route('admin.overtimes.index') }}">
                        <i class="fa fa-database"></i>
                        <span class="title">@lang('quickadmin.overtimes.title')</span>
                    </a>
               
                @endcan
                @endif


                @can('forms_other_access')
                <li class="{{ $request->segment(2) == 'forms_others' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.forms_others.index') }}">
                            <i class="fa fa-files-o"></i>
                            <span class="title">
                                @lang('quickadmin.forms-others.title')
                            </span>
                        </a>
                    </li>
                @endcan
                @can('overtimes_other_access')
                <li class="{{ $request->segment(2) == 'overtimes_others' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.overtimes_others.index') }}">
                            <i class="fa fa-database"></i>
                            <span class="title">
                                @lang('quickadmin.overtimes-others.title')
                            </span>
                        </a>
                    </li>
                @endcan

             
                </ul>
            </li>
            @endcan
            @endif

            
            @can('setting_access')
            <li class="{{ $request->segment(2) == 'backups' ? 'active' : '' }}">
                <a href="{{ route('admin.backups.index') }}">
                    <i class="fa fa-file-archive-o"></i>
                    <span class="title">Backups</span>
                </a>
            </li>
            @endcan


            @can('setting_access')
            <li class="{{ $request->segment(2) == 'settings' ? 'active' : '' }}">
                <a href="{{ route('admin.settings.index') }}">
                    <i class="fa fa-gears"></i>
                    <span class="title">@lang('quickadmin.settings.title')</span>
                </a>
            </li>
            @endcan
<!-- 
            <li class="{{ $request->segment(1) == 'change_password' ? 'active' : '' }}">
                <a href="{{ route('auth.change_password') }}">
                    <i class="fa fa-key"></i>
                    <span class="title">@lang('quickadmin.qa_change_password')</span>
                </a>
            </li>
 -->
            <li>
                <a href="#logout" onclick="$('#logout').submit();">
                    <i class="fa fa-arrow-left"></i>
                    <span class="title">@lang('quickadmin.qa_logout')</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">@lang('quickadmin.logout')</button>
{!! Form::close() !!}
