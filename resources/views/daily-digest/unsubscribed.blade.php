<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Unsubscribed - FindRoast</title>
        @vite(['resources/css/app.css'])
    </head>
    <body class="font-sans antialiased bg-stone-50">
        <div class="min-h-screen flex items-center justify-center px-4">
            <div class="max-w-md w-full bg-white rounded-lg shadow-sm border border-stone-200 p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 mb-4">
                    <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-stone-900 mb-2">You're Unsubscribed</h1>
                <p class="text-stone-600 mb-6">
                    You have been successfully unsubscribed from the FindRoast daily digest.
                    You will no longer receive daily emails about new coffees.
                </p>
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-amber-700 hover:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                    Return to FindRoast
                </a>
            </div>
        </div>
    </body>
</html>
