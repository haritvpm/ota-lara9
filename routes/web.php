<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect('/admin/home'); });

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('auth.login');
Route::post('login', 'Auth\LoginController@login')/*->name('auth.login')*/;
Route::post('logout', 'Auth\LoginController@logout')->name('auth.logout');

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')/*->name('auth.change_password')*/;

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')/*->name('auth.password.reset')*/;
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')/*->name('auth.password.reset')*/;


Route::get('change_displayname', 'Auth\ProfileController@showChangeDisplaynameForm')->name('auth.change_displayname');
Route::patch('change_displayname', 'Auth\ProfileController@changeDisplayname')/*->name('auth.change_displayname')*/;


Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index');
    
    Route::get('/goview/{file}', 'HomeController@goview');
    
    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    
    
    Route::get('users/clearold','Admin\UsersController@clearold');

    Route::get('users/password_reset/{id}', ['uses' => 'Admin\UsersController@password_reset', 'as' => 'users.password_reset']);

    Route::get('users/create_dataentry/{id}', ['uses' => 'Admin\UsersController@create_dataentry', 'as' => 'users.create_dataentry']);
    
    Route::get('users/editsimple/{id}', ['uses' => 'Admin\UsersController@editsimple', 'as' => 'users.editsimple']);
    Route::resource('users', 'Admin\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
   
    Route::get('designations/download_desig','Admin\DesignationsController@download_desig');

    Route::resource('designations', 'Admin\DesignationsController');
    Route::post('designations_mass_destroy', ['uses' => 'Admin\DesignationsController@massDestroy', 'as' => 'designations.mass_destroy']);

    
    
    Route::get('employees/clearold','Admin\EmployeesController@clearold');
    Route::get('employees/download_emp','Admin\EmployeesController@download_emp');
    Route::get('employees/findinvalidpen','Admin\EmployeesController@findinvalidpen');


    Route::get('employees/create_temppen',['uses' => 'Admin\EmployeesController@create_temppen', 'as' => 'employees.create_temppen']);

    Route::get('employees/sparksync',['uses' => 'Admin\EmployeesController@sparksync', 'as' => 'employees.sparksync']);
    Route::post('employees/sparksync',['uses' => 'Admin\EmployeesController@sparksync', 'as' => 'employees.sparksyncpost']);

    Route::resource('employees', 'Admin\EmployeesController');
   
   
    Route::get('employees/ajaxfind/{q}',array('as'=>'employees.ajax','uses'=>'Admin\EmployeesController@ajaxfind'));


    Route::post('employees/updatedesig', 'Admin\EmployeesController@updatedesig');


 // Route::get('employees/ajaxfindexactpenforattendace/{q}',array('as'=>'employees.ajaxexactforattendace','uses'=>'Admin\EmployeesController@ajaxfindexactpenforattendace'));

     Route::get('employees/ajaxfindexactpen/{q}',array('as'=>'employees.ajaxexact','uses'=>'Admin\EmployeesController@ajaxfindexactpen'));

    

    Route::post('employees_mass_destroy', ['uses' => 'Admin\EmployeesController@massDestroy', 'as' => 'employees.mass_destroy']);
    Route::resource('sessions', 'Admin\SessionsController');
    Route::post('sessions_mass_destroy', ['uses' => 'Admin\SessionsController@massDestroy', 'as' => 'sessions.mass_destroy']);
    Route::resource('calenders', 'Admin\CalendersController');
    Route::post('calenders_mass_destroy', ['uses' => 'Admin\CalendersController@massDestroy', 'as' => 'calenders.mass_destroy']);
    Route::resource('settings', 'Admin\SettingsController');
    Route::post('settings_mass_destroy', ['uses' => 'Admin\SettingsController@massDestroy', 'as' => 'settings.mass_destroy']);
    Route::resource('routings', 'Admin\RoutingsController');
    Route::post('routings_mass_destroy', ['uses' => 'Admin\RoutingsController@massDestroy', 'as' => 'routings.mass_destroy']);

    Route::get('forms/clearoldforms','Admin\FormsController@clearoldforms');

    Route::resource('forms', 'Admin\FormsController');
    Route::post('forms_mass_destroy', ['uses' => 'Admin\FormsController@massDestroy', 'as' => 'forms.mass_destroy']);
  
    Route::get('overtimes/fixx', 'Admin\OvertimesController@fixx');

    Route::resource('overtimes', 'Admin\OvertimesController');
    Route::post('overtimes_mass_destroy', ['uses' => 'Admin\OvertimesController@massDestroy', 'as' => 'overtimes.mass_destroy']);
    
    Route::post('my_forms/store_sitting', 'Admin\MyFormsController@store_sitting');
    Route::get('my_forms/create_sitting', ['uses' => 'Admin\MyFormsController@create_sitting', 'as' => 'my_forms.create_sitting'] );

    Route::get('my_forms/create_copy/{id}', ['uses' => 'Admin\MyFormsController@create_copy', 'as' => 'my_forms.create_copy'] );

    Route::put('my_forms/update_sitting/{id}', 'Admin\MyFormsController@update_sitting');

    Route::get('my_forms/getpdf', 'Admin\MyFormsController@getpdf' , ['uses' => 'Admin\MyFormsController@getpdf', 'as' => 'my_forms.getpdf']);
    Route::put('my_forms/forward/{id}', 'Admin\MyFormsController@forward');
    Route::put('my_forms/submittoaccounts/{id}', 'Admin\MyFormsController@submittoaccounts');
    Route::put('my_forms/sendback/{id}', 'Admin\MyFormsController@sendback');
     Route::put('my_forms/ignore/{id}', 'Admin\MyFormsController@ignore');
     Route::put('my_forms/sendonelevelback/{id}', 'Admin\MyFormsController@sendonelevelback');

    Route::resource('my_forms', 'Admin\MyFormsController');

    
    
    Route::resource('pa2mlaforms', 'Admin\PA2MLAFormsController');

   
    Route::get('searches_other/download','Admin\SearchesOtherController@download');
    Route::get('searches_other/download_desig','Admin\SearchesOtherController@download_desig');
    Route::get('searches_other/download_emp','Admin\SearchesOtherController@download_emp');
    Route::resource('searches_other', 'Admin\SearchesOtherController');

    Route::get('searches/download','Admin\SearchesController@download');
    Route::get('searches/download_calender','Admin\SearchesController@download_calender');
    Route::get('searches/download_desig','Admin\SearchesController@download_desig');
    Route::get('searches/download_emp','Admin\SearchesController@download_emp');
    Route::get('searches/download_user','Admin\SearchesController@download_user');
     
    Route::resource('searches', 'Admin\SearchesController');

    Route::resource('presets', 'Admin\PresetsController');

    Route::get('presets/ajaxfind/{q}',array('as'=>'presets.ajax','uses'=>'Admin\PresetsController@ajaxfind'));

    Route::post('presets_mass_destroy', ['uses' => 'Admin\PresetsController@massDestroy', 'as' => 'presets.mass_destroy']);

     Route::get('designations_others/download_desig','Admin\DesignationsOthersController@download_desig');
    Route::resource('designations_others', 'Admin\DesignationsOthersController');
    Route::post('designations_others_mass_destroy', ['uses' => 'Admin\DesignationsOthersController@massDestroy', 'as' => 'designations_others.mass_destroy']);


    Route::get('employees_others/clearold', 'Admin\EmployeesOthersController@clearold');
    
    Route::resource('employees_others', 'Admin\EmployeesOthersController');
    Route::post('employees_others_mass_destroy', ['uses' => 'Admin\EmployeesOthersController@massDestroy', 'as' => 'employees_others.mass_destroy']);

    Route::get('employees_others/ajaxfind/{q}',array('as'=>'employees_others.ajax','uses'=>'Admin\EmployeesOthersController@ajaxfind'));
    Route::get('employees_others/ajaxload/{q}',array('as'=>'employees_others.ajaxload','uses'=>'Admin\EmployeesOthersController@ajaxload'));

    ///////////////////////OD///////////////////////



    Route::resource('overtimesothers', 'Admin\OvertimesOthersController');
    Route::post('overtimesothers_mass_destroy', ['uses' => 'Admin\OvertimesOthersController@massDestroy', 'as' => 'overtimesothers.mass_destroy']);
    
    Route::get('my_forms_others/clearold', 'Admin\MyFormsOthersController@clearold');

    Route::get('my_forms_others/getpdf', 'Admin\MyFormsOthersController@getpdf' , ['uses' => 'Admin\MyFormsOthersController@getpdf', 'as' => 'my_forms_others.getpdf']);
    Route::post('my_forms_others/store_sitting', 'Admin\MyFormsOthersController@store_sitting');
    Route::get('my_forms_others/create_sitting', ['uses' => 'Admin\MyFormsOthersController@create_sitting', 'as' => 'my_forms_others.create_sitting'] );
    Route::put('my_forms_others/update_sitting/{id}', 'Admin\MyFormsOthersController@update_sitting');

    
    Route::put('my_forms_others/forward/{id}', 'Admin\MyFormsOthersController@forward');
    Route::put('my_forms_others/submittoaccounts/{id}', 'Admin\MyFormsOthersController@submittoaccounts');
    Route::put('my_forms_others/sendback/{id}', 'Admin\MyFormsOthersController@sendback');


    Route::resource('my_forms_others', 'Admin\MyFormsOthersController');


    Route::resource('forms_others', 'Admin\FormsOthersController');
    Route::post('forms_others_mass_destroy', ['uses' => 'Admin\FormsOthersController@massDestroy', 'as' => 'forms_others.mass_destroy']);
    Route::resource('overtimes_others', 'Admin\OvertimesOthersController');
    Route::post('overtimes_others_mass_destroy', ['uses' => 'Admin\OvertimesOthersController@massDestroy', 'as' => 'overtimes_others.mass_destroy']);

    
    Route::get('reports/detailed_report', ['uses' => 'Admin\ReportsController@detailed_report', 'as' => 'reports.detailed_report']);
    Route::resource('reports', 'Admin\ReportsController');
    Route::resource('reports_others', 'Admin\ReportsOthersController');



    // Backup routes
    Route::resource('backups',  'Admin\BackupController');
    Route::get('backup/create', 'Admin\BackupController@create');
    Route::get('backup/download/{file_name}', 'Admin\BackupController@download');
    Route::get('backup/delete/{file_name}', 'Admin\BackupController@delete');

   
    Route::get('attendances/ajaxfindexactpenforattendace/{q}',array('as'=>'attendances.ajaxexactforattendace','uses'=>'Admin\AttendancesController@ajaxfindexactpenforattendace'));
    
    Route::get('attendances/download','Admin\AttendancesController@download');

    Route::resource('attendances', 'Admin\AttendancesController');
    Route::post('attendances_mass_destroy', ['uses' => 'Admin\AttendancesController@massDestroy', 'as' => 'attendances.mass_destroy']);

    Route::resource('categories', 'Admin\CategoriesController');
    Route::post('categories_mass_destroy', ['uses' => 'Admin\CategoriesController@massDestroy', 'as' => 'categories.mass_destroy']);

    Route::get('punchings/ajaxgetpunchtimes/{date}/{pen}',array('as'=>'punchings.ajax','uses'=>'Admin\PunchingsController@ajaxgetpunchtimes'));

    Route::resource('punchings', 'Admin\PunchingsController');
   
    

    Route::post('csv_parse', 'Admin\CsvImportController@parse')->name('csv_parse');
    Route::post('csv_process', 'Admin\CsvImportController@process')->name('csv_process');
    Route::post('attendances/parse-csv-import', 'Admin\AttendancesController@parseCsvImportCustom')->name('attendances.parseCsvImportCustom');
    
});
