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

Route::prefix(env('APP_PREFIX', ''))->group(function () {
    Route::get('/', function () {
        $version = 'Unknown version.';
        $versionFile = __DIR__ . '/../versionInfo.inf';
        if (file_exists($versionFile)) {
            $version = fgets(fopen($versionFile, 'r'));
        }
        return view('welcome', ['version' => $version, 'title' => env('APP_NAME', 'Microservice')]);
    })->name('home');
});
