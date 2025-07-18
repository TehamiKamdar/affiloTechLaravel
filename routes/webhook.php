<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::group([
    'middleware' => ['webhook', 'throttle:60,1'],
    'prefix' => 'webhooks'
], function () {
    Route::post('/incoming', [WebhookController::class, 'actionIncomingWebhook']);
});
