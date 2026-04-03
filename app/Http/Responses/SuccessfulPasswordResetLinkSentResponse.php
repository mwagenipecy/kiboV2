<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse;
use Symfony\Component\HttpFoundation\Response;

class SuccessfulPasswordResetLinkSentResponse implements SuccessfulPasswordResetLinkRequestResponse
{
    public function __construct(
        public string $status
    ) {}

    public function toResponse($request): Response
    {
        $message = __('We have emailed your password reset link. If we have your phone number on file, we also sent the reset link by SMS.');

        return redirect()->route('cars.index')
            ->with('status', $message)
            ->with('showForgotPassword', true);
    }
}
