<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and email is verified
        if (Auth::check() && Auth::user()->email_verified_at) {
            return $next($request);
        }

        // Redirect unverified users to the email verification page with an error message
       return redirect()->route('verification.notice')->with(
            'error',
            'You need to verify your email to access this page.'
        );
    }
}
