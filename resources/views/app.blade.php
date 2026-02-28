<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- SEO Meta Tags -->
        <meta name="description" content="Discover specialty coffee roasts from artisan roasters. Check real-time availability and find your next favorite coffee across hundreds of roasters.">
        <meta name="keywords" content="specialty coffee, coffee roasters, artisan coffee, coffee beans, find coffee, coffee availability">

        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="FindRoast - Discover Specialty Coffee">
        <meta property="og:description" content="Discover specialty coffee roasts from artisan roasters. Check real-time availability and find your next favorite coffee across hundreds of roasters.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ config('app.url') }}">
        <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
        <meta property="og:site_name" content="FindRoast">
        <meta property="og:locale" content="en_US">

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="FindRoast - Discover Specialty Coffee">
        <meta name="twitter:description" content="Discover specialty coffee roasts from artisan roasters. Check real-time availability and find your next favorite coffee across hundreds of roasters.">
        <meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}">

        <!-- JSON-LD Structured Data -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@graph": [
                {
                    "@type": "Organization",
                    "name": "FindRoast",
                    "url": "{{ config('app.url') }}",
                    "logo": "{{ asset('images/og-image.jpg') }}",
                    "description": "Discover specialty coffee roasts from artisan roasters with real-time availability.",
                    "foundingDate": "2024"
                },
                {
                    "@type": "WebSite",
                    "name": "FindRoast",
                    "url": "{{ config('app.url') }}",
                    "description": "Discover specialty coffee roasts from artisan roasters. Check real-time availability and find your next favorite coffee.",
                    "potentialAction": {
                        "@type": "SearchAction",
                        "target": {
                            "@type": "EntryPoint",
                            "urlTemplate": "{{ config('app.url') }}?search={search_term_string}"
                        },
                        "query-input": "required name=search_term_string"
                    }
                }
            ]
        }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead

        @if (config('services.recaptcha.enabled') && config('services.recaptcha.site_key'))
            <!-- Google reCAPTCHA v3 -->
            <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
        @endif

        @if (config('app.env') === 'production')
            <!-- Plausible Analytics -->
            <!-- Privacy-friendly analytics by Plausible -->
            <script async src="https://a.521dimensions.com/js/pa-6YsJtLaLn65bsMc_16C1q.js"></script>
            <script>
                window.plausible=window.plausible||function(){(plausible.q=plausible.q||[]).push(arguments)},plausible.init=plausible.init||function(i){plausible.o=i||{}};
                plausible.init();
            </script>
        @endif

        <script src="https://cdn.bugflow.io/embed.js"></script><script>Bugflow.init({  "key": "cc373df0-ea79-4ffb-adc3-52018eaa9dd1",  "widget_color": "#92400e",  "button_color": "#92400e"})</script>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
