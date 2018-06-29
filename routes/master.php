<?php

Route::resource('/product-category', 'ProductCategoryController', ['except' => ['show']]);

Route::resource('/brand', 'BrandController', ['except' => ['create', 'show']]);
Route::post('/brand/data', 'BrandController@getData')->name('brand.data');