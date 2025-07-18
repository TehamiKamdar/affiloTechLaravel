<?php

use App\Http\Controllers\DecryptController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doc\Website\ListController as WebsiteListController;
use App\Http\Controllers\Doc\Website\ShowController as WebsiteShowController;
use App\Http\Controllers\Doc\Advertiser\ListController as AdvertiserListController;
use App\Http\Controllers\Doc\Advertiser\ShowController as AdvertiserShowController;
use App\Http\Controllers\Doc\Offer\ListController as OfferListController;
use App\Http\Controllers\Doc\Offer\ShowController as OfferShowController;
use App\Http\Controllers\Doc\Transaction\ListController as TransactionListController;
use App\Http\Controllers\Doc\Generate\TrackingLinkController;
use App\Http\Controllers\Doc\Generate\DeeplinkController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/decrypt',[DecryptController::class,'e7061']);

Route::group(['middleware' => ['\App\Http\Middleware\DOCTokenVerification::class', 'throttle:10,1'], 'prefix' => 'v1'], function () {
    Route::get('websites', WebsiteListController::class);
    Route::get('websites/{id}', WebsiteShowController::class);
    Route::get('advertisers', AdvertiserListController::class);
    Route::get('advertisers/{id}', AdvertiserShowController::class);
    Route::get('offers', OfferListController::class);
    Route::get('offer/{id}', OfferShowController::class);
    Route::get('transactions', TransactionListController::class);
    Route::post('generate-link/{id}', TrackingLinkController::class);
    Route::post('generate-deep-link/{id}', DeeplinkController::class);
});