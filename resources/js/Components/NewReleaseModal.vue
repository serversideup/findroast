<template>
    <Teleport to="body">
        <Transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-if="show" class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center px-4 py-6 sm:px-0">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-stone-900/50 backdrop-blur-sm" @click="dismiss"></div>

                <!-- Modal -->
                <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95">
                    <div v-if="show" class="relative w-full sm:max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden">
                        <!-- Amber accent bar -->
                        <div class="h-1 w-full bg-gradient-to-r from-amber-600 to-amber-400"></div>

                        <!-- Header -->
                        <div class="px-6 pt-6 pb-4 flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center">
                                    <SparklesIcon class="h-5 w-5 text-amber-700" />
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide">What's New</p>
                                    <h2 class="text-lg font-bold text-stone-900 leading-tight">
                                        Version {{ entry.version }}
                                    </h2>
                                </div>
                            </div>
                            <button
                                @click="dismiss"
                                class="flex-shrink-0 mt-0.5 rounded-lg p-1.5 text-stone-400 hover:text-stone-600 hover:bg-stone-100 transition-colors">
                                <XMarkIcon class="h-5 w-5" />
                            </button>
                        </div>

                        <!-- Date -->
                        <div class="px-6 pb-2">
                            <time class="text-xs text-stone-500">Released {{ formatDate(entry.date) }}</time>
                        </div>

                        <!-- Description -->
                        <div class="px-6 py-4 border-t border-stone-100">
                            <p class="text-sm text-stone-700 leading-relaxed whitespace-pre-line">{{ entry.description }}</p>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 bg-stone-50 border-t border-stone-200 flex items-center justify-between gap-3">
                            <Link
                                href="/changelog"
                                class="text-sm font-medium text-amber-700 hover:text-amber-800 transition-colors"
                                @click="dismiss">
                                View full changelog →
                            </Link>
                            <button
                                @click="dismiss"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-amber-700 hover:bg-amber-800 rounded-lg transition-colors">
                                Got it
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { SparklesIcon, XMarkIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    entry: {
        type: Object,
        required: true,
    },
});

const COOKIE_NAME = 'changelog_seen';
const show = ref(false);

const getCookie = (name) => {
    const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : null;
};

const setCookie = (name, value, days = 365) => {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/; SameSite=Lax`;
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const dismiss = () => {
    show.value = false;
    setCookie(COOKIE_NAME, String(props.entry.id));
};

onMounted(() => {
    const seen = getCookie(COOKIE_NAME);
    if (seen !== String(props.entry.id)) {
        // Small delay so the page settles before showing the modal
        setTimeout(() => {
            show.value = true;
        }, 800);
    }
});
</script>
