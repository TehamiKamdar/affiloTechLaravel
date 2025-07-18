<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Publisher\HomeController as PublisherHomeController;
use App\Http\Controllers\Publisher\AdvertiserController as PublisherAdvertiserController;
use App\Http\Controllers\Publisher\ReportController as PublisherReportController;
use App\Http\Controllers\Publisher\PromoteController as PublisherPromoteController;
use App\Http\Controllers\Publisher\FinanceController as PublisherFinanceController;
use App\Http\Controllers\Publisher\ToolController as PublisherToolController;
use App\Http\Controllers\Publisher\SettingController as PublisherSettingController;
use App\Http\Controllers\Publisher\APIInfoController;
use App\Http\Controllers\Publisher\TextLinkController;
use App\Http\Controllers\Publisher\DeepLinkController;

Route::group([
    "middleware" => ["auth", "verified", "publisher", "publisher.status"],
    "prefix" => "publisher",
    "as" => "publisher.",
], function () {
    Route::get('/dashboard', [PublisherHomeController::class, 'index'])->name('dashboard');
    Route::get('/advertiser-status/{publisher}', [PublisherHomeController::class, 'getAdvertiserStatus']);
    Route::get('/home', [PublisherHomeController::class, 'index'])->name('home');
    Route::post('/cart-data', [PublisherHomeController::class, 'sales_chart_data'])->name('sales_chart_data');
    Route::post('/clicks-data', [PublisherHomeController::class, 'clicks_data'])->name('clicks_data');
    Route::post('/region-graph', [PublisherHomeController::class, 'region_graph'])->name('sales.region.graph');
    Route::post('/advertiser-performance-graph', [PublisherHomeController::class, 'advertiserPerfomanceGraph_data'])->name('advertiser.performance.graph');
    Route::post('/overview-graph', [PublisherHomeController::class, 'overview_graph'])->name('overview.graph');

    Route::post('/complete-profile', [PublisherHomeController::class, 'completeProfile'])->name('complete.profile');

    Route::get('/my-advertisers', [PublisherAdvertiserController::class, 'getMyAdvertiser'])->name('my-advertisers');
    Route::get('/top-advertisers', [PublisherAdvertiserController::class, 'getTopAdvertiser'])->name('top-advertisers');
    Route::get('/new-advertisers', [PublisherAdvertiserController::class, 'getNewAdvertiser'])->name('new-advertisers');
    Route::get('/find-advertisers', [PublisherAdvertiserController::class, 'getFindAdvertiser'])->name('find-advertisers');
    Route::get('/view-advertiser/{advertiser}', [PublisherAdvertiserController::class, 'viewAdvertiser'])->name('view-advertiser');
    Route::post('/generate-export-advertiser', [PublisherAdvertiserController::class, 'generateExportAdvertiser'])->name('generate-export-advertiser');
    Route::post('/apply-advertiser', [PublisherAdvertiserController::class, 'applyAdvertiser'])->name('apply-advertiser');
    Route::get('/transactions', [PublisherReportController::class, 'getTransactions'])->name('transactions');
    Route::post('/generate-export-transactions', [PublisherReportController::class, 'generateExportTransactions'])->name('generate-export-transaction');
    Route::get('/advertiser-performance', [PublisherReportController::class, 'getAdvertiserPerformance'])->name('advertiser-performance');
    Route::get('/click-performances', [PublisherReportController::class, 'getClickPerformance'])->name('click-performance');
    Route::get('/daily-performance', [PublisherReportController::class, 'getDailyPerformance'])->name('daily-performance');
    Route::get('/coupons', [PublisherPromoteController::class, 'getCoupons'])->name('coupons');
    Route::get('/text-links', [TextLinkController::class, "actionTextLink"])->name('text-links');
    Route::get('/deep-links', [DeeplinkController::class, "actionDeeplink"])->name('deep-links');
    Route::get('/embed-links', [PublisherPromoteController::class, 'getEmbeddedLinks'])->name('embed-links');
    Route::get('/finance-overview', [PublisherFinanceController::class, 'getFinanceOverview'])->name('finance-overview');
    Route::get('/payments', [PublisherFinanceController::class, 'getPayments'])->name('payments');
    Route::get('/link-generator', [PublisherToolController::class, 'getLinkGenerator'])->name('link-generator');
    Route::get('/feeds', [PublisherToolController::class, 'getFeeds'])->name('feeds');
    Route::get('/api', [APIInfoController::class, 'actionApiInfo'])->name('api');
    Route::get('/download-export-files', [PublisherToolController::class, 'downloadExportFiles'])->name('download-export-files');

    Route::group([
        "prefix" => "profile",
        "as" => "profile."
    ], function () {
        Route::get('/basic-information', [PublisherSettingController::class, 'getBasicInformation'])->name('basic-information');
        Route::post('/basic-information-store', [PublisherSettingController::class, 'storeBasicInformation'])->name('basic-information.store');
        Route::get('/payment-billing', [PublisherSettingController::class, 'getPaymentNBilling'])->name('payment-billing');
        Route::post('/store-payment-billing', [PublisherSettingController::class, 'storePaymentNBilling'])->name('storepayments');
                Route::post('/store-payment', [PublisherSettingController::class, 'storePayment'])->name('storepayment');

        Route::get('/invoice', [PublisherSettingController::class, 'getInvoices'])->name('invoices');
        Route::get('/login-information/change-email', [PublisherSettingController::class, 'getLoginInformation'])->name('login-information.change-email');
        Route::get('/login-information/change-password', [PublisherSettingController::class, 'getLoginInformation'])->name('login-information.change-password');
        Route::post('/login-information/email-update', [PublisherSettingController::class, 'changeEmailUpdate'])->name('changes.email-update');
         Route::post('/login-information/password-update', [PublisherSettingController::class, 'changePasswordUpdate'])->name('changes.password-update');
        Route::post('/basic-information', [PublisherSettingController::class, 'storeBasicInformation'])->name('store-basic-information');
        Route::get('/metadata', [PublisherSettingController::class, 'getMetaData'])->name('metadata');
        Route::post('/metadata', [PublisherSettingController::class, 'storeMetaData'])->name('store-metadata');
        Route::get('/company-information', [PublisherSettingController::class, 'getCompanyInformation'])->name('company-information');
        Route::post('/company-information', [PublisherSettingController::class, 'storeCompanyInformation'])->name('company-information.store');
        Route::get('/communication-preferences', [PublisherSettingController::class, 'getCommunicationPreferences'])->name('communication-preferences');
        Route::post('/communication-preferences', [PublisherSettingController::class, 'storeCommunicationPreferences'])->name('store-communication-preferences');
        Route::get('/website', [PublisherSettingController::class, 'getWebsites'])->name('website');
        // Route::get('/website', [PublisherSettingController::class, 'getWebsites'])->name('websites.index');
        Route::post('/websites', [PublisherSettingController::class, 'updateWebsite'])->name('store-websites');
        Route::get('/referred-links', [PublisherSettingController::class, 'getReferredLinks'])->name('referred-links');
        Route::post('/referred-links', [PublisherSettingController::class, 'storeReferredLinks'])->name('store-referred-links');
// web.php
        Route::get('/get-states/{countryId}', [PublisherSettingController::class, 'getStates']);
        Route::get('/get-cities/{stateId}', [PublisherSettingController::class, 'getCities']);

    });

    Route::group([
        "prefix" => "deeplink",
        "as" => "deeplink."
    ], function () {
        Route::post("/check-availability", [DeeplinkController::class, "actionCheckAvailability"])->name("check-availability");
    });

    Route::group([
        "prefix" => "tracking",
        "as" => "tracking."
    ], function () {
        Route::post("/check-availability", [DeeplinkController::class, "actionTrackingURLCheckAvailability"])->name("check-availability");
    });

     Route::group(['prefix' => 'api-info', 'as' => 'api-info.'], function () {

            Route::get('/', [APIInfoController::class, 'actionApiInfo'])->name('index');
            Route::post('/regenerate-token', [APIInfoController::class, 'actionApiTokenRegenerate'])->name('regenerate-token');

        });
});
