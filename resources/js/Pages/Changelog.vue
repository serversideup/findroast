<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';

const entries = computed(() => usePage().props.entries);

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Changelog" />

        <div class="min-h-screen bg-stone-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <!-- Header -->
                <div class="mb-12">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-8 w-1 bg-amber-700 rounded-full"></div>
                        <span class="text-sm font-semibold text-amber-700 uppercase tracking-wide">Release History</span>
                    </div>
                    <h1 class="text-4xl font-bold text-stone-900">Changelog</h1>
                    <p class="mt-3 text-lg text-stone-600">
                        Stay up to date with everything new on FindRoast — new features, improvements, and fixes.
                    </p>
                </div>

                <!-- No entries state -->
                <div v-if="entries.length === 0" class="text-center py-16 bg-white rounded-xl border border-stone-200">
                    <div class="text-4xl mb-4">☕</div>
                    <p class="text-stone-500 text-lg">No releases yet. Check back soon!</p>
                </div>

                <!-- Timeline -->
                <div v-else class="relative">
                    <!-- Vertical line -->
                    <div class="absolute left-0 top-0 bottom-0 w-px bg-stone-200 ml-[11px]"></div>

                    <div class="space-y-10">
                        <div
                            v-for="(entry, index) in entries"
                            :key="entry.id"
                            class="relative pl-10">
                            <!-- Timeline dot -->
                            <div class="absolute left-0 top-1.5 flex items-center justify-center w-6 h-6 rounded-full border-2 border-amber-700 bg-white">
                                <div class="w-2 h-2 rounded-full bg-amber-700"></div>
                            </div>

                            <!-- Card -->
                            <div class="bg-white rounded-xl border border-stone-200 shadow-sm overflow-hidden">
                                <!-- Card header -->
                                <div class="px-6 py-4 border-b border-stone-100 flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-bold bg-amber-100 text-amber-800">
                                            {{ entry.version }}
                                        </span>
                                        <span v-if="index === 0" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            Latest
                                        </span>
                                    </div>
                                    <time class="text-sm text-stone-500">
                                        {{ formatDate(entry.date) }}
                                    </time>
                                </div>

                                <!-- Card body -->
                                <div class="px-6 py-5">
                                    <p class="text-stone-700 text-sm leading-relaxed whitespace-pre-line">{{ entry.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
