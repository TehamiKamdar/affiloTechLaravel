<?php
namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PublisherMiddleware
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
        // Check if the user is authenticated and marked for forced logout
        if (Auth::check() && Auth::user()->force_logout) {
            User::where('id', Auth::user()->id)->update(['force_logout' => 0]);
            Auth::logout();
            return redirect('/get-started');
        }

        // Check if the user is a publisher
        if (Auth::check() && Auth::user()->is_publisher) {
            return $next($request);
        }

        // Redirect non-publishers to the publisher home
        return redirect('/publisher/home');
    }
}
