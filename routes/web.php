<?php

use Illuminate\Support\Facades\Route;
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
/*Route::get('/linkstorage', function () { $targetFolder = base_path().'/storage/app/public'; $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/storage'; symlink($targetFolder, $linkFolder); });*/

// ---- ADMIN
//Route::domain('admin.jenflow-new.test')->group(function () {

    // login
    Route::get('/', [\App\Http\Controllers\Admin\AuthController::class,'login'])->name('login');
    Route::post('/authenticate', [\App\Http\Controllers\Admin\AuthController::class,'authenticate']);

    // Forgot password
    Route::get('forgot-password')->middleware('guest')->name('password.request');
    Route::post('forgot-password')->middleware('guest')->name('password.email');
    Route::get('reset-password/{token}')->middleware('guest')->name('password.reset');
    Route::post('reset-password')->middleware('guest')->name('password.update');

    // Auth Routes
    Route::middleware('auth')->group(function() {
        Route::get('logout', [\App\Http\Controllers\Admin\AuthController::class,'logout']);

        // File uploads
        Route::post('upload/{table?}/{id?}', [\App\Http\Controllers\Admin\UploadController::class, 'store']);
        Route::get('upload/delete/{id}', [\App\Http\Controllers\Admin\UploadController::class, 'destroy']);

        // Dashboards
        Route::get('dashboard', [\App\Http\Controllers\Admin\OverviewDashboardController::class,'index']);
        Route::get('salesdashboard', [\App\Http\Controllers\Admin\OverviewDashboardController::class,'index']);
        Route::get('productiondashboard', [\App\Http\Controllers\Admin\OverviewDashboardController::class,'index']);

        // Trainings
        Route::middleware('role:ignore,view-training-dates')->prefix('training-dates')->group(function() {
            Route::get('/', [\App\Http\Controllers\Admin\TrainingDateController::class,'index']);
            Route::post('/', [\App\Http\Controllers\Admin\TrainingDateController::class,'store']);
            Route::get('{id}', [\App\Http\Controllers\Admin\TrainingDateController::class,'show']);
            Route::put('{id}', [\App\Http\Controllers\Admin\TrainingDateController::class,'update']);
            Route::match(['get','delete'],'destroy/{id}', [\App\Http\Controllers\Admin\TrainingDateController::class,'destroy']);
            Route::post('stock-link',[\App\Http\Controllers\Admin\TrainingDateController::class,'storeStockLink']);
        });
        Route::get('delete-stock-link/{id}',[\App\Http\Controllers\Admin\TrainingDateController::class,'destroyStockLink']);
        Route::get('get-training-dates',[\App\Http\Controllers\Admin\TrainingDateController::class,'getTrainingDates']);

        // Sales orders
        Route::middleware('role:ignore,view-sale-orders')->resource('salesorders',\App\Http\Controllers\Admin\SalesOrderController::class);
        Route::post('store-sales-item',[\App\Http\Controllers\Admin\SalesOrderController::class,'storeSalesItem']);
        Route::match(['get','delete'],'destroy-sales-order-item/{id}', [\App\Http\Controllers\Admin\SalesOrderController::class, 'destroyOrderItem']);
        Route::post('load-items-for-dispatch',[\App\Http\Controllers\Admin\SalesOrderController::class, 'loadItemsForDispatch']);
        Route::get('salesorders/commercial-invoice/{id}',[\App\Http\Controllers\Admin\SalesOrderController::class,'commercialInvoice']);

        // Sales order dispatches
        Route::middleware('role:ignore,view-dispatches')->resource('dispatches', \App\Http\Controllers\Admin\SalesOrderDispatchesController::class);

        //  Expenses
        Route::middleware('role:ignore,view-sale-orders')->resource('expenses',\App\Http\Controllers\Admin\ExpenseController::class);
        Route::post('store-sales-item',[\App\Http\Controllers\Admin\ExpenseController::class,'storeSalesItem']);
        Route::match(['get','delete'],'destroy-sales-order-item/{id}', [\App\Http\Controllers\Admin\ExpenseController::class, 'destroyOrderItem']);
        Route::post('load-items-for-dispatch',[\App\Http\Controllers\Admin\ExpenseController::class, 'loadItemsForDispatch']);
        Route::get('salesorders/commercial-invoice/{id}',[\App\Http\Controllers\Admin\ExpenseController::class,'commercialInvoice']);
       
        Route::middleware('role:ignore,view-expenses')->resource('expense-types',\App\Http\Controllers\Admin\ExpenseTypeController::class);
        // Quotes
        Route::prefix('quote')->group(function() {
            Route::get('{id}', [\App\Http\Controllers\Admin\SalesOrderController::class, 'quote'])->where('id','[0-9]+');
            Route::get('{type}/{id}', [\App\Http\Controllers\Admin\SalesOrderController::class, 'quotePdf'])->where('type','print|download');
            Route::post('email', [\App\Http\Controllers\Admin\SalesOrderController::class, 'emailQuote']);
        });

        // Invoices
        Route::middleware('role:ignore,view-invoices')->resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class);
        Route::middleware('role:ignore,view-invoices')->prefix('invoices')->group(function() {
            Route::get('{type}/{id}', [\App\Http\Controllers\Admin\InvoiceController::class, 'pdf'])->where('type','print|download');
            Route::post('email', [\App\Http\Controllers\Admin\InvoiceController::class, 'email']);
            Route::get('detail/{id}',[\App\Http\Controllers\Admin\InvoiceController::class,'detail']);
            Route::post('manual-payment/{id}',[\App\Http\Controllers\Admin\InvoiceController::class,'manualPayment']);
        });

        // Vat Compliance
        Route::middleware('role:ignore,view-vat')->group(function() {
            Route::get('vat',[\App\Http\Controllers\Admin\VATComplianceController::class,'index']);
            Route::get('vat/testing/{value}',[\App\Http\Controllers\Admin\VATComplianceController::class,'setVatTesting']);
            Route::get('vat/settings',[\App\Http\Controllers\Admin\VATComplianceController::class,'settings']);
            Route::get('vat/create-access-token/{id}',[\App\Http\Controllers\Admin\VATComplianceController::class,'createAccessToken']); // use creds to create access token
            Route::get('vat/handle-response',[\App\Http\Controllers\Admin\VATComplianceController::class,'handleResponse']); // store access token

            // Get Obligations
            Route::post('vat/get-obligations',[\App\Http\Controllers\Admin\VATComplianceController::class,'getObligations']);

            // Tests
            Route::get('vat/test', [\App\Http\Controllers\Admin\VATComplianceController::class,'test']);
            Route::get('vat/hello-world', [\App\Http\Controllers\Admin\VATComplianceController::class,'helloWorld']);
            Route::get('vat/hello-world-user', [\App\Http\Controllers\Admin\VATComplianceController::class,'helloWorldUser']);
            Route::get('vat/hello-world-application',[\App\Http\Controllers\Admin\VATComplianceController::class,'helloWorldApplication']);
        });

        // Customers
        Route::middleware('role:ignore,view-customers')->resource('customers',\App\Http\Controllers\Admin\CustomerController::class);
        Route::match(['get', 'put'],'customer-status', [\App\Http\Controllers\Admin\CustomerController::class,'customerStatus']);
        Route::prefix('customers')->group(function() {
            Route::get('default-address/{customer_id}/{address_id}/{type?}',[\App\Http\Controllers\Admin\CustomerController::class,'defaultAddress']);
            Route::get('destroy-address/{address_id}',[\App\Http\Controllers\Admin\CustomerController::class,'destroyAddress']);
            Route::post('store-address',[\App\Http\Controllers\Admin\CustomerController::class,'storeAddress']);
            Route::get('legacy-orders',function() {
                return 'Legacy orders has not been implemented yet';
            });
            Route::get('addresses/{id}',[\App\Http\Controllers\Admin\CustomerController::class,'getAddresses']);
        });

        /**
         * When linking suppliers, remove supplier from dropdown is it already exists
         * like the product categories
         * maybe do for build products to but some instances may require the product more than once
         */
        // Products
        Route::middleware('role:ignore,view-products')->resource('products', \App\Http\Controllers\Admin\ProductController::class);
        Route::get('product-status', [\App\Http\Controllers\Admin\ProductController::class, 'productStatus']);
        Route::get('get-product', [\App\Http\Controllers\Admin\ProductController::class,'getProduct']); // get product via ajax
        Route::post('store-product-child', [\App\Http\Controllers\Admin\ProductController::class, 'storeProductChild']);
        Route::match(['get','delete'],'destroy-product-child/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyProductChild']);
        Route::get('products/unlink-category/{product_id?}/{category_id?}',[\App\Http\Controllers\Admin\ProductController::class,'unlinkCategory']);
        Route::post('products/create-stock/{id}',[\App\Http\Controllers\Admin\ProductController::class,'addStock']);

        // Product Attributes
        Route::middleware('role:ignore,view-product-attributes')->group(function(){
            Route::get('attributes', [\App\Http\Controllers\Admin\AttributeController::class,'index']);
            Route::post('attributes', [\App\Http\Controllers\Admin\AttributeController::class,'store']);
            Route::post('attributes/link-product', [\App\Http\Controllers\Admin\AttributeController::class,'linkProduct']);
            Route::get('attributes/unlink-product/{product_id}/{attribute_id}', [\App\Http\Controllers\Admin\AttributeController::class,'unlinkProduct']);
            Route::get('attributes/{id}',[\App\Http\Controllers\Admin\AttributeController::class,'show']);
            Route::match(['delete','get'],'attributes/delete/{id}',[\App\Http\Controllers\Admin\AttributeController::class,'destroy']);
            Route::put('attributes/{id}', [\App\Http\Controllers\Admin\AttributeController::class,'update']);
        });

        // Categories
       
        Route::middleware('role:ignore,view-categories')->resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::get('category-status', [\App\Http\Controllers\Admin\CategoryController::class, 'categoryStatus']);

        Route::post('categories/link',[\App\Http\Controllers\Admin\CategoryController::class,'linkCategory']);

        Route::post('/categories/{id}/child',[\App\Http\Controllers\Admin\CategoryController::class,'getChild']);

        Route::get('categories/unlink-category/{category_id?}',[\App\Http\Controllers\Admin\CategoryController::class,'unlinkCategory']);
        Route::post('categories/link-product',[\App\Http\Controllers\Admin\CategoryController::class,'linkProduct']);

        // Product Types
        Route::middleware('role:ignore,view-product-types')->resource('product-types',\App\Http\Controllers\Admin\ProductTypeController::class);

        // Production Orders
        Route::middleware('role:ignore,view-production-orders')->resource('productionorders',\App\Http\Controllers\Admin\ProductionOrderController::class);
        Route::post('store-build-item', [\App\Http\Controllers\Admin\ProductionOrderController::class, 'storeBuildItem']);
        Route::match(['get','delete'],'destroy-build-item/{id}', [\App\Http\Controllers\Admin\ProductionOrderController::class, 'destroyBuildItem']);
        Route::post('process-production-order/{id}',[\App\Http\Controllers\Admin\ProductionOrderController::class,'processProductionOrder']);

        // Purchase Orders
        Route::middleware('role:ignore,view-purchase-orders')->resource('purchases',\App\Http\Controllers\Admin\PurchaseOrderController::class);
        Route::middleware('role:ignore,view-purchase-orders')->prefix('purchases')->group(function() {
            Route::post('add-item',[\App\Http\Controllers\Admin\PurchaseOrderController::class,'storePurchaseItem']);
            Route::get('view-purchase-order/{id}',[\App\Http\Controllers\Admin\PurchaseOrderController::class,'purchaseOrder']);
            Route::post('email',[\App\Http\Controllers\Admin\PurchaseOrderController::class,'emailPurchaseOrder']);
            Route::get('{type}/{id}', [\App\Http\Controllers\Admin\PurchaseOrderController::class, 'pdf'])->where('type','print|download');
            // Sales order dispatches
            Route::resource('dispatches', \App\Http\Controllers\Admin\PurchaseOrderDispatchesController::class);
            Route::post('load-items-for-dispatch',[\App\Http\Controllers\Admin\PurchaseOrderController::class,'loadItemsForDispatch']);

        });

        // Refunds

        Route::middleware('role:ignore,view-refunds')->resource('refunds',\App\Http\Controllers\Admin\RefundController::class);
        Route::middleware('role:ignore,view-refunds')->prefix('refunds')->group(function() {
            Route::get('detail/{id}',[\App\Http\Controllers\Admin\RefundController::class,'detail']);
            Route::post('refund-items/{id}',[\App\Http\Controllers\Admin\RefundController::class,'refundItems']);
            Route::post('manual-payment/{id}',[\App\Http\Controllers\Admin\RefundController::class,'manualPayment']);
            Route::get('{type}/{id}', [\App\Http\Controllers\Admin\RefundController::class, 'pdf'])->where('type','print|download');
            Route::post('email', [\App\Http\Controllers\Admin\RefundController::class, 'email']);
        });

        // Suppliers
        Route::middleware('role:ignore,view-suppliers')->group(function() {
            Route::resource('suppliers', \App\Http\Controllers\Admin\SupplierController::class);
            Route::post('suppliers/addresses/{id}',[\App\Http\Controllers\Admin\SupplierController::class,'addresses']);
        });
        Route::get('supplier/products/{id}',[\App\Http\Controllers\Admin\SupplierController::class,'getProducts']);
        Route::post('store-supplier-ref', [\App\Http\Controllers\Admin\SupplierController::class, 'storeSupplierRef']);
        Route::match(['get','delete'],'destroy-supplier-ref/{id}', [\App\Http\Controllers\Admin\SupplierController::class, 'destroySupplierRef']);

        // Roles
        Route::middleware('role:ignore,create-roles')->resource('roles',\App\Http\Controllers\Admin\RoleController::class);

        // Users
        Route::resource('users',\App\Http\Controllers\Admin\UserController::class);
        Route::put('users/permissions/{id}', [\App\Http\Controllers\Admin\UserController::class,'permissions'])->where('id','[0-9]+');
        Route::get('calendar', [\App\Http\Controllers\Admin\UserController::class,'calendar']);

        // Fetch calendar
        Route::get('fetch-calendar/{id?}',[\App\Http\Controllers\Admin\HolidayController::class,'fetch']);
        Route::post('calendar',[\App\Http\Controllers\Admin\HolidayController::class,'store']);
        Route::put('calendar/{id}',[\App\Http\Controllers\Admin\HolidayController::class,'update'])->where('id','[0-9]+');
        Route::delete('calendar/{id}',[\App\Http\Controllers\Admin\HolidayController::class,'destroy'])->where('id','[0-9]+');
        Route::middleware('role:ignore,approve-holidays')->post('action-holiday',[\App\Http\Controllers\Admin\HolidayController::class,'actionHolidayRequests']);

        // Notifications
        Route::get('read-notifications/{id?}', [\App\Http\Controllers\Admin\NotificationController::class,'markAsRead']);
        Route::get('view-notification/{id}', [\App\Http\Controllers\Admin\NotificationController::class,'view']);

        Route::get('filemanager', function () {
           return view('admin.storage.file-manager');
        });

        Route::get('fetch-national-holidays', [\App\Http\Controllers\Admin\HolidayController::class,'fetchNationalHolidays']);

        // Settings
        Route::prefix('settings')->group(function() {
            Route::get('/',[\App\Http\Controllers\Admin\SettingController::class,'index']);
            Route::put('{id}',[\App\Http\Controllers\Admin\SettingController::class,'update']);
            Route::post('address',[\App\Http\Controllers\Admin\SettingController::class,'storeAddress']);
            Route::delete('address',[\App\Http\Controllers\Admin\SettingController::class,'destroyAddress']);
        });

        Route::get('refresh-token', function() {
            session()->regenerate();
            return response()->json([
                "token" => csrf_token()
            ], 200);
        });

        Route::middleware('role:super-admin')->get('test', function() {

            /*$countries = countries();

            foreach($countries as $key => $country)
            {
                echo "['title' => '{$country->title}', 'code' => '$country->code'],<br>";
            }*/

            /*dump($product->addMediaFromUrl($url)
                ->addCustomHeaders([
                    'ACL' => 'public-read'
                ])
                ->toMediaCollection('images'));*/

            /*dump(\Spatie\MediaLibrary\Models\Media::find(7)->delete());*/
            //$product = \App\Models\Product::with(['attributes'])->find(1141);

            //dd($product);

            /*$stripe = new \App\Repositories\Payments\PaymentGateway(new \App\Repositories\Payments\Providers\StripeGateway(env('STRIPE_TEST_SECRET')));
            //dd($stripe->refund('pi_1IgReWFudQQrPhjHQtsvAtpQ','13.00'));
            dd($stripe->retrieve('intent','pi_1Ipwn2FudQQrPhjHS6TD6i1w'));*/
            //\App\Events\PaymentReceived::dispatch(\App\Models\Invoice::find(1));

            dd(\App\Models\SalesOrderDispatch::find(1)->items[0]->orderedItem);
            //return view('admin.invoices.templates.commercial-invoice-pdf', ['detail' => \App\Models\Invoice::find(1)]);

        });
    });

//});

// Public pay invoice link
Route::get('invoices/pay/{hash}', [\App\Http\Controllers\Admin\InvoiceController::class,'pay']);
Route::post('invoices/pay/{hash}', [\App\Http\Controllers\Admin\InvoiceController::class,'processPayment']);
Route::get('invoices/payment-response/{payment_ref}', [\App\Http\Controllers\Admin\InvoiceController::class,'paymentResponse']);

/**
 * Fetch postcode
 */
Route::match(['get','post'],'fetch-postcode', function(\Illuminate\Http\Request $request, \App\Repositories\PostcodeFinder $postcodeFinder) {
    $validated = $request->validate([
       'postcode' => 'required'
    ]);

    $res = $postcodeFinder->getPostcode($validated['postcode']);
    return response()->json($res);
});

/**
 * Check a customers email doesnt already exist
 */
Route::get('check-customer-email',[\App\Http\Controllers\Admin\CustomerController::class, 'checkCustomerEmail']);

// Home routes
/*Route::get('/', function() {
   echo 'Welcome to the jenflow home view';
});*/



