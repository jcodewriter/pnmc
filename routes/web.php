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

Route::get('/', 'IndexController@index')->name('home');
Route::get('/market-cap', 'IndexController@marketCap')->name('market-cap');
Route::get('/market-cap/get', 'IndexController@getMarketCapData')->name('market-cap-ajax');
Route::get('/graph-data', 'AssetController@getUpdatedMarketCapGraphData');
Route::get('/daily-volume', 'IndexController@dailyVolume')->name('daily-volume');
Route::get('/daily-volume/get', 'IndexController@getDailyVolumeData')->name('daily-volume-ajax');
Route::get('/rich-list', 'IndexController@getRichList')->name('rich-list');
Route::get('/asset/{tickerSymbol}', 'AssetController@index')->name('asset');
Route::get('/asset/{tickerSymbol}/get-history', 'AssetController@getAssetHistoryData')->name('asset-history-ajax');
Route::get('/asset/{tickerSymbol}/price-trend', 'AssetController@showFullPriceGraph')->name('asset-price-trend');
Route::get('/asset/{tickerSymbol}/exchange-price-trend', 'AssetController@showFullExchangePriceGraph')->name('asset-exchange-price-trend');
Route::get('/asset/{tickerSymbol}/graph-data', 'AssetController@getUpdatedGraphData');
Route::get('/asset/{tickerSymbol}/volume', 'AssetController@showFullVolumeGraph')->name('asset-volume');
Route::get('/asset/{tickerSymbol}/supply', 'AssetController@showFullSupplyGraph')->name('asset-supply');
Route::get('/asset/{tickerSymbol}/rich-list', 'AssetController@getAssetRichList')->name('asset-rich-list');

Auth::routes(['register' => false]);

Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/admin/users', 'Admin\UserController@index')->name('admin-users');
Route::get('/admin/assets', 'Admin\AssetController@index')->name('admin-assets');
Route::get('/admin/translations', 'Admin\TranslationController@index')->name('admin-translations');
