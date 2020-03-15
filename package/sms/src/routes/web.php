<?php

Route::group(['namespace'=>'Felix\Sms\Http\Controllers'], function(){
    Route::get('/admini', 'Index@index');
});