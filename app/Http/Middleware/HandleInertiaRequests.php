<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Modules\Platform\Models\ChangelogEntry;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'app_url' => config('app.url'),
            'google_maps_api_key' => config('services.google.maps.key'),
            'recaptchaSiteKey' => config('services.recaptcha.site_key'),
            'recaptchaEnabled' => config('services.recaptcha.enabled'),
            'latest_changelog' => fn () => ChangelogEntry::published()
                ->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->first(['id', 'version', 'date', 'description']),
        ];
    }
}
