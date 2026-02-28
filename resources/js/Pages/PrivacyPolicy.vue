<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <Head title="Privacy Policy" />

        <div class="min-h-screen bg-stone-50 py-12 px-4 sm:px-6 lg:px-8">
            <article class="max-w-3xl mx-auto">
                <!-- Header -->
                <header class="mb-12">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-8 w-1 bg-amber-700 rounded-full"></div>
                        <span class="text-sm font-semibold text-amber-700 uppercase tracking-wide">Legal</span>
                    </div>
                    <h1 class="text-4xl font-bold text-stone-900 mb-4">Privacy Policy</h1>
                    <p class="text-stone-600">
                        Last Updated: February 25, 2026
                    </p>
                </header>

                <!-- Content -->
                <div class="prose prose-stone prose-lg max-w-none">
                    <!-- Introduction -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">Introduction</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                At FindRoast, we respect your privacy and are committed to protecting your personal data. This Privacy Policy explains how we collect, use, and safeguard your information when you use our Service.
                            </p>
                            <p>
                                FindRoast is operated by ServerSideUp. By using our Service, you agree to the collection and use of information in accordance with this policy.
                            </p>
                        </div>
                    </section>

                    <!-- Information We Collect -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">1. Information We Collect</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <h3 class="text-lg font-semibold text-stone-900 mt-4 mb-2">Personal Information</h3>
                            <p>
                                When you create an account, we collect:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Email address (required for authentication)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Name (if provided)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Password (encrypted and hashed)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>API tokens you generate for accessing our API</span>
                                </li>
                            </ul>

                            <h3 class="text-lg font-semibold text-stone-900 mt-4 mb-2">Usage Information</h3>
                            <p>
                                We automatically collect certain information when you use our Service:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Search queries and filter preferences</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Pages visited and features used</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Browser type and operating system</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>IP address (anonymized)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Referral source</span>
                                </li>
                            </ul>

                            <h3 class="text-lg font-semibold text-stone-900 mt-4 mb-2">Contact Form Data</h3>
                            <p>
                                When you contact us through our contact form, we collect:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Your name and email address</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>The content of your message</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Subject and reason for contact</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <!-- How We Use Your Information -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">2. How We Use Your Information</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                We use the information we collect to:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Provide, maintain, and improve our Service</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Authenticate your account and provide API access</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Personalize your experience and save your preferences</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Communicate with you about your account or our Service</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Analyze usage patterns to improve functionality</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Prevent fraud and ensure security</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Respond to your inquiries and support requests</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <!-- Analytics -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">3. Analytics</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                We use <a href="https://plausible.io" target="_blank" rel="noopener noreferrer" class="text-amber-700 hover:text-amber-800 transition-colors">Plausible Analytics</a>, a privacy-focused analytics service that:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Does not use cookies</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Does not collect personal information</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Does not track you across websites</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Anonymizes your IP address</span>
                                </li>
                            </ul>
                            <p>
                                This allows us to understand aggregate usage patterns without compromising your privacy.
                            </p>
                        </div>
                    </section>

                    <!-- Data Sharing -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">4. How We Share Your Information</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                We do not sell, rent, or trade your personal information. We may share your information only in the following circumstances:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span><strong>Service Providers:</strong> With trusted third-party services that help us operate our platform (hosting, email delivery, analytics)</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span><strong>Legal Requirements:</strong> When required by law or to protect our rights</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span><strong>Business Transfers:</strong> In the event of a merger, acquisition, or sale of assets</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span><strong>With Your Consent:</strong> When you explicitly agree to share information</span>
                                </li>
                            </ul>
                        </div>
                    </section>

                    <!-- Data Security -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">5. Data Security</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                We implement industry-standard security measures to protect your data:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>All connections use HTTPS/TLS encryption</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Passwords are hashed using bcrypt</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>API tokens are encrypted at rest</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Regular security updates and monitoring</span>
                                </li>
                            </ul>
                            <p>
                                However, no method of transmission over the internet is 100% secure. While we strive to protect your data, we cannot guarantee absolute security.
                            </p>
                        </div>
                    </section>

                    <!-- Your Rights -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">6. Your Privacy Rights</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                You have the following rights regarding your personal data:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span><strong>Access:</strong> Request a copy of the personal data we hold about you</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span><strong>Correction:</strong> Update or correct inaccurate information</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span><strong>Deletion:</strong> Request deletion of your account and associated data</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span><strong>Export:</strong> Request a machine-readable copy of your data</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span><strong>Opt-out:</strong> Unsubscribe from communications (where applicable)</span>
                                </li>
                            </ul>
                            <p>
                                To exercise these rights, please contact us through our contact form or email.
                            </p>
                        </div>
                    </section>

                    <!-- Data Retention -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">7. Data Retention</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                We retain your personal information only as long as necessary to:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Provide our services to you</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Comply with legal obligations</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Resolve disputes and enforce our agreements</span>
                                </li>
                            </ul>
                            <p>
                                When you delete your account, we will remove your personal information within 30 days, except where we're required to retain it for legal or security purposes.
                            </p>
                        </div>
                    </section>

                    <!-- Cookies -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">8. Cookies and Tracking</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                We use minimal cookies for essential functionality:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span><strong>Session Cookies:</strong> To keep you logged in</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span><strong>Security Cookies:</strong> To prevent CSRF attacks</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span><strong>Preference Cookies:</strong> To remember your filter and search settings</span>
                                </li>
                            </ul>
                            <p>
                                We do not use advertising or tracking cookies. You can configure your browser to refuse cookies, but this may limit your ability to use certain features.
                            </p>
                        </div>
                    </section>

                    <!-- Third-Party Links -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">9. Third-Party Links</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                Our Service contains links to roaster websites and other third-party sites. We are not responsible for the privacy practices or content of these external sites. We encourage you to review the privacy policies of any third-party sites you visit.
                            </p>
                        </div>
                    </section>

                    <!-- Children's Privacy -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">10. Children's Privacy</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                Our Service is not directed to individuals under the age of 13. We do not knowingly collect personal information from children. If you believe we have inadvertently collected information from a child, please contact us immediately.
                            </p>
                        </div>
                    </section>

                    <!-- International Users -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">11. International Users</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                If you are accessing our Service from outside the United States, please be aware that your information may be transferred to, stored, and processed in the United States where our servers are located.
                            </p>
                            <p>
                                By using our Service, you consent to the transfer of your information to the United States and agree that U.S. law will govern the collection and use of your information.
                            </p>
                        </div>
                    </section>

                    <!-- Changes to Policy -->
                    <section class="bg-white rounded-xl border border-stone-200 shadow-sm p-8 mb-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">12. Changes to This Policy</h2>
                        <div class="space-y-4 text-stone-700 leading-relaxed">
                            <p>
                                We may update this Privacy Policy from time to time. We will notify you of any material changes by:
                            </p>
                            <ul class="space-y-2 ml-6">
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Posting the new policy on this page</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Updating the "Last Updated" date</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-amber-700 mt-1.5">•</span>
                                    <span>Sending an email notification (for significant changes)</span>
                                </li>
                            </ul>
                            <p>
                                Your continued use of the Service after changes indicates your acceptance of the updated policy.
                            </p>
                        </div>
                    </section>

                    <!-- Contact Section -->
                    <section class="bg-amber-50 rounded-xl border border-amber-200 p-8">
                        <h2 class="text-2xl font-bold text-stone-900 mb-4">Questions About Privacy?</h2>
                        <p class="text-stone-700 leading-relaxed mb-6">
                            If you have any questions about this Privacy Policy or how we handle your data, please contact us.
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

.prose a {
    @apply text-amber-700 hover:text-amber-800 transition-colors;
}
</style>
