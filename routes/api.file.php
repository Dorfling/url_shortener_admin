<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'api',
    ],
], function () {
    Route::get('/f/{file}', 'Api\FileController@getFile')
        ->name('public-file');
});
