<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <Head title="API Documentation" />

        <div class="min-h-screen bg-stone-50 py-12 px-4 sm:px-6 lg:px-8">
            <article class="max-w-4xl mx-auto">
                <!-- Header -->
                <header class="mb-12">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-8 w-1 bg-amber-700 rounded-full"></div>
                        <span class="text-sm font-semibold text-amber-700 uppercase tracking-wide">For Developers</span>
                    </div>
                    <h1 class="text-4xl font-bold text-stone-900 mb-4">API Documentation</h1>
                    <p class="text-xl text-stone-600 leading-relaxed">
                        Access FindRoast's coffee data programmatically. Our API provides simple, RESTful access to search and retrieve specialty coffee offerings.
                    </p>
                </header>

                <!-- Content Sections -->
                <div class="space-y-8">
                    <!-- Getting Started -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Getting Started
                        </h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                The FindRoast API uses standard HTTP methods and returns JSON responses. All API requests require authentication using a personal access token.
                            </p>
                            <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                <p class="text-sm font-mono text-stone-900">
                                    Base URL: <span class="text-amber-700">https://findroast.com/api/v1</span>
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Authentication -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            Authentication
                        </h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                All API requests must include a valid personal access token in the <code class="px-2 py-1 bg-stone-100 rounded text-sm font-mono text-stone-900">Authorization</code> header.
                            </p>

                            <div>
                                <h3 class="text-lg font-semibold text-stone-900 mb-3">Creating an API Token</h3>
                                <ol class="space-y-2 ml-6 list-decimal">
                                    <li>
                                        <Link href="/register" class="text-amber-700 hover:text-amber-800 transition-colors">Create a FindRoast account</Link> or <Link href="/login" class="text-amber-700 hover:text-amber-800 transition-colors">log in</Link>
                                    </li>
                                    <li>Navigate to your <Link href="/profile" class="text-amber-700 hover:text-amber-800 transition-colors">Profile page</Link></li>
                                    <li>Scroll to the "API Tokens" section</li>
                                    <li>Click "Create New Token" and give it a descriptive name</li>
                                    <li>Copy the token immediately - it won't be shown again</li>
                                </ol>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-stone-900 mb-3">Using Your Token</h3>
                                <p class="mb-3">Include your token in the <code class="px-2 py-1 bg-stone-100 rounded text-sm font-mono text-stone-900">Authorization</code> header as a Bearer token:</p>
                                <pre class="bg-stone-900 text-stone-100 rounded-lg p-4 overflow-x-auto text-sm"><code>curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  https://findroast.com/api/v1/roasts</code></pre>
                            </div>
                        </div>
                    </section>

                    <!-- Rate Limiting -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Rate Limiting
                        </h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                API requests are limited to <strong class="text-stone-900">60 requests per minute</strong> per token. Rate limit information is included in the response headers:
                            </p>
                            <div class="bg-stone-50 rounded-lg p-4 border border-stone-200 font-mono text-sm space-y-1">
                                <p><span class="text-stone-600">X-RateLimit-Limit:</span> <span class="text-stone-900">60</span></p>
                                <p><span class="text-stone-600">X-RateLimit-Remaining:</span> <span class="text-stone-900">59</span></p>
                            </div>
                            <p>
                                If you exceed the rate limit, you'll receive a <code class="px-2 py-1 bg-stone-100 rounded text-sm font-mono text-stone-900">429 Too Many Requests</code> response. Please implement appropriate backoff strategies in your applications.
                            </p>
                        </div>
                    </section>

                    <!-- Endpoints -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-6 flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            API Endpoints
                        </h2>

                        <!-- List Roasts Endpoint -->
                        <div class="mb-8 pb-8 border-b border-stone-200 last:border-0 last:pb-0 last:mb-0">
                            <div class="mb-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded">GET</span>
                                    <code class="text-lg font-mono text-stone-900">/roasts</code>
                                </div>
                                <p class="text-stone-600">Retrieve a paginated list of coffee roasts with optional filtering.</p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-base font-semibold text-stone-900 mb-3">Query Parameters</h4>
                                    <div class="space-y-3">
                                        <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                            <div class="flex items-start gap-3">
                                                <code class="text-sm font-mono text-amber-700 font-semibold">search</code>
                                                <div class="flex-1">
                                                    <p class="text-sm text-stone-600 mb-1"><em>string, optional</em></p>
                                                    <p class="text-sm text-stone-700">Search term to filter roasts by name or description (max 255 characters)</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                            <div class="flex items-start gap-3">
                                                <code class="text-sm font-mono text-amber-700 font-semibold">processes</code>
                                                <div class="flex-1">
                                                    <p class="text-sm text-stone-600 mb-1"><em>array of integers, optional</em></p>
                                                    <p class="text-sm text-stone-700">Filter by coffee processing method IDs (e.g., washed, natural, honey)</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                            <div class="flex items-start gap-3">
                                                <code class="text-sm font-mono text-amber-700 font-semibold">flavor_notes</code>
                                                <div class="flex-1">
                                                    <p class="text-sm text-stone-600 mb-1"><em>array of integers, optional</em></p>
                                                    <p class="text-sm text-stone-700">Filter by flavor note IDs (e.g., chocolate, berry, citrus)</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                            <div class="flex items-start gap-3">
                                                <code class="text-sm font-mono text-amber-700 font-semibold">varieties</code>
                                                <div class="flex-1">
                                                    <p class="text-sm text-stone-600 mb-1"><em>array of integers, optional</em></p>
                                                    <p class="text-sm text-stone-700">Filter by coffee variety IDs (e.g., Bourbon, Typica, Gesha)</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                            <div class="flex items-start gap-3">
                                                <code class="text-sm font-mono text-amber-700 font-semibold">countries</code>
                                                <div class="flex-1">
                                                    <p class="text-sm text-stone-600 mb-1"><em>array of integers, optional</em></p>
                                                    <p class="text-sm text-stone-700">Filter by origin country IDs</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                            <div class="flex items-start gap-3">
                                                <code class="text-sm font-mono text-amber-700 font-semibold">companies</code>
                                                <div class="flex-1">
                                                    <p class="text-sm text-stone-600 mb-1"><em>array of integers, optional</em></p>
                                                    <p class="text-sm text-stone-700">Filter by roaster/company IDs</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                            <div class="flex items-start gap-3">
                                                <code class="text-sm font-mono text-amber-700 font-semibold">sort</code>
                                                <div class="flex-1">
                                                    <p class="text-sm text-stone-600 mb-1"><em>string, optional</em></p>
                                                    <p class="text-sm text-stone-700">Sort order: <code class="px-1.5 py-0.5 bg-white rounded text-xs">newest</code> or <code class="px-1.5 py-0.5 bg-white rounded text-xs">a-z</code></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                            <div class="flex items-start gap-3">
                                                <code class="text-sm font-mono text-amber-700 font-semibold">per_page</code>
                                                <div class="flex-1">
                                                    <p class="text-sm text-stone-600 mb-1"><em>integer, optional</em></p>
                                                    <p class="text-sm text-stone-700">Number of results per page (min: 1, max: 50, default: 12)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-base font-semibold text-stone-900 mb-3">Example Request</h4>
                                    <pre class="bg-stone-900 text-stone-100 rounded-lg p-4 overflow-x-auto text-sm"><code>curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  "https://findroast.com/api/v1/roasts?search=ethiopia&sort=newest&per_page=20"</code></pre>
                                </div>

                                <div>
                                    <h4 class="text-base font-semibold text-stone-900 mb-3">Example Response</h4>
                                    <pre class="bg-stone-900 text-stone-100 rounded-lg p-4 overflow-x-auto text-sm"><code>{
  "data": [
    {
      "id": 123,
      "name": "Ethiopia Yirgacheffe",
      "url": "https://example.com/products/ethiopia-yirgacheffe",
      "price": "18.50",
      "currency": "USD",
      "type": "Light Roast",
      "in_stock": true,
      "primary_image": "https://example.com/images/product.jpg",
      "first_seen_at": "2026-01-15T10:30:00.000000Z",
      "last_seen_at": "2026-02-20T14:22:00.000000Z",
      "created_at": "2026-01-15T10:30:00.000000Z",
      "company": {
        "id": 45,
        "name": "Acme Coffee Roasters",
        "slug": "acme-coffee-roasters"
      },
      "flavor_notes": [
        {"id": 12, "name": "Blueberry"},
        {"id": 34, "name": "Floral"}
      ],
      "processes": [
        {"id": 2, "name": "Washed"}
      ],
      "countries": [
        {"id": 67, "name": "Ethiopia"}
      ],
      "varieties": [
        {"id": 8, "name": "Heirloom"}
      ],
      "elevations": [
        {"id": 5, "name": "1800-2000m"}
      ]
    }
  ],
  "links": {
    "first": "https://findroast.com/api/v1/roasts?page=1",
    "last": "https://findroast.com/api/v1/roasts?page=5",
    "prev": null,
    "next": "https://findroast.com/api/v1/roasts?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 12,
    "to": 12,
    "total": 58
  }
}</code></pre>
                                </div>
                            </div>
                        </div>

                        <!-- Get Single Roast Endpoint -->
                        <div>
                            <div class="mb-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded">GET</span>
                                    <code class="text-lg font-mono text-stone-900">/roasts/{id}</code>
                                </div>
                                <p class="text-stone-600">Retrieve detailed information about a specific coffee roast.</p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-base font-semibold text-stone-900 mb-3">Path Parameters</h4>
                                    <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                        <div class="flex items-start gap-3">
                                            <code class="text-sm font-mono text-amber-700 font-semibold">id</code>
                                            <div class="flex-1">
                                                <p class="text-sm text-stone-600 mb-1"><em>integer, required</em></p>
                                                <p class="text-sm text-stone-700">The ID of the roast to retrieve</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-base font-semibold text-stone-900 mb-3">Example Request</h4>
                                    <pre class="bg-stone-900 text-stone-100 rounded-lg p-4 overflow-x-auto text-sm"><code>curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  https://findroast.com/api/v1/roasts/123</code></pre>
                                </div>

                                <div>
                                    <h4 class="text-base font-semibold text-stone-900 mb-3">Example Response</h4>
                                    <pre class="bg-stone-900 text-stone-100 rounded-lg p-4 overflow-x-auto text-sm"><code>{
  "data": {
    "id": 123,
    "name": "Ethiopia Yirgacheffe",
    "url": "https://example.com/products/ethiopia-yirgacheffe",
    "price": "18.50",
    "currency": "USD",
    "type": "Light Roast",
    "in_stock": true,
    "primary_image": "https://example.com/images/product.jpg",
    "first_seen_at": "2026-01-15T10:30:00.000000Z",
    "last_seen_at": "2026-02-20T14:22:00.000000Z",
    "created_at": "2026-01-15T10:30:00.000000Z",
    "company": {
      "id": 45,
      "name": "Acme Coffee Roasters",
      "slug": "acme-coffee-roasters"
    },
    "flavor_notes": [
      {"id": 12, "name": "Blueberry"},
      {"id": 34, "name": "Floral"}
    ],
    "processes": [
      {"id": 2, "name": "Washed"}
    ],
    "countries": [
      {"id": 67, "name": "Ethiopia"}
    ],
    "varieties": [
      {"id": 8, "name": "Heirloom"}
    ],
    "elevations": [
      {"id": 5, "name": "1800-2000m"}
    ]
  }
}</code></pre>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Errors -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Error Responses
                        </h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>The API uses standard HTTP status codes to indicate success or failure:</p>
                            <div class="space-y-3">
                                <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                    <p class="font-semibold text-stone-900 mb-1"><code class="px-2 py-1 bg-white rounded text-sm">401 Unauthorized</code></p>
                                    <p class="text-sm">Missing or invalid authentication token</p>
                                </div>
                                <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                    <p class="font-semibold text-stone-900 mb-1"><code class="px-2 py-1 bg-white rounded text-sm">404 Not Found</code></p>
                                    <p class="text-sm">The requested resource does not exist</p>
                                </div>
                                <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                    <p class="font-semibold text-stone-900 mb-1"><code class="px-2 py-1 bg-white rounded text-sm">422 Unprocessable Entity</code></p>
                                    <p class="text-sm">Validation error - check the response for details</p>
                                </div>
                                <div class="bg-stone-50 rounded-lg p-4 border border-stone-200">
                                    <p class="font-semibold text-stone-900 mb-1"><code class="px-2 py-1 bg-white rounded text-sm">429 Too Many Requests</code></p>
                                    <p class="text-sm">Rate limit exceeded - wait before making more requests</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Support Section -->
                    <section class="bg-amber-50 rounded-xl border border-amber-200 p-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">Need Help?</h2>
                        <p class="text-stone-700 leading-relaxed mb-6">
                            Have questions about the API or need help integrating? We're here to help. Reach out through our contact form or open an issue on GitHub.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <Link
                                href="/contact"
                                style="color: #fff;"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors font-medium">
                                Contact Us
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </Link>
                            <a
                                href="https://github.com/serversideup/findroast"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-stone-900 text-white rounded-lg hover:bg-stone-800 transition-colors font-medium">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                </svg>
                                View on GitHub
                            </a>
                        </div>
                    </section>
                </div>
            </article>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Code styling */
code {
    @apply font-mono;
}

pre code {
    @apply block;
}

/* Smooth link transitions */
a {
    @apply transition-colors;
}
</style>
