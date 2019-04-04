<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;

Route::get('/mailable', function() {
    $userId = new \App\Domain\UserId("ceb424e0-f626-11e8-b9ab-af6570e99efd");
    $userRepo = new \App\Persistence\DbUserRepository();
    $user = $userRepo->user($userId);
    $courseRepo = new \App\Persistence\DbCourseRepository();
    $course = $courseRepo->course(new \App\Domain\CourseId('bc0399c0-2003-11e9-bcc6-8f81603b4e07'));
    $participant = $course->getParticipant($userId);
    return new \App\Mail\CourseParticipantSignedUp($course, $participant, $user);
});

Route::get('/{path}', function () {
    return view('main');
})->where('path', '.*');
