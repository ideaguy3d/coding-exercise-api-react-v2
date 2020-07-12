<?php
declare(strict_types=1);

use Illuminate\Http\Request;

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

Route::get('/file', function() {
    Log::info('_> rendering file upload view');
    return view('file-practice');
});

Route::post('files/people', 'UploadController@file');

Route::get('debug', 'FilesController@debug');

Route::get('projects', 'ProjectController@index');
Route::post('projects', 'ProjectController@store');