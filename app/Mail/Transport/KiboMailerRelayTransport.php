<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;

class KiboMailerRelayTransport extends AbstractTransport
{
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $baseUrl = (string) config('services.kibomailer.base_url');
        $apiKey = (string) config('services.kibomailer.api_key');
        $apiSecret = (string) config('services.kibomailer.api_secret');

        $to = array_map(
            static fn (Address $address): string => $address->getAddress(),
            $email->getTo()
        );

        $html = $email->getHtmlBody();
        $text = $email->getTextBody() ?? ($html ? trim(strip_tags($html)) : null);
        $subject = trim((string) $email->getSubject());

        if ($subject === '') {
            $contentForSubject = trim((string) ($text ?? ($html ? strip_tags($html) : '')));
            $subject = $contentForSubject !== ''
                ? Str::of($contentForSubject)->squish()->limit(80, '')->toString()
                : 'kiboauto email notification';
        }

        if ($baseUrl === '' || $apiKey === '' || $apiSecret === '') {
            Log::error('KiboMailer relay configuration missing required environment values', [
                'base_url_set' => $baseUrl !== '',
                'api_key_set' => $apiKey !== '',
                'api_secret_set' => $apiSecret !== '',
                'required_envs' => [
                    'KIBO_MAILER_BASE_URL',
                    'KIBO_MAILER_API_KEY',
                    'KIBO_MAILER_API_SECRET',
                ],
            ]);

            throw new \RuntimeException('KiboMailer relay configuration is incomplete. Set KIBO_MAILER_BASE_URL, KIBO_MAILER_API_KEY and KIBO_MAILER_API_SECRET.');
        }

        Log::info('Sending email via KiboMailer API', [
            'transport' => 'kibomailer_relay',
            'endpoint' => rtrim($baseUrl, '/').'/api/send',
            'to_count' => count($to),
            'to' => $to,
            'subject' => $subject,
            'api_key_prefix' => substr($apiKey, 0, 6),
        ]);

        try {
            $response = Http::baseUrl(rtrim($baseUrl, '/'))
                ->acceptJson()
                ->asJson()
                ->withHeaders([
                    'X-Api-Key' => $apiKey,
                    'X-Api-Secret' => $apiSecret,
                ])
                ->post('/api/send', [
                    'to' => $to,
                    'subject' => $subject,
                    'html' => $html,
                    'text' => $text,
                    'priority' => 'high',
                ]);
        } catch (\Throwable $e) {
            Log::error('KiboMailer API request failed before response', [
                'transport' => 'kibomailer_relay',
                'endpoint' => rtrim($baseUrl, '/').'/api/send',
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }

        $payload = $response->json();
        $isAccepted = $response->status() === 202
            && is_array($payload)
            && ($payload['status'] ?? null) === 'accepted'
            && !empty($payload['request_id'])
            && ($payload['priority'] ?? null) === 'high';

        Log::info('KiboMailer API response received', [
            'transport' => 'kibomailer_relay',
            'status_code' => $response->status(),
            'accepted' => $isAccepted,
            'request_id' => is_array($payload) ? ($payload['request_id'] ?? null) : null,
            'response_body' => is_array($payload) ? $payload : ['raw' => $response->body()],
        ]);

        if (!$isAccepted) {
            throw new \RuntimeException(
                'Email relay request was not accepted (expected HTTP 202 and accepted payload). Please request again or verify relay configuration.'
            );
        }
    }

    public function __toString(): string
    {
        return 'kibomailer-relay';
    }
}
