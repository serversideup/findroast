<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <Head title="Terms of Service" />

        <div class="min-h-screen bg-stone-50 py-12 px-4 sm:px-6 lg:px-8">
            <article class="max-w-3xl mx-auto">
                <!-- Header -->
                <header class="mb-12">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-8 w-1 bg-amber-700 rounded-full"></div>
                        <span class="text-sm font-semibold text-amber-700 uppercase tracking-wide">Legal</span>
                    </div>
                    <h1 class="text-4xl font-bold text-stone-900 mb-4">Terms of Service</h1>
                    <p class="text-stone-600">
                        Last Updated: February 25, 2026
                    </p>
                </header>

                <!-- Content -->
                <div class="prose prose-stone prose-lg max-w-none">
                    <!-- Introduction -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">1. Agreement to Terms</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                By accessing or using FindRoast.com (the "Service"), you agree to be bound by these Terms of Service ("Terms"). If you disagree with any part of these terms, you may not access the Service.
                            </p>
                            <p>
                                FindRoast is operated by ServerSideUp. These Terms apply to all visitors, users, and others who access or use the Service.
                            </p>
                        </div>
                    </section>

                    <!-- Service Description -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">2. Description of Service</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                FindRoast is a coffee discovery platform that aggregates publicly available information about specialty coffee offerings from various roasters. The Service provides:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Search and filter capabilities for coffee offerings</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Information about coffee roasters and their products</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>API access for developers (requires account registration)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>User accounts for personalized features</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <!-- User Accounts -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">3. User Accounts</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                When you create an account with us, you must provide accurate, complete, and current information. You are responsible for:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Safeguarding your password and API tokens</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>All activities that occur under your account</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Notifying us immediately of any unauthorized use</span>
                                </li>
                            </ul>
                            <p>
                                We reserve the right to refuse service, terminate accounts, or remove content at our sole discretion.
                            </p>
                        </div>
                    </section>

                    <!-- Acceptable Use -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">4. Acceptable Use</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                You agree not to use the Service to:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Violate any laws or regulations</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Transmit any harmful or malicious code</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Attempt to gain unauthorized access to the Service or its systems</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Scrape or harvest data in violation of our API terms</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Overload or interfere with the proper functioning of the Service</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Use the Service for any unlawful or fraudulent purpose</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <!-- API Usage -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">5. API Usage</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                If you use our API, you agree to:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Respect rate limits and usage quotas</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Properly attribute data sourced from FindRoast</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Keep your API tokens secure and not share them</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Not resell or redistribute API data without permission</span>
                                </li>
                            </ul>
                            <p>
                                We reserve the right to modify, suspend, or terminate API access at any time.
                            </p>
                        </div>
                    </section>

                    <!-- Content and Data -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">6. Content and Data</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                The information on FindRoast is aggregated from publicly available sources. While we strive for accuracy, we make no guarantees about:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>The completeness, accuracy, or reliability of any information</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Product availability, pricing, or specifications</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>The quality or practices of featured roasters</span>
                                </li>
                            </ul>
                            <p>
                                All coffee purchases are made directly with roasters. FindRoast is not responsible for transactions between users and roasters.
                            </p>
                        </div>
                    </section>

                    <!-- Intellectual Property -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">7. Intellectual Property</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                The Service and its original content (excluding aggregated roaster data) are owned by ServerSideUp and are protected by copyright, trademark, and other laws.
                            </p>
                            <p>
                                FindRoast is open source software. Our codebase is available under the terms specified in our <a href="https://github.com/serversideup/findroast" target="_blank" rel="noopener noreferrer" class="text-amber-700 hover:text-amber-800 transition-colors">GitHub repository</a>.
                            </p>
                            <p>
                                Product information, images, and descriptions displayed on FindRoast remain the property of their respective roasters.
                            </p>
                        </div>
                    </section>

                    <!-- Disclaimer of Warranties -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">8. Disclaimer of Warranties</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                The Service is provided "as is" and "as available" without warranties of any kind, either express or implied. We do not warrant that:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>The Service will be uninterrupted, timely, secure, or error-free</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>The results obtained from using the Service will be accurate or reliable</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Any errors in the Service will be corrected</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <!-- Limitation of Liability -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">9. Limitation of Liability</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                To the maximum extent permitted by law, FindRoast and ServerSideUp shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Your use or inability to use the Service</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Any unauthorized access to or alteration of your data</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Any transactions or relationships between you and third-party roasters</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Any errors, mistakes, or inaccuracies in content</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <!-- Changes to Terms -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">10. Changes to Terms</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                We reserve the right to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days' notice prior to any new terms taking effect.
                            </p>
                            <p>
                                By continuing to access or use our Service after revisions become effective, you agree to be bound by the revised terms.
                            </p>
                        </div>
                    </section>

                    <!-- Governing Law -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">11. Governing Law</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                These Terms shall be governed by and construed in accordance with the laws of the United States, without regard to its conflict of law provisions.
                            </p>
                        </div>
                    </section>

                    <!-- Contact Section -->
                    <section class="bg-amber-50 rounded-xl border border-amber-200 p-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">Questions About These Terms?</h2>
                        <p class="text-stone-700 leading-relaxed mb-6">
                            If you have any questions about these Terms of Service, please contact us.
                        </p>
                        <Link
                            href="/contact"
                            style="color: #fff;"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors font-medium">
                            Contact Us
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </Link>
                    </section>
                </div>
            </article>
        </div>
    </AppLayout>
</template>

<style scoped>
.prose {
    @apply text-stone-700;
}

.prose h2 {
    @apply mt-0 mb-4;
}

.prose p {
    @apply mb-4 last:mb-0;
}

.prose ul {
    @apply my-4;
}

.prose li {
    @apply mb-2 last:mb-0;
}

.prose a {
    @apply text-amber-700 hover:text-amber-800 transition-colors;
}
</style>
