<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';

const page = usePage();
const successMessage = computed(() => page.props.flash?.success);
const recaptchaSiteKey = computed(() => page.props.recaptchaSiteKey);
const recaptchaEnabled = computed(() => page.props.recaptchaEnabled);

const form = useForm({
    name: '',
    email: '',
    subject: 'general_inquiry',
    message: '',
    company_name: '',
    company_url: '',
    recaptcha_token: '',
});

const subjects = [
    { value: 'general_inquiry', label: 'General Inquiry' },
    { value: 'new_company', label: 'Suggest a Coffee Roaster' },
    { value: 'feature_request', label: 'Feature Request' },
    { value: 'sponsor_inquiry', label: 'Sponsor Inquiry' },
    { value: 'other', label: 'Other' },
];

const showCompanyFields = computed(() => {
    return form.subject === 'new_company';
});

// Load reCAPTCHA script
onMounted(() => {
    if (recaptchaEnabled.value && recaptchaSiteKey.value) {
        const script = document.createElement('script');
        script.src = `https://www.google.com/recaptcha/api.js?render=${recaptchaSiteKey.value}`;
        document.head.appendChild(script);
    }
});

const submit = async () => {
    // Get reCAPTCHA token if enabled
    if (recaptchaEnabled.value && recaptchaSiteKey.value && window.grecaptcha) {
        try {
            const token = await window.grecaptcha.execute(recaptchaSiteKey.value, { action: 'contact' });
            form.recaptcha_token = token;
        } catch (error) {
            console.error('reCAPTCHA error:', error);
        }
    }

    form.post(route('contact.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Contact Us" />

        <div class="min-h-screen bg-stone-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-stone-900 mb-4">Get in Touch</h1>
                    <p class="text-lg text-stone-600">
                        Have a question, suggestion, or feedback? We'd love to hear from you!
                    </p>
                </div>

                <!-- Success Message -->
                <div v-if="successMessage" class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ successMessage }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white rounded-lg shadow-sm border border-stone-200 p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name -->
                        <div>
                            <InputLabel for="name" value="Name" class="text-stone-700 font-medium" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-2 block w-full rounded-lg border-stone-300 focus:border-amber-500 focus:ring-amber-500"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <!-- Email -->
                        <div>
                            <InputLabel for="email" value="Email" class="text-stone-700 font-medium" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-2 block w-full rounded-lg border-stone-300 focus:border-amber-500 focus:ring-amber-500"
                                v-model="form.email"
                                required
                                autocomplete="email"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <!-- Subject -->
                        <div>
                            <InputLabel for="subject" value="What can we help you with?" class="text-stone-700 font-medium" />
                            <select
                                id="subject"
                                v-model="form.subject"
                                required
                                class="mt-2 block w-full rounded-lg border-stone-300 focus:border-amber-500 focus:ring-amber-500 shadow-sm"
                            >
                                <option v-for="subject in subjects" :key="subject.value" :value="subject.value">
                                    {{ subject.label }}
                                </option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.subject" />
                        </div>

                        <!-- Company Fields (conditional) -->
                        <transition
                            enter-active-class="transition duration-200 ease-out"
                            enter-from-class="opacity-0 -translate-y-2"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition duration-150 ease-in"
                            leave-from-class="opacity-100 translate-y-0"
                            leave-to-class="opacity-0 -translate-y-2"
                        >
                            <div v-if="showCompanyFields" class="space-y-6 p-4 bg-amber-50 rounded-lg border border-amber-200">
                                <p class="text-sm text-amber-900 font-medium">Company Information (Optional)</p>

                                <!-- Company Name -->
                                <div>
                                    <InputLabel for="company_name" value="Coffee Roaster Name" class="text-stone-700 font-medium" />
                                    <TextInput
                                        id="company_name"
                                        type="text"
                                        class="mt-2 block w-full rounded-lg border-stone-300 focus:border-amber-500 focus:ring-amber-500"
                                        v-model="form.company_name"
                                        placeholder="e.g., Blue Bottle Coffee"
                                    />
                                    <InputError class="mt-2" :message="form.errors.company_name" />
                                </div>

                                <!-- Company URL -->
                                <div>
                                    <InputLabel for="company_url" value="Website URL" class="text-stone-700 font-medium" />
                                    <TextInput
                                        id="company_url"
                                        type="url"
                                        class="mt-2 block w-full rounded-lg border-stone-300 focus:border-amber-500 focus:ring-amber-500"
                                        v-model="form.company_url"
                                        placeholder="https://example.com"
                                    />
                                    <InputError class="mt-2" :message="form.errors.company_url" />
                                </div>
                            </div>
                        </transition>

                        <!-- Message -->
                        <div>
                            <InputLabel for="message" value="Message" class="text-stone-700 font-medium" />
                            <textarea
                                id="message"
                                v-model="form.message"
                                required
                                rows="6"
                                class="mt-2 block w-full rounded-lg border-stone-300 focus:border-amber-500 focus:ring-amber-500 shadow-sm"
                                placeholder="Tell us more about your inquiry..."
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.message" />
                            <p class="mt-2 text-sm text-stone-500">
                                Please provide as much detail as possible to help us assist you better.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end pt-4">
                            <PrimaryButton
                                class="bg-amber-700 hover:bg-amber-800 focus:ring-amber-500 px-6 py-3 text-base"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing">Sending...</span>
                                <span v-else>Send Message</span>
                            </PrimaryButton>
                        </div>
                    </form>
                </div>

                <!-- Additional Info -->
                <div class="mt-8 text-center text-sm text-stone-600">
                    <p>
                        We typically respond within 1-2 business days.
                    </p>
                    <p v-if="recaptchaEnabled" class="mt-2 text-xs text-stone-500">
                        This site is protected by reCAPTCHA and the Google
                        <a href="https://policies.google.com/privacy" target="_blank" class="text-amber-700 hover:text-amber-800">Privacy Policy</a> and
                        <a href="https://policies.google.com/terms" target="_blank" class="text-amber-700 hover:text-amber-800">Terms of Service</a> apply.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
