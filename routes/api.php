<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix(env('APP_PREFIX', ''))->group(function () {
    Route::post(
        '/authentications',
        [
            'uses' => 'AuthenticationsController@create'
        ]
    );
});
