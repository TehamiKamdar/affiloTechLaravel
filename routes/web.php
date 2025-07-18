<?php
use App\Models\TrackingUrl;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\RedirectController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Publisher\Track\DeepLinkController;
use App\Http\Controllers\Publisher\Misc\LinkExpiredController;
use App\Http\Controllers\Publisher\Track\SimpleLinkController;
use App\Http\Controllers\Publisher\Track\TrackCouponLinkController;

Route::get("\x2f", function () { // Route: /
    return redirect()->route("\x67\x65\x74\x2d\163\x74\x61\x72\x74\x65\144"); // Route name: get-started
});
goto oC9lO;

lntiR:
require __DIR__ . "\57\x61\165\164\x68\x2e\160\x68\160"; // File: /auth.php
goto jDdZc;

E4yK5:
Route::get("\x2f\x74\x72\x61\143\153\x2f\x6c\157\156\x67", function ($short_url) { // Route: /track/long
    $trackingUrl = TrackingUrl::where("\x73\x68\x6f\x72\x74\137\165\x72\x6c", $short_url)->firstOrFail(); // Column: short_url
    $trackingUrl->increment("\157\160\145\x6e\x73"); // Column: opens
    return redirect()->to($trackingUrl->original_url);
})->name("\x74\x72\x61\x63\153\x2e\x6c\157\x6e\x67"); // Route name: track.long
goto xuWzu;

XPqWl:
Route::post("\x67\145\x74\x2d\x63\151\x74\x69\x65\163", array(AddressController::class, "\x61\x63\164\151\x6f\156\x43\x69\x74\x69\x65\x73"))->name("\x67\145\164\55\143\151\164\151\x65\x73"); // Route: get-cities
goto lntiR;

xuWzu:
Route::get("\57\x74\x65\x73\x74", array(\App\Http\Controllers\TestController::class, "\151\x6e\144\145\170")); // Route: /test | Controller Method: index
goto Dz5oe;

Dz5oe:
Route::post("\147\x65\164\55\163\x74\x61\164\65\x73", array(AddressController::class, "\x61\x63\164\x69\157\x6e\123\164\x61\164\x65\x73"))->name("\147\x65\x74\55\x73\x74\141\164\x65\163"); // Route: get-states
goto XPqWl;

jDdZc:
require __DIR__ . "\57\160\165\142\x6c\151\x73\150\x65\162\x2e\160\x68\x70"; // File: /publisher.php
goto U0vyY;

U0vyY:
require __DIR__ . "\57\x61\144\x6d\x69\x6e\56\160\x68\x70"; // File: /admin.php
goto z7uxF;

oC9lO:
Route::get("\57\x74\162\x61\x63\153\x2f\x7b\x73\150\157\162\x74\x5f\x75\162\154\175", function ($short_url) { // Route: /track/{short_url}
    $trackingUrl = TrackingUrl::where("\163\x68\157\x72\x74\x5f\165\x72\x6c", $short_url)->firstOrFail(); // Column: short_url
    $trackingUrl->increment("\157\x70\145\x6e\163"); // Column: opens
    return redirect()->to($trackingUrl->original_url);
})->name("\x74\162\141\x63\153"); // Route name: track
goto E4yK5;

z7uxF:
require __DIR__ . "\57\x77\x65\142\x68\157\157\x6b\x2e\x70\x68\x70"; // File: /webhook.php



Route::get('track', [SimpleLinkController::class, 'actionCodeTrackingLong'])->name("track.simple.long");
Route::get('track/{advertiser}/{website}/{coupon}', [TrackCouponLinkController::class, 'actionURLTracking'])->name("track.coupon");
Route::get('track/{advertiser}/{website}', [SimpleLinkController::class, 'actionURLTracking'])->name("track.simple");
Route::get('track/{tracking}', [SimpleLinkController::class, 'actionURLTrackingWithSubId'])->name("track.simple.sub-id");
Route::get('g/{code}', [SimpleLinkController::class, 'actionCodeTrackingWithSubId'])->name("track.simple.short");
Route::get('short/{code}', [SimpleLinkController::class, 'actionShortURLTracking'])->name("track.short");



Route::get('deeplink', [DeepLinkController::class, 'actionLongURLTracking'])->name("track.deeplink.long");
Route::get('deeplink/{code}', [DeepLinkController::class, 'actionURLTracking'])->name("track.deeplink");
Route::get('link-expired', LinkExpiredController::class)->name("link.expired");
Route::get('redirect', RedirectController::class)->name("redirect.url");
