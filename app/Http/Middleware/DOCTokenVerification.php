<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class DOCTokenVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('token');
        $user = User::where("api_token", $header)->count();
        if($user) {
            return $next($request);
        }
        return response()->json(['status' => 404, 'success' => false, 'message' => 'Unauthorized Access'], 404);
    }
}
