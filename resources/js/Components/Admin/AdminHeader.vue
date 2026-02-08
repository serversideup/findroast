<template>
    <div class="bg-white w-full flex items-center justify-between px-4 border-b border-stone-200 z-50 fixed h-14">
        <div class="flex items-center gap-4">
            <button
                @click="$emit('toggle-sidebar')"
                class="p-2 hover:bg-stone-100 rounded-lg transition-colors"
            >
                <Bars3Icon class="h-5 w-5 text-stone-600" />
            </button>

            <div class="flex items-center gap-2">
                <img src="/images/logos/header-logo.svg" class="h-4"/>
                <span class="text-xs font-medium text-stone-500">Admin</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <Link
                href="/"
                class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-amber-700 hover:bg-amber-50 rounded-lg transition-colors"
            >
                <ArrowTopRightOnSquareIcon class="h-4 w-4" />
                View Site
            </Link>

            <div class="h-6 w-px bg-stone-200"></div>

            <UserMenu />
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { Bars3Icon, ArrowTopRightOnSquareIcon } from '@heroicons/vue/24/outline';
import UserMenu from '@/Components/Header/UserMenu.vue';

defineEmits(['toggle-sidebar']);
</script>
