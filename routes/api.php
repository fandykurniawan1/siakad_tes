<?php

Route::post('image/destroy', 'ImageController@destroy')->name('image.destroy');
Route::get('master/province/{province}/city', 'LocationController@getCity');
