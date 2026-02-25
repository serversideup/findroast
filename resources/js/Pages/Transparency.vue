<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <Head title="Transparency" />

        <div class="min-h-screen bg-stone-50 py-12 px-4 sm:px-6 lg:px-8">
            <article class="max-w-3xl mx-auto">
                <!-- Header -->
                <header class="mb-12">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-8 w-1 bg-amber-700 rounded-full"></div>
                        <span class="text-sm font-semibold text-amber-700 uppercase tracking-wide">Our Commitment</span>
                    </div>
                    <h1 class="text-4xl font-bold text-stone-900 mb-4">Transparency</h1>
                    <p class="text-xl text-stone-600 leading-relaxed">
                        The coffee industry thrives on transparency. We believe in building FindRoast with openness, honesty, and accountability. Here's how we operate and what we value.
                    </p>
                </header>

                <!-- Content Sections -->
                <div class="prose prose-stone prose-lg max-w-none">
                    <!-- Open Source Section -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            Open Source
                        </h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                FindRoast is built as an open-source project by <a href="https://serversideup.net" target="_blank" rel="noopener noreferrer" class="text-amber-700 hover:text-amber-800 transition-colors">ServerSideUp</a>. Our code is publicly available on GitHub, allowing anyone to inspect, contribute, and understand how the platform works.
                            </p>
                            <p>
                                We believe transparency starts with open code. By making our platform open source, we invite the coffee community to help us build something better together.
                            </p>
                            <div class="mt-6">
                                <a
                                    href="https://github.com/serversideup/findroast"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    style="color: #fff;"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-stone-900 text-white rounded-lg hover:bg-stone-800 transition-colors font-medium">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                    </svg>
                                    View on GitHub
                                </a>
                            </div>
                        </div>
                    </section>

                    <!-- Data Privacy Section -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Data & Privacy
                        </h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                Your privacy matters. We collect minimal data necessary to provide our service and never sell your information to third parties.
                            </p>
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 my-4">
                                <h3 class="text-base font-semibold text-amber-900 mb-2">What we collect:</h3>
                                <ul class="space-y-2 text-sm text-amber-900">
                                    <li class="flex items-start gap-2">
                                        <span class="text-amber-700 mt-0.5">•</span>
                                        <span>Account information (email, name) for authentication</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-amber-700 mt-0.5">•</span>
                                        <span>Search preferences and saved filters to improve your experience</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-amber-700 mt-0.5">•</span>
                                        <span>Anonymous usage analytics to understand how people use FindRoast</span>
                                    </li>
                                </ul>
                            </div>
                            <p>
                                We use industry-standard security practices to protect your data. All connections are encrypted, and passwords are securely hashed.
                            </p>
                        </div>
                    </section>

                    <!-- Data Sources Section -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Data Sources
                        </h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                FindRoast aggregates coffee offerings from various roasters' websites. We gather publicly available information including:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Coffee names, descriptions, and pricing</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Origin information, processing methods, and flavor profiles</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Company information and product availability</span>
                                </li>
                            </ul>
                            <p>
                                We respect robots.txt directives and aim to be good citizens of the web. If you're a roaster and have concerns about how we display your information, please <Link href="/contact" class="text-amber-700 hover:text-amber-800 transition-colors">contact us</Link>.
                            </p>
                        </div>
                    </section>

                    <!-- Revenue Model Section -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            How We Sustain Ourselves
                        </h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                FindRoast is currently free to use. To be honest, it was built as an app because I wanted to see what coffee is avaialble on the market. However, we're exploring sustainable revenue models that align with our values:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Affiliate partnerships with roasters (you'll always know when links are affiliate links)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Sponsored listings. These will be clearly marked as such.</span>
                                </li>
                            </ul>
                            <p>
                                We'll never compromise our core mission of helping coffee lovers discover great coffee. The search engine will always be free.
                            </p>
                            <p>
                                We are app developers as well. If you like FindRoast awesome and looking to support our work, you can <a href="https://serversideup.net/hire-us" class="text-amber-700 hover:text-amber-800 transition-colors">hire us</a> to build your app. The process is simple, tell us what you want, assign us tasks, and we'll get it done! We have over 15 years of building high quality, professional web and mobile applications.
                            </p>
                        </div>
                    </section>

                    <!-- Our Values Section -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Our Values
                        </h2>
                        <div class="space-y-6 text-stone-700 leading-relaxed">
                            <div>
                                <h3 class="text-lg font-semibold text-stone-900 mb-2">Coffee Community First</h3>
                                <p>
                                    We exist to serve coffee lovers and support specialty roasters. Every decision we make considers the impact on both.
                                </p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-stone-900 mb-2">Quality Over Quantity</h3>
                                <p>
                                    We focus on specialty coffee roasters who care about quality, sustainability, and their craft.
                                </p>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-stone-900 mb-2">Continuous Improvement</h3>
                                <p>
                                    We're always learning and improving. We welcome feedback, embrace mistakes as learning opportunities, and iterate based on community input.
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- Contact Section -->
                    <section class="bg-amber-50 rounded-xl border border-amber-200 p-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">Questions or Concerns?</h2>
                        <p class="text-stone-700 leading-relaxed mb-6">
                            We're here to help. If you have questions about how we operate, concerns about data privacy, or suggestions for how we can be more transparent, please reach out.
                        </p>
                        <a
                            href="/contact"
                            style="color: #fff;"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition-colors font-medium">
                            Contact Us
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </section>
                </div>
            </article>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Enhanced typography for readability */
.prose {
    @apply text-stone-700;
}

.prose h2 {
    @apply mt-0 mb-4;
}

.prose h3 {
    @apply mt-0 mb-2;
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

/* Smooth link transitions */
.prose a {
    @apply text-amber-700 hover:text-amber-800 transition-colors;
}
</style>
