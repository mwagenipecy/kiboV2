<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SelcomSmsService
{
    public function send(string $phoneNumber, string $message): bool
    {
        $baseUrl = rtrim((string) config('services.selcom_sms.base_url'), '/');
        $username = (string) config('services.selcom_sms.username');
        $password = (string) config('services.selcom_sms.password');

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
            $response = Http::timeout(15)->get($baseUrl . '/bin/send.json', [
                'USERNAME' => $username,
                'PASSWORD' => $password,
                'DESTADDR' => $normalized,
                'MESSAGE' => $message,
            ]);

            if ($response->successful()) {
                Log::info('Selcom SMS sent', [
                    'to' => $normalized,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return true;
            }

            Log::error('Selcom SMS failed', [
                'to' => $normalized,
                'status' => $response->status(),
                'body' => $response->body(),
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

        if (str_starts_with($digits, '255')) {
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            return '255' . substr($digits, 1);
        }

        if (strlen($digits) === 9) {
            return '255' . $digits;
        }

        return $digits;
    }
}

