<?php

// Sports Routes
Route::prefix('sports')->name('sports.')->group(function () {
    Route::get('/', 'SportController@index')->name('index');
    Route::post('store', 'SportController@store')->name('store');
    Route::get('{id}/edit', 'SportController@edit')->name('edit');
    Route::put('{id}/update', 'SportController@update')->name('update');
    Route::delete('{id}/destroy', 'SportController@destroy')->name('destroy');
});

// Cryptocurrency Routes
Route::prefix('cryptocurrencies')->name('cryptocurrencies.')->group(function () {
    Route::get('/', 'CryptocurrencyController@index')->name('index');
    Route::get('create', 'CryptocurrencyController@create')->name('create');
    Route::post('store', 'CryptocurrencyController@store')->name('store');
    Route::get('{id}/edit', 'CryptocurrencyController@edit')->name('edit');
    Route::put('{id}/update', 'CryptocurrencyController@update')->name('update');
    Route::post('update-prices', 'CryptocurrencyController@updatePrices')->name('update.prices');
    Route::delete('{id}/destroy', 'CryptocurrencyController@destroy')->name('destroy');
});

// Casino Games Routes
Route::prefix('casino/games')->name('casino.games.')->group(function () {
    Route::get('/', 'CasinoGameController@index')->name('index');
    Route::get('create', 'CasinoGameController@create')->name('create');
    Route::post('store', 'CasinoGameController@store')->name('store');
    Route::get('{id}/edit', 'CasinoGameController@edit')->name('edit');
    Route::put('{id}/update', 'CasinoGameController@update')->name('update');
    Route::delete('{id}/destroy', 'CasinoGameController@destroy')->name('destroy');
});
