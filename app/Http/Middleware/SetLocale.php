<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get available locales from config
        $availableLocales = config('app.available_locales', ['en', 'sw']);
        
        // Check if locale is set in session
        $locale = Session::get('locale');
        
        // If not in session, try to get from request
        if (!$locale && $request->has('lang')) {
            $locale = $request->get('lang');
        }
        
        // If still not set, try to detect from browser
        if (!$locale) {
            $locale = $request->getPreferredLanguage($availableLocales);
        }
        
        // Fallback to default locale
        if (!$locale || !in_array($locale, $availableLocales)) {
            $locale = config('app.locale', 'en');
        }
        
        // Set the locale
        App::setLocale($locale);
        Session::put('locale', $locale);
        
        return $next($request);
    }
}

