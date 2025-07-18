<?php
namespace App\Http\Controllers\Publisher\Misc;

use App\Http\Controllers\Controller;
use App\Http\Requests\Publisher\Misc\UploadProfileImageRequest;
use App\Models\Website;
use App\Services\Publisher\Misc\AjaxRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AjaxRequestController extends Controller
{
    protected object $service;

    public function __construct(AjaxRequestService $service)
    {
        $this->service = $service;
    }

    /**
     * Get the last day of a given month.
     */
    public function actionGetMonthLastDay(Request $request)
    {
        return $this->service->getMonthLastDay($request);
    }

    /**
     * Handle profile image upload.
     */
    public function actionUploadProfileImage(UploadProfileImageRequest $request)
    {
        return $this->service->uploadProfileImage($request);
    }

    /**
     * Set pagination limit in session.
     */
    public function actionSetPaginationLimit(Request $request)
    {
        session()->put("publisher_{$request->type}_limit", $request->limit);
        return response()->json(true);
    }

    /**
     * Set advertiser view preference in session.
     */
    public function actionSetAdvertiserView(Request $request)
    {
        session()->put("publisher_advertiser_view", $request->view);
        return response()->json(true);
    }

    /**
     * Set active website for the user and redirect to dashboard.
     */
     public function actionSetWebsite(Request $request, Website $website)
    {
        $user = $request->user();
        $user->update([
            'active_website_id' => $website->id
        ]);

        return redirect()
            ->route('dashboard', ['type' => 'publisher'])
            ->with('success', 'Website successfully changed.');
    }
}