<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user is an admin
        if ($user->hasRole('admin')) {
            return $next($request);  // Allow admin to proceed to the dashboard
        }

        // Check if the user is a regional user and restrict access to the corresponding region
        if ($user->hasRole('regional')) {
            $region = $user->region;  // Assuming you have a region field in the users table
            if ($request->route('region') != $region) {
                return redirect()->route('dashboard');  // Redirect to the main dashboard if region doesn't match
            }
            return $next($request);  // Allow regional user to see their region-specific data
        }

        // If the user does not have a valid role, redirect to a default page
        return redirect()->route('home');
    }
}
