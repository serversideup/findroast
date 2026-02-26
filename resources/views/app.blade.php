<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead

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
