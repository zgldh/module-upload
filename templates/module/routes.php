<?php

Route::post('upload/bundle','\WoXuanWang\Upload\Controllers\UploadController@bundle');
Route::resource('upload', '\WoXuanWang\Upload\Controllers\UploadController');
