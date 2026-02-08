<template>
    <Head title="Cafe Map" />

    <div class="h-[calc(100vh-3.5rem)] lg:h-[calc(100vh-4rem)] flex flex-col bg-stone-50">
        <!-- Filter Bar -->
        <div class="border-b border-stone-200 bg-white/95 backdrop-blur-sm z-30 flex-shrink-0">
            <div class="px-3 sm:px-4 lg:px-0">
                <!-- On desktop, filters sit inside the sidebar width area + spill into map -->
                <div class="lg:flex lg:items-center">
                    <div class="lg:w-96 lg:px-3 lg:border-r lg:border-stone-200 flex-shrink-0">
                        <MapFilters :cafe-count="filteredCafes.length" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Sidebar + Map -->
        <div class="flex-1 flex overflow-hidden relative">
            <!-- Cafe List (desktop sidebar + mobile bottom sheet) -->
            <CafeList :cafes="filteredCafes" />

            <!-- Map -->
            <div class="flex-1 relative">
                <CafeMap
                    :cafes="filteredCafes"
                    :selected-cafe="selectedCafe"
                    :hovered-cafe="hoveredCafe"
                    @select-cafe="selectCafe"
                />
            </div>
        </div>

        <!-- Detail Slide-out -->
        <CafeDetail
            :cafe="selectedCafe"
            @close="clearSelection"
        />
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import { Head } from '@inertiajs/vue3';
import { useCafeMap } from '@/Composables/useCafeMap';
import CafeMap from './Partials/CafeMap.vue';
import CafeList from './Partials/CafeList.vue';
import CafeDetail from './Partials/CafeDetail.vue';
import MapFilters from './Partials/MapFilters.vue';

const {
    filteredCafes,
    selectedCafe,
    hoveredCafe,
    selectCafe,
    clearSelection,
} = useCafeMap();
</script>
