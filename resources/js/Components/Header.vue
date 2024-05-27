<template>
    <div class="bg-white w-full flex items-center justify-between px-5 py-2 border-b border-gray-100 z-50 fixed">
        <div class="flex items-center justify-between mx-auto w-full max-w-screen-xxl">
            <div class="flex items-center w-64">
                <Link href="/">
                    <img src="/images/logos/header-logo.svg" class="h-5"/>
                </Link>
            </div>
                
            <nav class="flex justify-between items-center">
                <ul class="flex items-center space-x-6">
                    <li v-for="link in links">
                        <Link :href="link.path || '/'">
                            {{ link.name }}
                        </Link>
                    </li>
                </ul>
            </nav>

            <div v-if="!user" class="flex items-center justify-end space-x-3 w-64">
                <SecondaryButton @click="promptLogin()">
                    Log In
                </SecondaryButton>

                <PrimaryButton @click="promptRegister()">
                    Sign Up
                </PrimaryButton>
            </div>
            <div v-else>
                <!-- <AppHeaderUserMenu/> -->
            </div>
        </div>

        <!-- <AuthLogin/>
        <AuthRegister/> -->
    </div>
</template>

<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useEventBus } from '@vueuse/core';

const user = computed(() => usePage().props.user);

const links = [
    {
        name: 'Find Coffee',
        path: '/directory'
    },
    {
        name: 'Map',
        path: '/map'
    },
    {
        name: 'Brew Log'
    },
    {
        name: 'Recipes'
    },
    {
        name: 'Blog'
    },
    {
        name: 'Contact',
    }
]

const promptBus = useEventBus('roast-prompt-bus');

const promptLogin = () => {
    promptBus.emit( 'prompt-login' );
}

const promptRegister = () => {
    promptBus.emit( 'prompt-register' );
}
</script>

