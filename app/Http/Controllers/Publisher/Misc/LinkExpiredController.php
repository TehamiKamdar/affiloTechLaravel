<?php
namespace App\Http\Controllers\Publisher\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LinkExpiredController extends Controller
{
    /**
     * Handle link expiration and return the appropriate view.
     */
    public function __invoke()
    {
        return view('publisher.advertisers.link-dead');
    }
}
