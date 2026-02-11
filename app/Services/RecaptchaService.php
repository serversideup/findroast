<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    /**
     * Verify the reCAPTCHA response token.
     */
    public function verify(string $token, string $action = 'contact'): bool
    {
        // Skip if reCAPTCHA is disabled
        if (!config('recaptcha.enabled') || !config('recaptcha.secret_key')) {
            return true;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('recaptcha.secret_key'),
                'response' => $token,
            ]);

            $result = $response->json();

            // Check if verification was successful
            if (!$result['success']) {
                return false;
            }

            // Verify the action matches
            if (isset($result['action']) && $result['action'] !== $action) {
                return false;
            }

            // Check if score meets threshold
            $score = $result['score'] ?? 0;
            $threshold = config('recaptcha.threshold', 0.5);

            return $score >= $threshold;
        } catch (\Exception $e) {
            // Log the error but don't block the submission
            logger()->error('reCAPTCHA verification failed: ' . $e->getMessage());

            // Return true to not block users if reCAPTCHA service is down
            return true;
        }
    }
}
