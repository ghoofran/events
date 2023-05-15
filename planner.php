<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;






Route::post('planner/register',[AuthController::class, 'PlannerRegister']);
Route::post('planner/login',[AuthController::class, 'PlannerLogin']);
Route::group( ['prefix' => 'planner','middleware' => ['auth:planner-api','scopes:planner']],function(){
    Route::post('logout',[AuthController::class, 'PlannerLogout']);
});




