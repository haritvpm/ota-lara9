<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::group([
        'middleware' => 'api',
        'prefix' => 'auth'
    ], function ($router) {
        Route::post('login', [AuthController::class,'login']);
        Route::get('me', [AuthController::class,'me']);
      //  Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
     //   Route::post('me', [AuthController::class, 'me']);

    });


Route::group(['namespace' => 'Api\V1', 'as' => 'api.', 'middleware' => ['auth:api']], function () {

 

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
