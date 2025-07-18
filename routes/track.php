1<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Publisher\Track\SimpleLinkController;
use App\Http\Controllers\Publisher\Track\TrackCouponLinkController;
use App\Http\Controllers\Publisher\Track\DeepLinkController;
use App\Http\Controllers\Publisher\Misc\LinkExpiredController;
use App\Http\Controllers\RedirectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group that
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('track', [SimpleLinkController::class, 'actionCodeTrackingLong'])->name("track.simple.long");
Route::get('track/{advertiser}/{website}/{coupon}', [TrackCouponLinkController::class, 'actionURLTracking'])->name("track.coupon");
Route::get('track/{advertiser}/{website}', [SimpleLinkController::class, 'actionURLTracking'])->name("track.simple");
Route::get('track/{tracking}', [SimpleLinkController::class, 'actionURLTrackingWithSubId'])->name("track.simple.sub-id");
Route::get('g/{code}', [SimpleLinkController::class, 'actionCodeTrackingWithSubId'])->name("track.simple.short");


Route::get('deeplink', [DeepLinkController::class, 'actionLongURLTracking'])->name("track.deeplink.long");
Route::get('deeplink/{code}', [DeepLinkController::class, 'actionURLTracking'])->name("track.deeplink");
Route::get('link-expired', LinkExpiredController::class)->name("link.expired");
Route::get('redirect', RedirectController::class)->name("redirect.url");