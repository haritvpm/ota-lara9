<?php

Route::post('login', 'Api\\AuthController@login');

Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.', 'middleware' => ['auth:sanctum']], function () {



        Route::resource('designations', 'DesignationsController', ['except' => ['create', 'edit']]);

        Route::resource('employees', 'EmployeesController', ['except' => ['create', 'edit']]);

        Route::get('employees/find/{$q}', 'EmployeesController@find');

        Route::resource('sessions', 'SessionsController', ['except' => ['create', 'edit']]);

        Route::resource('calenders', 'CalendersController', ['except' => ['create', 'edit']]);

        Route::resource('settings', 'SettingsController', ['except' => ['create', 'edit']]);

        Route::resource('routings', 'RoutingsController', ['except' => ['create', 'edit']]);

        Route::resource('forms', 'FormsController', ['except' => ['create', 'edit']]);

        Route::resource('overtimes', 'OvertimesController', ['except' => ['create', 'edit']]);

});
