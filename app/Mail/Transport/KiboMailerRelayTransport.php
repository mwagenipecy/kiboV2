<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
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

        $response = Http::baseUrl(rtrim((string) config('services.kibomailer.base_url'), '/'))
            ->acceptJson()
            ->asJson()
            ->withHeaders([
                'X-Api-Key' => (string) config('services.kibomailer.api_key'),
                'X-Api-Secret' => (string) config('services.kibomailer.api_secret'),
            ])
            ->post('/api/send', [
                'to' => $to,
                'subject' => $subject,
                'html' => $html,
                'text' => $text,
                'priority' => 'high',
            ]);

        $payload = $response->json();
        $isAccepted = $response->status() === 202
            && is_array($payload)
            && ($payload['status'] ?? null) === 'accepted'
            && !empty($payload['request_id'])
            && ($payload['priority'] ?? null) === 'high';

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
