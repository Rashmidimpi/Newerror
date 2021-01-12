
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestDetailController;

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


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);  
    Route::post('/sendOTP', [AuthController::class, 'sendOTP']);  
    Route::post('/sendOTPLogin',[AuthController::class, 'sendOTPlogin'] ); 
    Route::post('/verifyOTP', [AuthController::class, 'verifyOTP']); 
    Route::post('/createTest', [TestDetailController::class, 'create_quiz']);  
    // Route::post('/createTest', 'App\Http\Controllers\TestDetailController@create_quiz');
    Route::post('/addQuestion', [TestDetailController::class, 'addQuestion']);  
    Route::post('/gettestlist', [TestDetailController::class, 'getTestList']);  
    Route::post('/getquestion', [TestDetailController::class, 'getQuestionList']);  
    Route::get('/getTestDetails/{test_id}', [TestDetailController::class, 'getTestDetails']);  
});