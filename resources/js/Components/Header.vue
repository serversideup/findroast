<template>
    <div class="bg-white w-full hidden lg:flex items-center justify-between px-5 border-b border-stone-200 z-50 fixed h-16 shadow-sm">
        <div class="flex items-center justify-between mx-auto w-full max-w-screen-xxl">
            <div class="flex items-center w-64">
                <Link href="/" class="transition-opacity hover:opacity-70">
                    <img src="/images/logos/header-logo.svg" class="h-5"/>
                </Link>
            </div>

            <nav class="flex justify-between items-center">
                <ul class="flex items-center space-x-1">
                    <li v-for="link in links" :key="link.name">
                        <Link
                            :href="link.path || '/'"
                            :class="[
                                'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                                isActivePage(link.path)
                                    ? 'text-amber-900 bg-amber-50'
                                    : 'text-stone-700 hover:text-amber-900 hover:bg-stone-50'
                            ]">
                            {{ link.name }}
                        </Link>
                    </li>
                    <li>
                        <a
                            href="https://github.com/serversideup/findroast"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="px-3 py-2 rounded-lg text-stone-700 hover:text-amber-900 hover:bg-stone-50 transition-colors inline-flex items-center"
                            aria-label="GitHub Repository">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                            </svg>
                        </a>
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
            <div v-else class="flex items-center justify-end w-64">
                <UserMenu/>
            </div>
        </div>

        <LoginModal/>
        <RegisterModal/>
    </div>
</template>

<script setup>
import LoginModal from './Auth/LoginModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import RegisterModal from './Auth/RegisterModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import UserMenu from './Header/UserMenu.vue';
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useEventBus } from '@vueuse/core';

const page = usePage();
const user = computed(() => page.props.auth.user);

const links = [
    {
        name: 'Offerings',
        path: '/'
    },
    {
        name: 'Companies',
        path: '/companies'
    },
    {
        name: 'Map',
        path: '/map'
    },
    {
        name: 'Transparency',
        path: '/transparency'
    },
    {
        name: 'Contact',
        path: '/contact'
    },
    {
        name: 'Changelog',
        path: '/changelog'
    },
]

const isActivePage = (path) => {
    if (!path) return false;
    const currentPath = page.url;

    // Exact match for home page
    if (path === '/' && currentPath === '/') {
        return true;
    }

    // For other pages, check if current path starts with the link path
    if (path !== '/' && currentPath.startsWith(path)) {
        return true;
    }

    return false;
}

const promptBus = useEventBus('roast-prompt-bus');

const promptLogin = () => {
    promptBus.emit( 'prompt-login' );
}

const promptRegister = () => {
    promptBus.emit( 'prompt-register' );
}
</script>

