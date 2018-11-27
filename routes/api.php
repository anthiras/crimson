<?php

use App\Domain\RoleId;
use Illuminate\Http\Request;
use App\Domain\CourseId;
use App\Domain\UserId;
use Illuminate\Support\Facades\Log;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::bind('courseId', function($value) { return new CourseId($value); });
Route::bind('userId', function($value) { return new UserId($value); });
Route::bind('roleId', function($value) { return new RoleId($value); });

Route::get('/courses', 'API\CourseController@index');
Route::get('/courses/{courseId}', 'API\CourseController@show')->middleware('requireauth');
Route::post('/courses', 'API\CourseController@store')->middleware('requireauth');
Route::put('/courses/{courseId}', 'API\CourseController@update')->middleware('requireauth');
Route::delete('/courses/{courseId}', 'API\CourseController@destroy')->middleware('requireauth');

Route::post('/courses/{courseId}/signUp', 'API\CourseParticipantController@signUp')->middleware('requireauth');
Route::post('/courses/{courseId}/cancelSignUp', 'API\CourseParticipantController@cancel')->middleware('requireauth');

Route::get('/users', 'API\UserController@index')->middleware('requireauth');;
Route::get('/users/current', 'API\UserController@current')->middleware('requireauth');
Route::get('/users/current/permissions', 'API\PermissionController@current')->middleware('requireauth');
Route::get('/users/{userId}', 'API\UserController@show')->middleware('requireauth');
Route::put('/users/{userId}', 'API\UserController@update')->middleware('requireauth');
Route::post('/users/{userId}/roles/{roleId}', 'API\UserRoleController@addRole')->middleware('requireauth');
Route::delete('/users/{userId}/roles/{roleId}', 'API\UserRoleController@removeRole')->middleware('requireauth');

Route::get('/membership/current', 'API\MembershipController@current')->middleware('requireauth');
Route::post('/membership/{userId}/setPaid', 'API\MembershipController@setPaid')->middleware('requireauth');
Route::post('/membership', 'API\MembershipController@store')->middleware('requireauth');

Route::get('/roles', 'API\RoleController@index');

Route::post('/auth0user', 'API\Auth0UserController@store');
