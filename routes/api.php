<?php

use Illuminate\Http\Request;

Route::group([ 'prefix' => 'auth' ], function () {
    Route::post('login', 'API\AuthController@login')->name('login');
  
    Route::group([ 'middleware' => 'auth:api' ], function() {
        Route::get('logout', 'API\AuthController@logout');
        Route::get('user', 'API\AuthController@user');
        Route::post('password', 'API\AuthController@changePassword');
        Route::put('profile', 'API\AuthController@updateProfile');
    });
});


Route::group([ 'middleware' => 'auth:api' ], function() {

    Route::get('customers/all', 'API\CustomerController@all');
    Route::apiResource('customers', 'API\CustomerController');
    
    Route::get('car-mades/all', 'API\CarMadeController@all');
    Route::apiResource('car-mades', 'API\CarMadeController');

    Route::apiResource('cars', 'API\CarController');

    Route::apiResource('expenses', 'API\ExpenseController');

    Route::get('expense-types/all', 'API\ExpenseTypeController@all');
    Route::apiResource('expense-types', 'API\ExpenseTypeController');

    
    Route::get('categories/all', 'API\CategoryController@all');
    Route::apiResource('categories', 'API\CategoryController');
    
    Route::apiResource('sub-categories', 'API\SubCategoryController');
    
    
    //Products
    Route::get('/products/search', 'API\ProductController@search');
    Route::get('products/{product}/purchases', 'API\ProductController@getPurchases');
    Route::post('products/{product}/purchases', 'API\ProductController@purchase');
    Route::apiResource('products', 'API\ProductController');
    
    Route::apiResource("product-purchases", "API\ProductPurchaseController");

    //Sales
    Route::get('sales/status', 'API\SaleController@getStatus');
    Route::put('sales/{sale}/status', 'API\SaleController@changeStatus');
    
    Route::post('sales/{sale}/customers', 'API\SaleController@changeCustomer');
    Route::delete('sales/{sale}/customers','API\SaleController@removeCustomer');
    
    Route::delete('sales/{sale}/products/{sale_product}', 'API\SaleController@removeProduct');
    Route::post('sales/{sale}/products', 'API\SaleController@addProduct');
    
    Route::post('sales/{sale}/external-products', 'API\SaleController@addExternalProduct');
    Route::delete('sales/{sale}/external-products/{external_product}', 'API\SaleController@removeExternalProduct');
    
    Route::get('sales/{sale}/expenses', 'API\SaleController@getExpenses');
    Route::post('sales/{sale}/expenses', 'API\SaleController@addExpense');
    Route::delete('sales/{sale}/expenses/{expense}', 'API\SaleController@removeExpense');

    Route::get('sales/{sale}/exports/invoice', 'API\SaleController@exportInvoice');
    Route::delete('sales/{sale}/services/{sale_service}', 'API\SaleController@removeService');
    Route::post('sales/{sale}/services', 'API\SaleController@addService');

    Route::post('sales/{sale}/payments', 'API\SaleController@addPayment');
    Route::delete('sales/{sale}/payments/{payment}', 'API\SaleController@removePayment');

    Route::put('sales/{sale}/make-closed', 'API\SaleController@makeClosed');
    Route::put('sales/{sale}/make-open', 'API\SaleController@makeOpen');

    Route::apiResource('sales', 'API\SaleController');

    //Setting
    Route::get('settings', 'API\SettingController@index');
    Route::put('settings', 'API\SettingController@update');

    Route::apiResource('services', 'API\ServiceController');

    Route::apiResource('suppliers', 'API\SupplierController');

    Route::put('users/{user}/password', 'API\UserController@passwordReset');
    Route::apiResource('users', 'API\UserController');
});