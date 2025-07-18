<?php

namespace App\Services\Publisher\Misc;

use App\Helper\Static\Methods;
use App\Http\Requests\Publisher\Misc\UploadProfileImageRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AjaxRequestService
{
    /**
     * @param Request $request
     * @return string
     */
    public function getMonthLastDay(Request $request): string
    {
        return Carbon::parse("{$request->year}-{$request->month}-01")->format("t");
    }

    public function uploadProfileImage(UploadProfileImageRequest $request)
    {
        try
        {
            $user = auth()->user();
            $filename = time() . '.' . $request->fileUpload->extension();

            $request->fileUpload->move(public_path('images'), $filename);

            $image = 'images/' . $filename;

            $publisher = $user->publisher;
            $publisher->update([
                'image' => $image
            ]);

            $sectionName = "profile-picture";
            $section = $user->profile_complete_section ? $user->profile_complete_section : [];
            if(!in_array($sectionName, $section))
            {
                array_push($section, $sectionName);
                $user->update([
                    'profile_complete_per' => $user->profile_complete_per + 20,
                    'profile_complete_section' => $section
                ]);
            }

            // save uploaded image filename here to your database
            return response()->json([
                'message' => 'Image uploaded successfully.',
                'image' => Methods::staticAsset($image)
            ], 200);
        }
        catch (\Exception $exception)
        {
            // save uploaded image filename here to your database
            return response()->json([
                'message' => $exception->getMessage(),
                'image' => null
            ], 500);
        }
    }
}
