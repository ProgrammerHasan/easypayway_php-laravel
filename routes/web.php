<?php

Route::prefix('/easypayway')->group(static function () {
    Route::get('/', 'EasypaywayController@index')->name('easypayway.pay');
    Route::post('/success', 'EasypaywayController@success')->name('easypayway.success');
    Route::post('/fail', 'EasypaywayController@fail')->name('easypayway.fail');
    Route::post('/cancel', 'EasypaywayController@cancel')->name('easypayway.cancel');
    Route::post('/ipn', 'EasypaywayController@ipn')->name('easypayway.ipn');
});