<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//create a new route group


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//create new route for auth
Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});


// // create new api resource route for user
// Route::apiResource('/user', UserController::class)->middleware('auth:api');

// // create new api resource route for tasks
// Route::apiResource('/task', TaskController::class)->middleware('auth:api');
Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResources([
        '/user' => UserController::class,
        '/task' => TaskController::class,
        '/category' => CategoryController::class,
    ]);
});
