# Guide: Adding a New Language

This guide explains how to add a new language to the Kibo Auto application.

## Step 1: Create Language Directory

Create a new directory in `lang/` with your language code (e.g., `fr` for French, `de` for German, `ar` for Arabic):

```bash
mkdir lang/fr
```

Common language codes:
- `fr` - French
- `de` - German  
- `es` - Spanish
- `ar` - Arabic
- `pt` - Portuguese
- `zh` - Chinese
- `ja` - Japanese
- etc.

## Step 2: Copy and Translate Translation Files

Copy all translation files from `lang/en/` to your new language directory, then translate them.

### Required Files:

1. **`lang/fr/auth.php`** - Authentication strings
   - Copy from `lang/en/auth.php`
   - Translate all strings

2. **`lang/fr/common.php`** - Common UI elements
   - Copy from `lang/en/common.php`
   - Translate all strings

3. **`lang/fr/vehicles.php`** - Vehicle-related strings
   - Copy from `lang/en/vehicles.php`
   - Translate all strings

4. **`lang/fr/validation.php`** - Validation messages
   - Copy from `lang/en/validation.php`
   - Translate all strings

## Step 3: Update Configuration

Edit `config/app.php` and add your language code to the `available_locales` array:

```php
'available_locales' => ['en', 'sw', 'fr'],  // Add 'fr' (or your language code)
```

## Step 4: Update Language Helper (Optional)

If you want the language name to display in the language switcher, edit `app/Helpers/LanguageHelper.php`:

```php
public static function getLanguageNames(): array
{
    return [
        'en' => __('common.english'),
        'sw' => __('common.swahili'),
        'fr' => 'Français',  // Add your language name
    ];
}
```

**Note:** For this to work properly, you may need to add language name translations to `common.php` or hardcode the name.

## Step 5: Add Language Name to Translations (Recommended)

Add the language name to `lang/fr/common.php` (and other language files):

In `lang/en/common.php`:
```php
'french' => 'French',
```

In `lang/sw/common.php`:
```php
'french' => 'Kifaransa',
```

In `lang/fr/common.php`:
```php
'french' => 'Français',
```

Then update `LanguageHelper.php` to use translations:
```php
'fr' => __('common.french'),
```

## Step 6: Test Your Language

1. Clear cache: `php artisan config:clear`
2. Clear cache: `php artisan cache:clear`
3. Visit your site and click the language switcher
4. Your new language should appear in the dropdown
5. Select it and verify all translations display correctly

## File Structure Summary

```
lang/
├── en/                    # English (existing)
│   ├── auth.php
│   ├── common.php
│   ├── vehicles.php
│   └── validation.php
├── sw/                    # Swahili (existing)
│   ├── auth.php
│   ├── common.php
│   ├── vehicles.php
│   └── validation.php
└── fr/                    # Your new language
    ├── auth.php           # ← Create and translate
    ├── common.php         # ← Create and translate
    ├── vehicles.php       # ← Create and translate
    └── validation.php     # ← Create and translate
```

## Quick Start Example (French)

```bash
# 1. Create directory
mkdir lang/fr

# 2. Copy files
cp lang/en/auth.php lang/fr/auth.php
cp lang/en/common.php lang/fr/common.php
cp lang/en/vehicles.php lang/fr/vehicles.php
cp lang/en/validation.php lang/fr/validation.php

# 3. Edit config/app.php - add 'fr' to available_locales
# 4. Translate all strings in lang/fr/*.php files
# 5. Clear cache: php artisan config:clear && php artisan cache:clear
```

## Important Notes

- Always keep the same array keys in all language files
- Use the same file structure for all languages
- Test thoroughly after adding a new language
- Consider right-to-left (RTL) languages - you may need additional CSS/styling for Arabic, Hebrew, etc.
- The default fallback language is English (`en`) - if a translation is missing, it will show the English version

## Need Help?

- Check existing translations in `lang/en/` and `lang/sw/` for reference
- Use translation tools or services for accurate translations
- Test all pages after adding translations to ensure everything displays correctly

