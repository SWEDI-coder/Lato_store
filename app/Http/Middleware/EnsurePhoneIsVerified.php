<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsurePhoneIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        // Check if user is authenticated and has not verified their phone
        if ($user && !$user->hasVerifiedPhone()) {
            // Store intended URL for later
            if (!$request->is('verify-phone*')) {
                session(['url.intended' => $request->url()]);
            }
            
            // Store phone for verification
            session(['phone_for_verification' => $user->phone]);
            
            // Redirect to phone verification
            return redirect()->route('phone.verify');
        }

        return $next($request);
    }
}
