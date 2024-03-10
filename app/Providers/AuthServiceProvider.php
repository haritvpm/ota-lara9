<?php

namespace App\Providers;

use App\Role;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->registerPolicies();

        $user = \Auth::user();


        // Auth gates for: User management
        Gate::define('user_management_access', function ($user) {
            return in_array($user->role_id, [9,1]);
        });

        // Auth gates for: Roles
        Gate::define('role_access', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('role_create', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('role_edit', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('role_view', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('role_delete', function ($user) {
            return in_array($user->role_id, [9,1]);
        });

        // Auth gates for: Users
        Gate::define('user_access', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('user_create', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('user_edit', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('user_view', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('user_delete', function ($user) {
            return in_array($user->role_id, [9,1]);
        });

        // Auth gates for: Designations
        Gate::define('designation_access', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('designation_create', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('designation_edit', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('designation_view', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('designation_delete', function ($user) {
            return in_array($user->role_id, [9,1]);
        });


        // Auth gates for: Employees
        //6=services
        Gate::define('employee_access', function ($user) {
            return in_array($user->role_id, [9,1, 2,6]);
        });
        Gate::define('employee_create', function ($user) {
            return in_array($user->role_id, [9,1, 2]);
        });
        Gate::define('employee_edit', function ($user) {
            return in_array($user->role_id, [9,1, 2]);
        });
        Gate::define('employee_view', function ($user) {
            return in_array($user->role_id, [9,1, 2,6]);
        });
        Gate::define('employee_delete', function ($user) {
            return in_array($user->role_id, [9,1]);
        });

        // Auth gates for: Sessions
        Gate::define('session_access', function ($user) {
            return in_array($user->role_id, [1,6]);
        });
        Gate::define('session_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('session_edit', function ($user) {
            return in_array($user->role_id, [1,6]);
        });
        Gate::define('session_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('session_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Calenders
        Gate::define('calender_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('calender_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('calender_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('calender_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('calender_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Settings
        Gate::define('setting_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('setting_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('setting_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('setting_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('setting_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Routing
        Gate::define('routing_access', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('routing_create', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('routing_edit', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('routing_view', function ($user) {
            return in_array($user->role_id, [9,1]);
        });
        Gate::define('routing_delete', function ($user) {
            return in_array($user->role_id, [9,1]);
        });

        // Auth gates for: Forms
        Gate::define('form_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('form_create', function ($user) {
            return in_array($user->role_id, [1,2]);
        });
        Gate::define('form_edit', function ($user) {
            return in_array($user->role_id, [1,2]);
        });
        Gate::define('form_view', function ($user) {
            return in_array($user->role_id, [1,2]);
        });
        Gate::define('form_delete', function ($user) {
            return in_array($user->role_id, [1,2]);
        });

        // Auth gates for: Overtimes
        Gate::define('overtime_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('overtime_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('overtime_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('overtime_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('overtime_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });


        // Auth gates for: My forms
        Gate::define('my_form_access', function ($user) {
            return in_array($user->role_id, [1,2,5]);
        });
        Gate::define('my_form_create', function ($user) {
            return in_array($user->role_id, [2]);
        });
        Gate::define('my_form_edit', function ($user) {
            return in_array($user->role_id, [1,2]);
        });
        Gate::define('my_form_delete', function ($user) {
            return in_array($user->role_id, [1,2]);
        });


        

        // Auth gates for: My forms
        Gate::define('pa2mlaform_access', function ($user) {
            return in_array($user->role_id, [1]);
        });


        // Auth gates for: Designations other
        Gate::define('designations_other_access', function ($user) {
            return in_array($user->role_id, [1, 3]);
        });
        Gate::define('designations_other_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('designations_other_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('designations_other_view', function ($user) {
            return in_array($user->role_id, [1, 3]);
        });
        Gate::define('designations_other_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Employees other
        Gate::define('employees_other_access', function ($user) {
            return in_array($user->role_id, [1, 3]);
        });
        Gate::define('employees_other_create', function ($user) {
            return in_array($user->role_id, [1, 3]);
        });
        Gate::define('employees_other_edit', function ($user) {
            return in_array($user->role_id, [1, 3]);
        });
        Gate::define('employees_other_view', function ($user) {
            return in_array($user->role_id, [1, 3]);
        });
        Gate::define('employees_other_delete', function ($user) {
            return in_array($user->role_id, [1, 3]);
        });


        //my forms other
        Gate::define('my_form_others_access', function ($user) {
            return in_array($user->role_id, [1,3]);
        });
        Gate::define('my_form_others_create', function ($user) {
            return in_array($user->role_id, [3]);
        });
        Gate::define('my_form_others_edit', function ($user) {
            return in_array($user->role_id, [1,3]);
        });


        // preset_access
        Gate::define('preset_access', function ($user) {
            return in_array($user->role_id, [1,2]);
        });
         Gate::define('preset_massdelete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Forms others
        Gate::define('forms_other_access', function ($user) {
            return in_array($user->role_id, [1, 3]);
        });
        Gate::define('forms_other_create', function ($user) {
            return in_array($user->role_id, [1, 3]);
        });
        Gate::define('forms_other_edit', function ($user) {
            return in_array($user->role_id, [1, 3]);
        });
        Gate::define('forms_other_view', function ($user) {
            return in_array($user->role_id, [1, 3]);
        });
        Gate::define('forms_other_delete', function ($user) {
            return in_array($user->role_id, [1, 3]);
        });

        // Auth gates for: Raw data
        Gate::define('raw_datum_access', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Overtimes others
        Gate::define('overtimes_other_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('overtimes_other_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('overtimes_other_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('overtimes_other_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('overtimes_other_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        Gate::define('search_access', function ($user) {
            return in_array($user->role_id, [1,2,5]);
        });
        Gate::define('search_other_access', function ($user) {
            return in_array($user->role_id, [1,3]);
        });

        // Auth gates for: Report
        Gate::define('report_access', function ($user) {
            return in_array($user->role_id, [1,2,5]);
        });

         // Auth gates for: Attendance
        Gate::define('attendance_access', function ($user) {
            return in_array($user->role_id, [1, 4]);
        });
        Gate::define('attendance_create', function ($user) {
            return in_array($user->role_id, [1,4]);
        });
        Gate::define('attendance_edit', function ($user) {
            return in_array($user->role_id, [1,4]);
        });
        Gate::define('attendance_view', function ($user) {
            return in_array($user->role_id, [1, 2,4]);
        });
        Gate::define('attendance_delete', function ($user) {
            return in_array($user->role_id, [1,4]);
        });

         // Auth gates for: Categories
        Gate::define('category_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('category_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('category_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('category_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('category_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        //punching admin
        //8  = it cell
        //6=services
        //9= superadmin basically hari
        Gate::define('punching_management_access', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('punching_trace_access', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('device_access', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });

        
        Gate::define('govt_calendar_edit', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('govt_calendar_access', function ($user) {
            return in_array($user->role_id, [2,6,9,8]);
        });

        Gate::define('section_access', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('section_employee_access', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('officer_mapping_access', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('user_employee_access', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        
        Gate::define('section_create', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('section_edit', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('section_delete', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
       
        Gate::define('section_employee_create', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
       
        Gate::define('section_employee_edit', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('section_employee_delete', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
      
        Gate::define('user_employee_create', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
       
        Gate::define('user_employee_edit', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('user_employee_delete', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('officer_mapping_create', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
       
        Gate::define('officer_mapping_edit', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('officer_mapping_delete', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        });
        Gate::define('officer_employee_access', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        }); 
        Gate::define('officer_employee_create', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        }); 
        Gate::define('officer_employee_edit', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        }); 
        Gate::define('officer_employee_delete', function ($user) {
            return in_array($user->role_id, [6,9,8]);
        }); 
    }
}
