<template>
    <div class="bg-white w-full flex items-center justify-between px-4 border-b border-gray-100 z-50 fixed h-14 lg:hidden">
        <Link href="/">
            <img src="/images/logos/header-logo.svg" class="h-4"/>
        </Link>

        <button @click="openMenu = true" type="button">
            <Bars3Icon class="size-6 text-gray-700" />
        </button>
    </div>

    <TransitionRoot as="template" :show="openMenu">
        <Dialog as="div" class="relative z-[9999] lg:hidden" @close="openMenu = false">
            <TransitionChild 
                as="template" 
                enter="transition-opacity ease-linear duration-300" 
                enter-from="opacity-0" 
                enter-to="opacity-100" 
                leave="transition-opacity ease-linear duration-300" 
                leave-from="opacity-100" 
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-gray-900/80" />
            </TransitionChild>

            <div class="fixed inset-0 flex justify-end">
                <TransitionChild 
                    as="template" 
                    enter="transition ease-in-out duration-300 transform" 
                    enter-from="translate-x-full" 
                    enter-to="translate-x-0" 
                    leave="transition ease-in-out duration-300 transform" 
                    leave-from="translate-x-0" 
                    leave-to="translate-x-full"
                >
                    <DialogPanel class="relative w-full max-w-xs bg-white">
                        <div class="flex items-center justify-between px-4 h-14 border-b border-gray-100">
                            <Link href="/" @click="openMenu = false">
                                <img src="/images/logos/header-logo.svg" class="h-4"/>
                            </Link>
                            
                            <button @click="openMenu = false" type="button">
                                <XMarkIcon class="size-6 text-gray-700" />
                            </button>
                        </div>

                        <nav class="flex flex-col px-4 py-6">
                            <ul class="flex flex-col space-y-1">
                                <li v-for="link in links" :key="link.name">
                                    <Link 
                                        :href="link.path" 
                                        class="block px-3 py-3 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-50"
                                        @click="openMenu = false"
                                    >
                                        {{ link.name }}
                                    </Link>
                                </li>
                            </ul>
                        </nav>

                        <div v-if="!user" class="px-4 mt-auto border-t border-gray-100 pt-6">
                            <div class="flex flex-col space-y-3">
                                <SecondaryButton class="w-full justify-center" @click="promptLogin()">
                                    Log In
                                </SecondaryButton>

                                <PrimaryButton class="w-full justify-center" @click="promptRegister()">
                                    Sign Up
                                </PrimaryButton>
                            </div>
                        </div>
                        <div v-else class="px-4 mt-auto border-t border-gray-100 pt-6">
                            <Link 
                                href="/profile" 
                                class="block px-3 py-3 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-50"
                                @click="openMenu = false"
                            >
                                My Account
                            </Link>
                        </div>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useEventBus } from '@vueuse/core';
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';

const user = computed(() => usePage().props.auth.user);
const openMenu = ref(false);

const links = [
    {
        name: 'Offerings',
        path: '/',
    },
    {
        name: 'Companies',
        path: '/companies',
    },
    {
        name: 'Map',
        path: '/map',
    },
    {
        name: 'Transparency',
        path: '/transparency',
    },
    {
        name: 'Contact',
        path: '/contact',
    },
    {
        name: 'Changelog',
        path: '/changelog',
    },
];

const promptBus = useEventBus('roast-prompt-bus');

const promptLogin = () => {
    openMenu.value = false;
    promptBus.emit('prompt-login');
};

const promptRegister = () => {
    openMenu.value = false;
    promptBus.emit('prompt-register');
};
</script>

