<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageHelper
{
    /**
     * Get all available languages
     *
     * @return array
     */
    public static function getAvailableLanguages(): array
    {
        return config('app.available_locales', ['en', 'sw']);
    }
    
    /**
     * Get language display names
     *
     * @return array
     */
    public static function getLanguageNames(): array
    {
        return [
            'en' => __('common.english'),
            'sw' => __('common.swahili'),
        ];
    }
    
    /**
     * Get current locale
     *
     * @return string
     */
    public static function getCurrentLocale(): string
    {
        return App::getLocale();
    }
    
    /**
     * Set locale
     *
     * @param string $locale
     * @return void
     */
    public static function setLocale(string $locale): void
    {
        if (in_array($locale, self::getAvailableLanguages())) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        }
    }
    
    /**
     * Check if locale is available
     *
     * @param string $locale
     * @return bool
     */
    public static function isLocaleAvailable(string $locale): bool
    {
        return in_array($locale, self::getAvailableLanguages());
    }
    
    /**
     * Get locale display name
     *
     * @param string|null $locale
     * @return string
     */
    public static function getLocaleName(?string $locale = null): string
    {
        $locale = $locale ?? self::getCurrentLocale();
        $names = self::getLanguageNames();
        
        return $names[$locale] ?? $locale;
    }
}

