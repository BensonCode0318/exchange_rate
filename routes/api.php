<?php

use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers')->group(function () {
    // 轉換匯率
    Route::post('/exchange_rate', 'ExchangeRateController@postExchangeRate');
});
