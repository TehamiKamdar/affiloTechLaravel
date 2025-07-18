<?php
use App\Http\Controllers\Admin\CreativeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\ShowOnController;
use App\Http\Controllers\Admin\DuplicateController;

use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PublisherController as AdminPublisherController;
use App\Http\Controllers\Admin\AdvertiserController as AdminAdvertiserController;
use App\Http\Controllers\Admin\ApprovalController as AdminApprovalController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;

Route::group([
    'middleware' => ['auth', 'super_admin_n_admin'],
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {

    // Dashboard route
    Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('dashboard');

    // Route to Load Bar Chart Data
    Route::get('/chart-data', [AdminHomeController::class, 'getData'])->name('chart.data');


    //Load Links and Emails Graph Data
    Route::get('/tracking-link-chart', [AdminHomeController::class, 'getTrackingLinkStats']);
    Route::get('/deeplink-chart', [AdminHomeController::class, 'getDeepLinkStats']);
    Route::get('/email-chart', [AdminHomeController::class, 'getEmailApproveStats']);

    // Publishers Routes
    Route::prefix('publishers')->as('publishers.')->group(function () {
        Route::get('/{status}', [AdminPublisherController::class, 'index'])
            ->where('status', 'pending|hold|active|rejected')
            ->name('status');

        Route::get('/ajax', [AdminPublisherController::class, 'ajax'])->name('ajax');
        Route::post('/delete-all', [AdminPublisherController::class, 'deleteAllUsers'])->name('delete-all-selected');

        Route::prefix('view/{publisher}')->group(function () {
            Route::get('/', [AdminPublisherController::class, 'view'])->name('view');
            Route::get('/details', [AdminPublisherController::class, 'viewDetails'])->name('view.details');
            Route::get('/media-kits', [AdminPublisherController::class, 'viewMediakits'])->name('view.mediakits');
            Route::get('/websites', [AdminPublisherController::class, 'viewWebsites'])->name('view.websites');
            Route::get('/billing-info', [AdminPublisherController::class, 'viewBillingInfo'])->name('view.billing-info');
            Route::get('/payment-info', [AdminPublisherController::class, 'viewPaymentInfo'])->name('view.payment-info');
            Route::get('/lock-unlock/network-by-advertiser', [AdminPublisherController::class, 'viewLockUnlockNetworkByAdvertiser'])->name('view.lock-unlock.network-by-advertiser');
            Route::get('/lock-unlock/network-by-advertiser/ajax', [AdminPublisherController::class, 'viewLockUnlockNetworkByAdvertiser'])->name('view.lock-unlock.network-by-advertiser.ajax');
            Route::post('/lock-unlock/network-by-advertiser', [AdminPublisherController::class, 'storeLockUnlockNetworkByAdvertiser'])->name('view.lock-unlock.network-by-advertiser.store');
        });

        Route::get('/edit/{publisher}', [AdminPublisherController::class, 'edit'])->name('edit');
        Route::put('/update/{publisher}', [AdminPublisherController::class, 'update'])->name('update');
        Route::delete('/delete/{publisher}', [AdminPublisherController::class, 'delete'])->name('delete');

        Route::get('/website/status/{website}/{status}', [AdminPublisherController::class, 'websiteStatusUpdate'])->name('website.statusUpdate');
        Route::get('/status/{publisher}/{status}', [AdminPublisherController::class, 'publisherStatusUpdate'])->name('statusUpdate');

        Route::get('/access-login/{publisher}', [AdminPublisherController::class, 'accessLogin']);
    });

    // Advertisers Routes
    Route::prefix('advertisers')->as('advertisers.')->group(function () {

        Route::prefix('/api')->group(function () {
            Route::get('/', [AdminAdvertiserController::class, 'getApiAdvertiser'])->name('api');

            Route::as('api.')->group(function () {
                 Route::group(['prefix' => 'show-on-publisher', 'as' => 'show_on_publisher.'], function () {

                Route::get('/', [ShowOnController::class, 'index'])->name('index');
                Route::post('/', [ShowOnController::class, 'store'])->name('store');

                Route::post("/get-countries-by-network", [ShowOnController::class, 'getCountriesByNetwork'])->name('get-countries-by-network');
                Route::post("/get-advertisers-by-network", [ShowOnController::class, 'getAdvertisersByNetwork'])->name('get-advertisers-by-network');
                 Route::get('/duplicate-records', [DuplicateController::class, 'index'])->name('duplicate_record');
            Route::post('/duplicate-records', [DuplicateController::class, 'store'])->name('duplicate_record.store');

            });
                Route::get('/ajax', [AdminAdvertiserController::class, 'ajax'])->name('ajax');
                Route::get('/view/{advertiser}', [AdminAdvertiserController::class, 'view'])->name('view');
                Route::get('/edit/{advertiser}', [AdminAdvertiserController::class, 'edit'])->name('edit');
                Route::post('/update/{advertiser}', [AdminAdvertiserController::class, 'update'])->name('update');
                Route::get('/view/commission-rates/{advertiser}', [AdminAdvertiserController::class, 'viewCommissionRates'])->name('view.commission-rates');
                Route::get('/view/view-terms/{advertiser}', [AdminAdvertiserController::class, 'viewTerms'])->name('view.terms');
            });
        });

        // Advertiser Approval Routes
        Route::prefix('approval')->as('approval.')->group(function () {
            Route::get('/{status}', [AdminApprovalController::class, 'index'])
                ->where('status', 'pending|hold|joined|rejected')
                ->name('status');

            Route::get('/ajax', [AdminApprovalController::class, 'ajax'])->name('ajax');
            Route::post('/status-update', [AdminApprovalController::class, 'updateStatus'])->name('status-update');
        });
    });

    // Transactions Routes
    Route::prefix('transactions')->as('transactions.')->group(function () {
        Route::get('/', [AdminTransactionController::class, 'index'])->name('index');
        Route::get('/ajax', [AdminTransactionController::class, 'ajax'])->name('ajax');
        Route::get('/transaction/{transaction}', [AdminTransactionController::class, 'view'])->name('view');

    });

    Route::prefix('users')->as('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/ajax', [AdminUserController::class, 'ajax'])->name('ajax');
        Route::get('/user/{user}', [AdminUserController::class, 'view'])->name('view');
        Route::get('/user/status/active/{user}', [AdminUserController::class, 'status_active'])->name('status.active');
        Route::get('/user/status/pending/{user}', [AdminUserController::class, 'status_pending'])->name('status.pending');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/store', [AdminUserController::class, 'store'])->name('stores');
        Route::post('/destroy/{user}', [AdminUserController::class, 'destroy'])->name('delete');

    });
    Route::prefix('roles')->as('roles.')->group(function () {
    Route::get('/ajax',[RoleController::class, 'ajax'])->name('roleajax');
    Route::get('/view/{role}',[RoleController::class, 'show'])->name('view');
    Route::post('/updates',[RoleController::class, 'update'])->name('updates');
    Route::post('/delete/{role}',[RoleController::class, 'destroy'])->name('delete');
});

Route::prefix('permissions')->as('permissions.')->group(function () {
    Route::get('/',[PermissionController::class, 'index'])->name('index');
    Route::get('/ajax',[PermissionController::class, 'ajax'])->name('permissionajax');
    Route::get('/view/{permission}',[PermissionController::class, 'show'])->name('view');
    Route::post('/updates',[PermissionController::class, 'update'])->name('updates');
    Route::post('/delete/{permission}',[PermissionController::class, 'destroy'])->name('delete');
});

Route::prefix('creatives')->as('creatives.')->group(function () {
    Route::get('/coupons',[CreativeController::class, 'index'])->name('index');
    Route::get('/ajax',[CreativeController::class, 'ajax'])->name('creativeajax');
    Route::get('/coupons/view/{coupon}',[CreativeController::class, 'view'])->name('view');
});
    // Resource Routes for Roles and Users
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);


    Route::prefix('settings')->as('settings.')->group(function () {
        Route::get('/default-commission',[SettingController::class, 'default_commission'])->name('default-commission');
        Route::post('/default-commission-store',[SettingController::class,'default_commission_store'])->name('default-commission-store');
        Route::get('/notification',[SettingController::class, 'notification'])->name('notification');
        Route::post('/notification-store',[SettingController::class,'notification_store'])->name('notification-store');
    });
});
