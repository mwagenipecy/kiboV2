<?php

namespace App\Services;

use App\Models\VehicleMake;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CarListingFromLinkParser
{
    /**
     * Fetch a listing URL and try to infer year, make, and model from page title / Open Graph metadata.
     *
     * @return array{year: ?int, vehicle_make_id: ?int, vehicle_model_id: ?int, title: ?string, error: ?string}
     */
    public function extract(string $url): array
    {
        $base = [
            'year' => null,
            'vehicle_make_id' => null,
            'vehicle_model_id' => null,
            'title' => null,
            'error' => null,
        ];

        if (! $this->isSafePublicHttpUrl($url)) {
            return array_merge($base, ['error' => 'Only public http(s) links are allowed.']);
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; KiboAutoListingParser/1.0; +https://kiboauto.com)',
                    'Accept' => 'text/html,application/xhtml+xml',
                ])
                ->get($url);

            if (! $response->successful()) {
                return array_merge($base, ['error' => 'Could not load that page. Please enter make, model, and year manually.']);
            }

            $html = $response->body();
            $title = $this->extractTitleFromHtml($html);

            if ($title === null || trim($title) === '') {
                return array_merge($base, ['error' => 'No title found on the page. Please select make, model, and year manually.']);
            }

            $year = $this->extractYear($title);

            $make = $this->matchMake($title);
            if (! $make) {
                return array_merge($base, ['title' => $title, 'year' => $year, 'error' => null]);
            }

            $model = $this->matchModel($title, $make, $year);

            return [
                'year' => $year,
                'vehicle_make_id' => $make->id,
                'vehicle_model_id' => $model?->id,
                'title' => $title,
                'error' => null,
            ];
        } catch (\Throwable $e) {
            \Log::warning('CarListingFromLinkParser failed', ['url' => $url, 'message' => $e->getMessage()]);

            return array_merge($base, ['error' => 'Unable to read that link. Please enter details manually.']);
        }
    }

    private function isSafePublicHttpUrl(string $url): bool
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $parts = parse_url($url);
        $scheme = strtolower($parts['scheme'] ?? '');
        if (! in_array($scheme, ['http', 'https'], true)) {
            return false;
        }

        $host = strtolower($parts['host'] ?? '');
        if ($host === '' || $host === 'localhost') {
            return false;
        }

        if (Str::endsWith($host, '.local') || Str::endsWith($host, '.localhost')) {
            return false;
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            if (! filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return false;
            }
        }

        return true;
    }

    private function extractTitleFromHtml(string $html): ?string
    {
        $patterns = [
            '/<meta\s[^>]*property=["\']og:title["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i',
            '/<meta\s[^>]*content=["\']([^"\']+)["\'][^>]*property=["\']og:title["\'][^>]*>/i',
            '/<meta\s[^>]*name=["\']twitter:title["\'][^>]*content=["\']([^"\']+)["\'][^>]*>/i',
            '/<meta\s[^>]*content=["\']([^"\']+)["\'][^>]*name=["\']twitter:title["\'][^>]*>/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $m)) {
                return trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            }
        }

        if (preg_match('/<title[^>]*>([^<]+)<\/title>/is', $html, $m)) {
            return trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        return null;
    }

    private function extractYear(string $title): ?int
    {
        if (preg_match('/\b(19[89]\d|20[0-9]{2})\b/', $title, $m)) {
            $y = (int) $m[1];
            if ($y >= 1990 && $y <= (int) date('Y') + 1) {
                return $y;
            }
        }

        return null;
    }

    private function matchMake(string $title): ?VehicleMake
    {
        $haystack = ' '.mb_strtolower($title).' ';

        $makes = VehicleMake::query()
            ->where('status', 'active')
            ->get()
            ->sortByDesc(fn (VehicleMake $m) => mb_strlen($m->name));

        foreach ($makes as $make) {
            $needle = ' '.mb_strtolower($make->name).' ';
            if (str_contains($haystack, $needle)) {
                return $make;
            }
        }

        return null;
    }

    private function matchModel(string $title, VehicleMake $make, ?int $year): ?VehicleModel
    {
        $remainder = $title;
        if ($year !== null) {
            $remainder = preg_replace('/\b'.$year.'\b/', ' ', $remainder) ?? $remainder;
        }
        $remainder = str_ireplace($make->name, ' ', $remainder);
        $remainder = preg_replace('/[|–—\-,:]+/', ' ', $remainder) ?? $remainder;
        $remainder = mb_strtolower(trim(preg_replace('/\s+/', ' ', $remainder) ?? ''));

        if ($remainder === '') {
            return null;
        }

        $models = VehicleModel::query()
            ->where('vehicle_make_id', $make->id)
            ->where('status', 'active')
            ->get()
            ->sortByDesc(fn (VehicleModel $m) => mb_strlen($m->name));

        foreach ($models as $model) {
            if (str_contains($remainder, mb_strtolower($model->name))) {
                return $model;
            }
        }

        return null;
    }
}
