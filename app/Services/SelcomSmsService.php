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
                'base_url_set' => !empty($baseUrl),
                'username_set' => !empty($username),
                'password_set' => !empty($password),
                'phone' => $phoneNumber,
                'normalized' => $normalized,
            ]);
            return false;
        }

        try {
            $response = Http::withoutVerifying()
                ->withOptions(['allow_redirects' => true])
                ->timeout(15)
                ->get($baseUrl . '/bin/send.json', [
                    'USERNAME' => $username,
                    'PASSWORD' => $password,
                    'DESTADDR' => $normalized,
                    'MESSAGE' => $message,
                ]);

            if (!$response->successful()) {
                Log::error('Selcom SMS HTTP error', [
                    'to' => $normalized,
                    'http_status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            $data = $response->json();
            $smsStatus = $data['results'][0]['status'] ?? null;

            if ($smsStatus === '0') {
                Log::info('Selcom SMS sent', [
                    'to' => $normalized,
                    'msgid' => $data['results'][0]['msgid'] ?? null,
                    'balance' => $data['balance'] ?? null,
                ]);
                return true;
            }

            Log::error('Selcom SMS rejected by gateway', [
                'to' => $normalized,
                'status' => $smsStatus,
                'statustext' => $data['results'][0]['statustext'] ?? 'unknown',
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

    private function normalizeTanzaniaNumber(string $phoneNumber): string
    {
        $digits = preg_replace('/\D+/', '', $phoneNumber);
        if (!$digits) {
            return '';
        }

        // Handle +255XXXXXXXXX or 255XXXXXXXXX
        if (str_starts_with($digits, '255')) {
            return strlen($digits) === 12 ? $digits : '';
        }

        // Handle 0XXXXXXXXX
        if (str_starts_with($digits, '0')) {
            $local = substr($digits, 1);
            return strlen($local) === 9 ? '255' . $local : '';
        }

        // Handle 9-digit local numbers (e.g. 758238772)
        if (strlen($digits) === 9) {
            return '255' . $digits;
        }

        // Reject unsupported formats explicitly
        return '';
    }
}

