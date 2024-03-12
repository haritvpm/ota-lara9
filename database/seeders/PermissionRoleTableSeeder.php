<?php

namespace Database\Seeders;

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $admin_permissions = Permission::all();
        
        //superadmin
        Role::findOrFail(9)->permissions()->sync($admin_permissions->pluck('id'));


        $otadmin_permissions = $admin_permissions->filter(function ($permission) {
            return  !Str::startsWith($permission->title, 'attendance_') &&
            !Str::startsWith($permission->title, 'section_') &&
            !Str::startsWith($permission->title, 'device_access') &&
            !Str::startsWith($permission->title, 'officer_') &&
            !Str::startsWith($permission->title, 'user_employee') &&
            !Str::startsWith($permission->title, 'punching_') &&
            !Str::startsWith($permission->title, 'officer_') 
            ;
        });

        Role::findOrFail(1)->permissions()->sync($otadmin_permissions->pluck('id'));

        $user_permissions = $admin_permissions->filter(function ($permission) {
            return  Str::startsWith($permission->title, 'employee_access') 
            || Str::startsWith($permission->title, 'employee_view') 
            || Str::startsWith($permission->title, 'my_form_') 
            || Str::startsWith($permission->title, 'preset_access') 
            || Str::startsWith($permission->title, 'search_access') 
            || Str::startsWith($permission->title, 'report_access') 
            || Str::startsWith($permission->title, 'govt_calendar_access') 
            ;
        });

        Role::findOrFail(2)->permissions()->sync($user_permissions->pluck('id'));


        $otherdeptuser_permissions = $admin_permissions->filter(function ($permission) {
            return  Str::startsWith($permission->title, 'designations_other_') 
            || Str::startsWith($permission->title, 'employees_other_') 
            || Str::startsWith($permission->title, 'my_form_others_') 
            || Str::startsWith($permission->title, 'search_other_access') 
            ;
        });
        Role::findOrFail(3)->permissions()->sync($otherdeptuser_permissions->pluck('id'));

        //cell

        $celladmin_permissions = $admin_permissions->filter(function ($permission) {
            return  Str::startsWith($permission->title, 'attendance_') ||
            Str::startsWith($permission->title, 'section_') ||
            Str::startsWith($permission->title, 'device_access') ||
            Str::startsWith($permission->title, 'officer_') ||
            Str::startsWith($permission->title, 'user_employee') ||
            Str::startsWith($permission->title, 'punching_') ||
            Str::startsWith($permission->title, 'officer_') 
            ;
        });

        Role::findOrFail(8)->permissions()->sync($celladmin_permissions->pluck('id'));

    }
}