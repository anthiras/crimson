<?php

use App\Domain\RoleId;
use Illuminate\Http\Request;
use App\Domain\CourseId;
use App\Domain\UserId;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| V1 Routes
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

Route::get('/v1/courses', 'V1\CourseController@index');
Route::get('/v1/courses/{courseId}', 'V1\CourseController@show')->middleware('requireauth');
Route::post('/v1/courses', 'V1\CourseController@store')->middleware('requireauth');
Route::put('/v1/courses/{courseId}', 'V1\CourseController@update')->middleware('requireauth');
Route::delete('/v1/courses/{courseId}', 'V1\CourseController@destroy')->middleware('requireauth');

Route::post('/v1/courses/{courseId}/signUp', 'V1\CourseParticipantController@signUp')->middleware('requireauth');
Route::post('/v1/courses/{courseId}/cancelSignUp', 'V1\CourseParticipantController@cancel')->middleware('requireauth');

Route::get('/v1/users', 'V1\UserController@index')->middleware('requireauth');;
Route::get('/v1/users/current', 'V1\UserController@current')->middleware('requireauth');
Route::get('/v1/users/current/permissions', 'V1\PermissionController@current')->middleware('requireauth');
Route::get('/v1/users/{userId}', 'V1\UserController@show')->middleware('requireauth');
Route::put('/v1/users/{userId}', 'V1\UserController@update')->middleware('requireauth');
Route::post('/v1/users/{userId}/roles/{roleId}', 'V1\UserRoleController@addRole')->middleware('requireauth');
Route::delete('/v1/users/{userId}/roles/{roleId}', 'V1\UserRoleController@removeRole')->middleware('requireauth');

Route::get('/v1/membership/current', 'V1\MembershipController@current')->middleware('requireauth');
Route::post('/v1/membership/{userId}/setPaid', 'V1\MembershipController@setPaid')->middleware('requireauth');
Route::post('/v1/membership', 'V1\MembershipController@store')->middleware('requireauth');

Route::get('/v1/roles', 'V1\RoleController@index');

Route::post('/v1/auth0user', 'V1\Auth0UserController@store');
