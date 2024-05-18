<!-- @inject('request', 'Illuminate\Http\Request') -->
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar  sidebar-dark-primary elevation-4">
<a href="#" class="brand-link">
        <span class="brand-text font-weight-light">OvertimeAllowanceApp</span>
    </a>
    <section class="sidebar">
        <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-item">
                    <a class="nav-link {{ request()->is("*home")  ? "active" : "" }}" href="{{ url('/') }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('quickadmin.qa_dashboard') }}
                        </p>
                    </a>
                </li>

            @if( \Config::get('custom.show_legsectt'))
            @can('my_form_access')
            <li class="nav-item">
                    
                    <a href="{{ route("admin.my_forms2.index") }}" class="nav-link {{ request()->is("admin/my_forms2") || request()->is("admin/my_forms2/*") ? "active" : "" }}">
                    <i class="fa-fw nav-icon far fa-copy"></i>
                        <p>
                        My forms
                        </p>
                    </a>
                </li>
              
              <li class="nav-item">
                    
                  
                 <a href="{{ route("admin.my_forms.index") }}" class="nav-link {{ request()->is("admin/my_forms") || request()->is("admin/my_forms/*") ? "active" : "" }}">
                    <i class="fa-fw nav-icon far fa-copy"></i>
                        <p>
                        Old forms
                        </p>
                    </a>
                </li>
            @endcan
            @can('pa2mlaform_access')
               <li class="nav-item">
                    <a class="nav-link {{ request()->is("*pa2mlaforms")  ? "active" : "" }}" href="{{ route('admin.pa2mlaforms.index') }}">
                    <i class="fa-fw nav-icon far fa-copy"></i>
                        <p>
                        PA2MLA Forms
                        </p>
                    </a>
                </li>
            @endcan
            @endif

            @can('my_form_others_access')
            <li class="nav-item">
                    <a class="nav-link {{ request()->is("*my_forms_others")  ? "active" : "" }}" href="{{ route('admin.my_forms_others.index') }}">
                    <i class="fa-fw nav-icon far fa-copy"></i>
                        <p>
                        Forms Other Dept
                        </p>
                    </a>
                </li>
            @endcan

            @if(  \Config::get('custom.show_legsectt'))
            @can('search_access')
            <li class="nav-item">
                    <a class="nav-link {{ request()->is("*searches")  ? "active" : "" }}" href="{{ route('admin.searches.index') }}">
                    <i class="fas fa-fw  fa-search  nav-icon"></i>
                        <p>
                        Search
                        </p>
                    </a>
                </li>

            @endcan
            @endif

            @can('search_other_access')
            
            <li class="nav-item">
                    <a class="nav-link {{ request()->is("*searches_other")  ? "active" : "" }}" href="{{ route('admin.searches_other.index') }}">
                    <i class="fas fa-fw  fa-search  nav-icon"></i>
                        <p>
                        Search Other Dept
                        </p>
                    </a>
                </li>
            @endcan

            @can('employee_management_access')
            <li class="nav-item has-treeview {{ request()->is("*admin/employees*") ? "menu-open" : "" }} {{ request()->is("admin/designations*") ? "menu-open" : "" }} {{ request()->is("admin/categories*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-users ">

                            </i>
                            <p>
                                Employees
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>

            <ul class="nav nav-treeview">
               

            @if(  \Config::get('custom.show_legsectt'))
            @can('employee_access')
            
            <li class="nav-item">
                <a href="{{ route("admin.employees.index") }}" class="nav-link {{ request()->is("*admin/employees") || request()->is("*admin/employees/*") ? "active" : "" }}">
                    <i class="fa-fw nav-icon fas fa-user">
                    </i>
                    <p>
                    @lang('quickadmin.employees.title')
                    </p>
                </a>
            </li>

            @endcan
            @endif
             
            @can('employees_other_access')

            <li class="nav-item">
                <a href="{{ route("admin.employees_others.index") }}" class="nav-link {{ request()->is("*admin/employees_others*") ? "active" : "" }}">
                    <i class="fa-fw nav-icon fas fa-user">
                    </i>
                    <p>
                    @lang('quickadmin.employees-other.title')
                    </p>
                </a>
            </li>
            @endcan

            @if(  \Config::get('custom.show_legsectt'))
                @can('designation_access')
                    
                <li class="nav-item">
                    <a href="{{ route("admin.designations.index") }}" class="nav-link {{ request()->is("*admin/designations") || request()->is("*admin/designations/*")  ? "active" : "" }}">
                        <i class="fa-fw nav-icon fas fa-id-badge">
                        </i>
                        <p>
                        @lang('quickadmin.designations.title')
                        </p>
                    </a>
                </li>

                @endcan
                @endif

                 @can('designations_other_access')
                <li class="nav-item">
                    <a href="{{ route("admin.designations_others.index") }}" class="nav-link {{ request()->is("*admin/designations_others*") ? "active" : "" }}">
                        <i class="fa-fw nav-icon fas fa-id-badge">
                        </i>
                        <p>
                        @lang('quickadmin.designations-other.title')
                        </p>
                    </a>
                </li>

                @endcan
                @can('category_access')
                <li class="nav-item">
                    <a href="{{ route("admin.categories.index") }}" class="nav-link {{ request()->is("*admin/categories*") ? "active" : "" }}">
                        <i class="fa-fw nav-icon fas fa-th-large">
                        </i>
                        <p>
                        Categories
                        </p>
                    </a>
                </li>
                @endcan
            </ul>
            </li>
            @endcan
            @can('punching_management_access')
            <li class="nav-item has-treeview {{ request()->is("admin/punchings*") ? "menu-open" : "" }} {{ request()->is("admin/punching-traces*") ? "menu-open" : "" }} {{ request()->is("admin/devices*") ? "menu-open" : "" }} {{ request()->is("admin/govt-calendars*") ? "menu-open" : "" }} {{ request()->is("admin/sections*") ? "menu-open" : "" }} {{ request()->is("admin/section-employees*") ? "menu-open" : "" }} {{ request()->is("admin/officer-employees*") ? "menu-open" : "" }} {{ request()->is("admin/officer-mappings*") ? "menu-open" : "" }} {{ request()->is("admin/user-employees*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/punchings*") ? "active" : "" }} {{ request()->is("admin/punching-traces*") ? "active" : "" }} {{ request()->is("admin/devices*") ? "active" : "" }} {{ request()->is("admin/govt-calendars*") ? "active" : "" }} {{ request()->is("admin/sections*") ? "active" : "" }} {{ request()->is("admin/section-employees*") ? "active" : "" }} {{ request()->is("admin/officer-employees*") ? "active" : "" }} {{ request()->is("admin/officer-mappings*") ? "active" : "" }} {{ request()->is("admin/user-employees*") ? "active" : "" }}" href="#">
                                    <i class="fa-fw nav-icon fas fa-address-card">

                            </i>
                            <p>
                                {{ trans('cruds.punchingManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route("admin.punchings.index") }}" class="nav-link {{ request()->is("admin/punchings") || request()->is("admin/punchings/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.punching.title') }}
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route("admin.punching-traces.index") }}" class="nav-link {{ request()->is("admin/punching-traces") || request()->is("admin/punching-traces/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.punchingTrace.title') }}
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route("admin.devices.index") }}" class="nav-link {{ request()->is("admin/devices") || request()->is("admin/devices/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-laptop">

                                        </i>
                                        <p>
                                            {{ trans('cruds.device.title') }}
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route("admin.govt-calendars.index") }}" class="nav-link {{ request()->is("admin/govt-calendars") || request()->is("admin/govt-calendars/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.govtCalendar.title') }}
                                        </p>
                                    </a>
                                </li>

                                @can('section_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.sections.index") }}" class="nav-link {{ request()->is("admin/sections") || request()->is("admin/sections/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-puzzle-piece">

                                        </i>
                                        <p>
                                            {{ trans('cruds.section.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('section_employee_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.section-employees.index") }}" class="nav-link {{ request()->is("admin/section-employees") || request()->is("admin/section-employees/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user-plus">

                                        </i>
                                        <p>
                                            {{ trans('cruds.sectionEmployee.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('officer_employee_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.officer-employees.index") }}" class="nav-link {{ request()->is("admin/officer-employees") || request()->is("admin/officer-employees/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-user-plus">

                                        </i>
                                        <p>
                                            {{ trans('cruds.officerEmployee.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('officer_mapping_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.officer-mappings.index") }}" class="nav-link {{ request()->is("admin/officer-mappings") || request()->is("admin/officer-mappings/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-link">

                                        </i>
                                        <p>
                                            {{ trans('cruds.officerMapping.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('user_employee_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.user-employees.index") }}" class="nav-link {{ request()->is("admin/user-employees") || request()->is("admin/user-employees/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-link">

                                        </i>
                                        <p>
                                            {{ trans('cruds.userEmployee.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>

                    </li>
            @endcan
            <!-- @can('attendance_access')
            <li class="nav-item">
                    <a class="nav-link {{ request()->is("*punchings")  ? "active" : "" }}" href="{{ route('admin.punchings.index') }}">
                    <i class="fas fa-fw  fa fa-id-card  nav-icon"></i>
                        <p>
                        Punching
                        </p>
                    </a>
            </li>
            
             <li class="nav-item">
                    <a class="nav-link {{ request()->is("*attendances")  ? "active" : "" }}" href="{{ route('admin.attendances.index') }}">
                    <i class="fas fa-fw  fa fa-id-card nav-icon"></i>
                        <p>
                        @lang('quickadmin.attendance.title')
                        </p>
                    </a>
                </li> 
           
            @endcan -->
            @can('attendance_access')
                    <li class="nav-item has-treeview {{ request()->is("admin/punching-registers*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/punching-registers*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon far fa-calendar-check">

                            </i>
                            <p>
                                {{ trans('cruds.attendance.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('punching_register_access')
                                <li class="nav-item">
                                    <a href="{{ route("admin.punching-registers.index") }}" class="nav-link {{ request()->is("admin/punching-registers") || request()->is("admin/punching-registers/*") ? "active" : "" }}">
                                        <i class="fa-fw nav-icon fas fa-cogs">

                                        </i>
                                        <p>
                                            {{ trans('cruds.punchingRegister.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

        
            
            @can('user_management_access')
             <li class="nav-item has-treeview {{ request()->is("*admin/routings*") ? "menu-open" : "" }} {{ request()->is("admin/roles*") ? "menu-open" : "" }} {{ request()->is("admin/users*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa-fw nav-icon fas fa-user-circle">

                            </i>
                            <p>
                           Users
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>

                <ul class="nav nav-treeview">
                @can('user_access')
                
                    <li class="nav-item">
                        <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is("*admin/users*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-user">

                            </i>
                            <p>
                            @lang('quickadmin.users.title')
                            </p>
                        </a>
                    </li>
                @endcan
                @can('role_access')
                <li class="nav-item">
                        <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is("*admin/roles*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-briefcase">

                            </i>
                            <p>
                            @lang('quickadmin.roles.title')
                            </p>
                        </a>
                    </li>
             
                @endcan
              
                @can('routing_access')
                <li class="nav-item">
                        <a href="{{ route("admin.routings.index") }}" class="nav-link {{ request()->is("*admin/routings*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-mail-forward">

                            </i>
                            <p>
                            @lang('quickadmin.routing.title')
                            </p>
                        </a>
                    </li>
                        
                @endcan
                                
                </ul>
            </li>
            @endcan
            
            
            @can('session_access')
     
            <li class="nav-item">
                    <a class="nav-link {{ request()->is("*sessions")  ? "active" : "" }}" href="{{ route('admin.sessions.index') }}">
                    <i class="fas fa-fw  fa fa-university  nav-icon"></i>
                        <p>
                        @lang('quickadmin.sessions.title')
                        </p>
                    </a>
            </li>
            @endcan
            
            @can('calender_access')
       
            <li class="nav-item">
                    <a class="nav-link {{ request()->is("*calenders")  ? "active" : "" }}" href="{{ route('admin.calenders.index') }}">
                    <i class="fas fa-fw  fa fa-calendar  nav-icon"></i>
                        <p>
                        @lang('quickadmin.calenders.title')
                        </p>
                    </a>
            </li>
            @endcan
           
                       
<!--            
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
            @endcan -->

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

            
       


            @can('preset_access')
            <li class="nav-item">
                    <a class="nav-link {{ request()->is("*presets")  ? "active" : "" }}" href="{{ route('admin.presets.index') }}">
                    <i class="fas fa-fw  fa fa-gears  nav-icon"></i>
                        <p>
                        Presets
                        </p>
                    </a>
            </li>
            @endcan
            @can('setting_access')
            
            <li class="nav-item">
                    <a class="nav-link {{ request()->is("*settings")  ? "active" : "" }}" href="{{ route('admin.settings.index') }}">
                    <i class="fas fa-fw  fa fa-gears  nav-icon"></i>
                        <p>
                        @lang('quickadmin.settings.title')
                        </p>
                    </a>
        
            @endcan
            @can('setting_access')
            <li class="nav-item">
                    <a class="nav-link {{ request()->is("*backups")  ? "active" : "" }}" href="{{ route('admin.backups.index') }}">
                    <i class="fas fa-fw   fa-file-archive-o nav-icon"></i>
                        <p>
                        Backups
                        </p>
                    </a>
            </li>
            @endcan
           <!--  <li class="nav-item">
            <a  class="nav-link" href="#logout" onclick="$('#logout').submit();">
                    <i class="fas fa-fw  fa-arrow-left nav-icon"></i>
                    <p>
                    @lang('quickadmin.qa_logout')
                    </p> 
                </a>
                </li> -->
           
        </ul>
        </nav>
    </section>
</aside>
{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">@lang('quickadmin.logout')</button>
{!! Form::close() !!}
