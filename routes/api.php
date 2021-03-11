<?php

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

Route::resource('/mentors', 'MentorController');
Route::resource('/courses', 'CoursesController');
Route::resource('/chapters', 'ChaptersController');
Route::resource('/lessons', 'LessonsController');
Route::resource('/image-course', 'ImageCourseController');
Route::resource('/my-courses', 'MyCourseController');
Route::post('/my-courses/premium', 'MyCourseController@makePremium');
Route::resource('/reviews', 'ReviewController');
