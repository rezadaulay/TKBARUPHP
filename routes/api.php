<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(['prefix' => 'secure/api', 'middleware' => 'auth:api'], function() {
    Route::post('user/set_settings', 'StoreController@applySettings')->name('api.user.apply_settings');
    Route::post('so/bf', 'SalesOrderController@broughtForwardPayment')->name('api.so.bf');
});

Route::get('po/code', function () {
    return \App\Util\POCodeGenerator::generateCode();
})->name('api.po.code');

Route::get('so/code', function () {
    return \App\Util\SOCodeGenerator::generateCode();
})->name('api.so.code');

Route::group(['prefix' => 'warehouse'], function () {
    Route::group(['prefix' => 'outflow'], function () {
        Route::get('so/{id?}', 'WarehouseOutflowController@getWarehouseSOs')->name('api.warehouse.outflow.so');
    });
    Route::group(['prefix' => 'inflow'], function () {
        Route::get('po/{id?}', 'WarehouseInflowController@getWarehousePOs')->name('api.warehouse.inflow.po');
    });
    Route::group(['prefix' => 'stock_opname'], function () {
        Route::get('last', 'WarehouseController@getLastStockOpname')->name('api.warehouse.stock_opname.last');
    });
});

Route::group(['prefix' => 'bank'], function () {
    Route::group(['prefix' => 'upload'], function () {
        Route::get('last', 'BankController@getLastBankUpload')->name('api.bank.upload.last');
    });
});

Route::group(['prefix' => 'giro'], function () {
    Route::get('due_giro', 'GiroController@getDueGiro')->name('api.giro.due_giro');
});

Route::group(['prefix' => 'customer'], function () {
    Route::get('search_customer', 'CustomerController@searchCustomers')->name('api.customer.search');
    Route::get('passive_customer', 'CustomerController@getPassiveCustomer')->name('api.customer.passive_customer');
});

Route::group(['prefix' => 'phone_provider'], function() {
    Route::get('search/{param?}', 'PhoneProviderController@getPhoneProviderByDigit')->name('api.phone_provider.search');
});

Route::group(['prefix' => 'purchase_order'], function() {
    Route::get('due_purchase_order', 'PurchaseOrderController@getDuePO')->name('api.purchase_order.due_purchase_order');
    Route::get('unreceived_purchase_order', 'PurchaseOrderController@getUnreceivedPO')->name('api.purchase_order.unreceived_purchase_order');
});

Route::group(['prefix' => 'sales_order'], function() {
    Route::get('due_sales_order', 'SalesOrderController@getDueSO')->name('api.sales_order.due_sales_order');
    Route::get('undelivered_sales_order', 'SalesOrderController@getUndeliveredSO')->name('api.sales_order.undelivered_sales_order');
    Route::get('number_of_created_sales_order_per_day', 'SalesOrderController@getNumberOfCreatedSOPerDay')->name('api.sales_order.number_of_created_sales_order_per_day');
    Route::get('total_sales_order_amount_per_day', 'SalesOrderController@getTotalSOAmountPerDay')->name('api.sales_order.total_sales_order_amount_per_day');
});

Route::group(['prefix' => 'stock'], function() {
    Route::get('current_stocks/{wId?}', 'StockController@getCurrentStocks')->name('api.stock.current_stocks');
});

Route::get('user/get/calendar', 'CalendarController@retrieveEvents')->name('api.user.get.calendar');

Route::get('get/unfinish/store', 'StoreController@isUnfinishedStoreExist')->name('api.get.unfinish.store');

Route::get('get/unfinish/warehouse', 'WarehouseController@isUnfinishedWarehouseExist')->name('api.get.unfinish.warehouse');

Route::get('currencies/conversion', 'CurrenciesController@conversion')->name('api.currencies.conversion');