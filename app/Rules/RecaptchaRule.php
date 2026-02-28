<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class RecaptchaRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip validation if reCAPTCHA is disabled
        if (!config('services.recaptcha.enabled')) {
            return;
        }

        // Skip validation if secret key is not set (development environment)
        if (empty(config('services.recaptcha.secret_key'))) {
            return;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            // Check if the response was successful
            if (!$result['success']) {
                $fail('The reCAPTCHA verification failed. Please try again.');
                return;
            }

            // For reCAPTCHA v3, check the score
            if (isset($result['score']) && $result['score'] < config('services.recaptcha.threshold', 0.5)) {
                $fail('The reCAPTCHA verification failed. Please try again.');
                return;
            }
        } catch (\Exception $e) {
            // Log the error but don't fail validation to prevent blocking legitimate users
            // during temporary API outages
            \Log::error('reCAPTCHA verification error: ' . $e->getMessage());
        }
    }
}
