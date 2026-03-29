<template>
    <Head title="Find Your Perfect Roast" />

    <div class="bg-stone-50 min-h-screen">
        <!-- Hero / Search Section -->
        <div class="bg-gradient-to-b from-amber-50/80 to-stone-50 border-b border-stone-200">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 py-4 lg:py-5">
                <h1 class="text-lg sm:text-xl font-bold text-stone-900 tracking-tight">
                    Find Your Perfect Roast
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 mt-0.5">
                    Discover specialty coffees from top roasters worldwide
                </p>

                <!-- Search Bar -->
                <div class="mt-3 max-w-lg">
                    <div class="relative">
                        <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-stone-400" />
                        <input
                            type="text"
                            v-model="form.search"
                            placeholder="Search by name, origin, flavor..."
                            class="w-full pl-9 pr-4 py-2 text-base bg-white border border-stone-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 placeholder:text-stone-400"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Filter Bar -->
        <div class="border-b border-stone-200 bg-white/95 backdrop-blur-sm sticky top-14 lg:top-16 z-40 overflow-visible">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
                <Filters
                    :dailyDigestSubscribed="dailyDigestSubscribed"
                    @subscribe="handleDailyDigestClick"
                />
            </div>
        </div>

        <!-- Active Filters + Results -->
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
            <ActiveFiltersBar />
            <div class="pt-3">
                <Roasts />
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import ActiveFiltersBar from './Partials/ActiveFiltersBar.vue';
import Filters from './Partials/Filters.vue';
import Roasts from './Partials/Roasts.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { useOfferings } from '@/Composables/useOfferings';
import { MagnifyingGlassIcon } from '@heroicons/vue/20/solid';
import { ref, computed } from 'vue';

const props = defineProps({
    dailyDigestSubscribed: {
        type: Boolean,
        default: false
    }
});

const { form } = useOfferings();
const page = usePage();
const isSubscribing = ref(false);
const dailyDigestSubscribed = ref(props.dailyDigestSubscribed);

const handleDailyDigestClick = () => {
    if (dailyDigestSubscribed.value) {
        // Already subscribed, show a message
        alert('You are already subscribed to the daily digest! Check your email at 8 AM for new coffees.');
        return;
    }

    // Check if user is authenticated
    if (!page.props.auth.user) {
        // Redirect to login
        router.visit(route('login'));
        return;
    }

    // Subscribe
    isSubscribing.value = true;
    router.post(route('daily-digest.subscribe'), {}, {
        preserveScroll: true,
        onSuccess: () => {
            dailyDigestSubscribed.value = true;
            isSubscribing.value = false;
        },
        onError: () => {
            isSubscribing.value = false;
        }
    });
};
</script>
