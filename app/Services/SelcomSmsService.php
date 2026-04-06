<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SelcomSmsService
{
    public function send(string $phoneNumber, string $message): bool
    {
        $baseUrl = rtrim((string) (config('services.selcom_sms.base_url') ?: 'https://gw.selcommobile.com:8443'), '/');
        $username = (string) (config('services.selcom_sms.username') ?: 'savannahills');
        $password = (string) (config('services.selcom_sms.password') ?: 'savannahills');

        $normalized = $this->normalizeTanzaniaNumber($phoneNumber);

        if (empty($baseUrl) || empty($username) || empty($password) || empty($normalized)) {
            Log::error('Selcom SMS configuration/number invalid', [
                'base_url_set' => ! empty($baseUrl),
                'username_set' => ! empty($username),
                'password_set' => ! empty($password),
                'phone_raw' => $phoneNumber,
                'normalized' => $normalized,
            ]);

            return false;
        }

        try {
            $response = Http::withoutVerifying()
                ->connectTimeout(5)
                ->timeout(10)
                ->get($baseUrl.'/bin/send.json', [
                    'USERNAME' => $username,
                    'PASSWORD' => $password,
                    'DESTADDR' => $normalized,
                    'MESSAGE' => $message,
                ]);

            if (! $response->successful()) {
                Log::error('Selcom SMS HTTP error', [
                    'to' => $normalized,
                    'http_status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            $data = $response->json();
            if (! is_array($data)) {
                Log::error('Selcom SMS response was not JSON', [
                    'to' => $normalized,
                    'body' => $response->body(),
                ]);

                return false;
            }

            $results = $data['results'] ?? null;
            $first = is_array($results) && isset($results[0]) && is_array($results[0])
                ? $results[0]
                : null;
            $smsStatus = $first['status'] ?? null;

            // Gateway may return status as JSON number (int) or string — strict === '0' misses int 0.
            if ((string) $smsStatus === '0') {
                Log::info('Selcom SMS sent', [
                    'to' => $normalized,
                    'msgid' => $first['msgid'] ?? null,
                    'balance' => $data['balance'] ?? null,
                ]);

                return true;
            }

            Log::error('Selcom SMS rejected by gateway', [
                'to' => $normalized,
                'status' => $smsStatus,
                'statustext' => $first['statustext'] ?? 'unknown',
                'response' => $data,
            ]);
        } catch (\Throwable $e) {
            Log::error('Selcom SMS exception', [
                'to' => $normalized,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }

    public function normalizeTanzaniaNumber(string $phoneNumber): string
    {
        $digits = preg_replace('/\D+/', '', $phoneNumber);
        if (! $digits) {
            return '';
        }

        // Handle +255 / 255 prefix (12 digits: 255 + 9-digit national number)
        if (str_starts_with($digits, '255')) {
            if (strlen($digits) === 12) {
                return $digits;
            }
            // Strip duplicate country code (e.g. 2552557xxxxxxxx mistyped)
            if (str_starts_with($digits, '255255') && strlen($digits) === 15) {
                return substr($digits, 3);
            }

            return '';
        }

        // Handle 0XXXXXXXXX (10 digits total)
        if (str_starts_with($digits, '0')) {
            $local = substr($digits, 1);

            return strlen($local) === 9 ? '255'.$local : '';
        }

        // Handle 9-digit local numbers (e.g. 758238772)
        if (strlen($digits) === 9) {
            return '255'.$digits;
        }

        return '';
    }
}
