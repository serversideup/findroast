<template>
    <Modal :max-width="'md'" :show="show" @close="close()">
        <form @submit.prevent="submit" class="p-6">
            <div class="flex flex-col items-center justify-center">
                <img src="/images/logos/header-logo.svg" class="mb-4"/>
                <h3 class="text-lg font-medium text-gray-900 mb-5">Sign up for your free account</h3>
            </div>
            
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div v-if="recaptchaEnabled" class="mt-4 text-xs text-gray-600">
                This site is protected by reCAPTCHA and the Google
                <a href="https://policies.google.com/privacy" target="_blank" class="underline hover:text-gray-900">Privacy Policy</a> and
                <a href="https://policies.google.com/terms" target="_blank" class="underline hover:text-gray-900">Terms of Service</a> apply.
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="button"
                    @click="promptLogin()"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Already registered?
                </button>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { useEventBus } from '@vueuse/core';

const show = ref(false);
const page = usePage();

// Get reCAPTCHA config from page props if available
const recaptchaSiteKey = computed(() => page.props.recaptchaSiteKey || null);
const recaptchaEnabled = computed(() => page.props.recaptchaEnabled || false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    recaptcha_token: '',
});

const bus = useEventBus('roast-prompt-bus')

const listener = ( event ) => {
    if( event == 'prompt-register' ){
        show.value = true;
    }
}
bus.on(listener);

const close = () => {
    show.value = false;
    form.reset();
}

const submit = async () => {
    // Execute reCAPTCHA if enabled
    if (recaptchaEnabled.value && recaptchaSiteKey.value && window.grecaptcha) {
        try {
            const token = await window.grecaptcha.execute(recaptchaSiteKey.value, { action: 'register' });
            form.recaptcha_token = token;
        } catch (error) {
            console.error('reCAPTCHA error:', error);
            // Continue with form submission even if reCAPTCHA fails
            // The server will handle validation
        }
    }

    form.post(route('register'), {
        onFinish: () => {
            close()
        },
    });
};

const promptLogin = () => {
    show.value = false;
    bus.emit('prompt-login');
}
</script>