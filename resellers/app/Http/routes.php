<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('hashUser','Common\CartController@hashUser');

Route::get("sendOrder",'Common\EmailController@sendTestMail');

Route::get('/', [
    'uses' => 'HomeController@index',
    'as' => 'home'
]);

// Authentication routes...
Route::get('login', [
    'uses' => 'Auth\AuthController@getLogin',
    'as' => 'login'
]);
Route::post('login', 'Auth\AuthController@postLogin');

Route::get('logout', [
    'uses' => 'Auth\AuthController@getLogout',
    'as'   => 'logout'
]);

// Registration routes...
Route::get('register', [
    'uses'=>'Auth\AuthController@getRegister',
    'as'=>'register'
]);
Route::post('register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::group(['middleware' =>'auth'],function(){

    Route::get('account',function(){
        return view('account');
    });

    Route::get('usersettings',function(){
        return view('settings.user.user');
    });

    Route::post('usersettings/{type}','Settings\SettingsController@updateInfo');

    Route:: group(['middleware'=>'verified'], function(){
        Route::get('publish',function(){
            return view('publish');
        });

        Route::post('publish',function(){
            return Request::all();
        });
    });

    Route:: group(['middleware'=>'role:admin'], function(){
        Route::get('admin/settings',function(){
            return view('admin/settings');
        });

    });

    Route:: group(['middleware'=>'role:partner'], function(){

        /*
         * Dashboard routes
         */
         Route::get('/dashboard', [
             'uses' => 'Dashboard\DashboardController@index',
             'as' => 'home'
         ]);

        Route::get('salesAll','Dashboard\DashboardController@salesAll');

        Route::get('salesPaid','Dashboard\DashboardController@salesPaid');

        Route::get('salesPending','Dashboard\DashboardController@salesPending');

        Route::get('bc', [
            'uses' => 'OrderManagement\BillingCenterController@index',
            'as' => 'BillingCenter'
        ]);

        Route::get('getRecentOrders','Dashboard\DashboardController@recentOrders');

        Route::get('getTrackingInformation',"Common\OrderDetailController@getTrackingInformation");


        Route::get('bcSearch', [
            'uses' => 'OrderManagement\BillingCenterController@search',
            'as' => 'bcSearch'
        ]);

        Route::post('sendSpecialRequest', [
            'uses' => 'Common\EmailController@sendSpecialRequest',
            'as' => 'sendSpecialRequest'
        ]);

        Route::get('orderDetails/{orderNumber}/{from}', [
            'uses' => 'Common\OrderDetailController@index',
            'as' => 'orderDetails'
        ]);

        Route::get('omc', [
            'uses' => 'OrderManagement\OrderManagerController@index',
            'as' => 'OrderManager'
        ]);
        Route::get('omSearch', [
            'uses' => 'OrderManagement\OrderManagerController@search',
            'as' => 'omSearch'
        ]);
        Route::get('exportExcel', [
            'uses' => 'OrderManagement\OrderManagerController@exportExcel',
            'as' => 'exportExcel'
        ]);

        Route::get('cancelOrder/{OrderNumber}', [
            'uses' => 'Common\EmailController@cancelOrder',
            'as' => 'cancelOrder'
        ]);

        Route::post('attachFile', [
            'uses' => 'Common\UploadController@multipleUpload',
            'as' => 'attachFile'
        ]);

        Route::get('getImages/{sku}', [
            'uses' => 'Common\ImageController@index',
            'as' => 'getImages'
        ]);

        /*
         * rptv Customized Pricing
         */
        Route::get('rptv', [
            'uses' => 'Pricing\RptvCustPriceController@index',
            'as' => 'RptvCustPrice'
        ]);

        /*
         * RPTV Customized Pricing SEARCH
         */
        Route::get('rptvSearch', [
            'uses' => 'Pricing\RptvCustPriceController@search',
            'as' => 'rptvSearch'
        ]);

        /*
         * FP Customized Pricing
         */
        Route::get('fp', [
            'uses' => 'Pricing\FpCustPriceController@index',
            'as' => 'FpCustPrice'
        ]);

        /*
         * FP Customized Pricing SEARCH
         */
        Route::get('fpSearch', [
            'uses' => 'Pricing\FpCustPriceController@search',
            'as' => 'fpSearch'
        ]);

        /*
         * OTHER Customized Pricing
         */
        Route::get('other', [
            'uses' => 'Pricing\OtherCustPriceController@index',
            'as' => 'Other'
        ]);

        /*
         * Other Customized Pricing SEARCH
         */
        Route::get('otherSearch', [
            'uses' => 'Pricing\OtherCustPriceController@search',
            'as' => 'otherSearch'
        ]);


	/*
  	 * NEW Customized Pricing
   	*/
        Route::get('pricing', [
            'uses' => 'Pricing\NewPriceController@index',
            'as' => 'pricing'
        ]);

        /*
         * New Customized Pricing SEARCH
         */
        Route::get('newSearch', [
            'uses' => 'Pricing\NewPriceController@search',
            'as' => 'newSearch'
        ]);



        /*
         * CART
         */

         /*
          * Method to fill the table with the shipping method
          */
        Route::post('processOrder','Common\CartController@processOrder');

        //Method to load the shipping Information view
        Route::get('shippingInformation','Common\CartController@shippingInformation');

        //Method to send the order
        Route::post('sendOrder','Common\CartController@sendOrder');

        /*
          Method to get the cart
        */
        Route::get('getCart/{sku}', [
            'uses' => 'Common\CartController@index',
            'as' => 'getCart'
        ]);



        /*
         * Upload the shipping label for the case when the user choses to pay his own shipping label
         */
        Route::post('uploadShippingLabel','Common\CartController@uploadShippingLabel');

        /*
         * Validate SKU In Stock
         */
        Route::get('validateInStock', [
            'uses' => 'Common\CartController@validateInStock',
            'as' => 'validateInStock'
        ]);

        /*
         * Validates that the product isn't already on the Cart, if it is, it adds +1 to the quantity
         */
         Route::post('validateDuplicity',[
          'uses' => 'Common\CartController@validateDuplicity',
          'as' => 'validateDuplicity'
         ]);

         /*
          * Creates a web order if it doesnt exists
          */
          Route::post('CreateWebOrder',[
           'uses' => 'Common\CartController@createWebOrderId',
           'as' => 'CreateWebOrder'
          ]);

          /*
           * Save the order in the table
           */
           Route::post('saveOrder',[
             'uses' => 'Common\CartController@saveOrder',
             'as' => 'saveOrder'
           ]);

         /*
          * Route to delete the cart
          */
          Route::post('deleteCart','Common\CartController@deleteCart');

        /*
         *Route to get the cart
         */
         Route::get('getCartPreview','Common\CartController@getCartPreview');

       /*
        *Route to get the total items
        */
        Route::get('getCartCount','Common\CartController@getCartCount');

      /*
       *Route to delete the car
       */
       Route::get('deleteCartPreview','Common\CartController@deleteCart');

       /*
        *Route to the checkout
        */
        Route::get('checkout','Common\CartController@checkout');

        /*
         * Route to delete a single item from the cart
         */
        Route::post('deleteCartItem', 'Common\CartController@deleteSingleItem');

        /*
         * Route to change the shipping method
         */
        Route::post('changeShipping', 'Common\CartController@changeShipping');

        /*
         * Route to change quantity
         */
        Route::post('updateQuantity', 'Common\CartController@updateQuantity');


//-----------------------------------------------
        /*
        * Reports: Purchase Report By Sku
        */
        Route::get('PurchaseBySku', [
            'uses' => 'Reports\PurchaseBySkuController@index',
            'as' => 'PurchaseBySku'
        ]);

        /*
         * Reports: Purchase Report By SKu Search Data
         */
        Route::get('pBySkuSearch', [
            'uses' => 'Reports\PurchaseBySkuController@search',
            'as' => 'pBySkuSearch'
        ]);

        /*
         * Settings: SKU Mapping
         */
        Route::get('SkuMapping', [
            'uses' => 'Settings\SkuMappingController@index',
            'as' => 'SkuMapping'
        ]);
        /*
         * Settings: SKU Mapping Search Data
         */
        Route::get('skuMapSearch', [
            'uses' => 'Settings\SkuMappingController@search',
            'as' => 'skuMapSearch'
        ]);
        /*
        * Settings: SKU Mapping List
        */
        Route::get('getSkuList', [
            'uses' => 'Settings\SkuMappingController@getSkuList',
            'as' => 'getSkuList'
        ]);

        /*
         * Settings: SKU Mapping Edit Data
         */
        Route::post('skuMapEdit', [
            'uses' => 'Settings\SkuMappingController@edit',
            'as' => 'skuMapEdit'
        ]);


        /*
        * Settings: Carrier Mapping
        */
        Route::get('CarrierMapping', [
            'uses' => 'Settings\CarrierMappingController@index',
            'as' => 'CarrierMapping'
        ]);
        /*
         * Settings: Carrier Mapping Search Data
         */
        Route::get('carrierMapSearch', [
            'uses' => 'Settings\CarrierMappingController@search',
            'as' => 'carrierMapSearch'
        ]);
        /*
         * Settings: Carrier Mapping Edit Data
         */
        Route::post('carrierMapEdit', [
            'uses' => 'Settings\CarrierMappingController@edit',
            'as' => 'carrierMapEdit'
        ]);

        /*
        * OrderImport: Ship Station Import
        */
        Route::get('ShipImport', [
            'uses' => 'OrderImport\ShipStationImportController@index',
            'as' => 'ShipImport'
        ]);

       /*
       * OrderImport: Ship Station Import Save Data
       */
        Route::post('shipImportStore', [
            'uses' => 'OrderImport\ShipStationImportController@store',
            'as' => 'shipImportStore'
        ]);


        /*
        * OrderImport: Net Suite Import
        */
        Route::get('NetImport', [
            'uses' => 'OrderImport\NetSuiteImportController@index',
            'as' => 'NetImport'
        ]);
        /*
        * OrderImport: Net Suite Import Store
        */
        Route::post('NetImportStore', [
            'uses' => 'OrderImport\NetSuiteImportController@store',
            'as' => 'NetImportStore'
        ]);

	    Route::post('invoice',"Invoice\InvoiceController@generateInvoice");

    });



});
