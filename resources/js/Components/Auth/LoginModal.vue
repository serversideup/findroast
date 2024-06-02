<template>
    <Modal :max-width="'md'" :show="show" @close="close()">
        <form @submit.prevent="submit" class="p-6">
            <div class="flex flex-col items-center justify-center">
                <img src="/images/logos/header-logo.svg" class="mb-4"/>
                <h3 class="text-lg font-medium text-gray-900 mb-5">Log in to your account</h3>
            </div>
            
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
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
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="button"
                    @click="promptRegister()"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Need an account?
                </button>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useEventBus } from '@vueuse/core';

const show = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const bus = useEventBus('roast-prompt-bus')

const listener = ( event ) => {
    if( event == 'prompt-login' ){
        show.value = true;
    }
}
bus.on(listener);

const close = () => {
    show.value = false;
    form.reset();
}

const submit = () => {
    form.post(route('login'), {
        onSuccess: () => {
            close();
        },
    });
};

const promptRegister = () => {
    show.value = false;
    bus.emit('prompt-register');
}
</script>