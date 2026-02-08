<template>
    <button
        type="button"
        @click="$emit('select')"
        @mouseenter="$emit('hover')"
        @mouseleave="$emit('unhover')"
        :class="[
            'w-full text-left flex gap-3 p-3 border-l-2 transition-colors rounded-r-lg',
            active
                ? 'bg-amber-50 border-amber-400'
                : 'bg-white border-transparent hover:bg-stone-50'
        ]"
    >
        <!-- Thumbnail -->
        <div
            v-if="cafe.primary_image"
            class="w-14 h-14 rounded-lg bg-stone-100 flex-shrink-0 overflow-hidden"
        >
            <img
                :src="cafe.primary_image"
                :alt="cafe.name"
                class="w-full h-full object-cover"
            />
        </div>
        <div
            v-else
            class="w-14 h-14 rounded-lg bg-stone-100 flex-shrink-0 flex items-center justify-center"
        >
            <MapPinIcon class="h-5 w-5 text-stone-300" />
        </div>

        <!-- Info -->
        <div class="flex-1 min-w-0">
            <p class="text-[11px] font-medium text-amber-700 uppercase tracking-wide leading-none">
                {{ cafe.company?.name }}
            </p>
            <h3 class="text-sm font-semibold text-stone-900 leading-tight truncate mt-0.5">
                {{ cafe.name }}
            </h3>
            <p class="text-xs text-stone-500 truncate mt-0.5">
                {{ locationText }}
            </p>
            <!-- Brew method pills -->
            <div v-if="cafe.brew_methods?.length" class="flex flex-wrap gap-0.5 mt-1.5">
                <span
                    v-for="method in cafe.brew_methods.slice(0, 3)"
                    :key="method.id"
                    class="text-[10px] bg-amber-50 text-amber-800 border border-amber-200 rounded-full px-1.5 py-0.5 leading-none"
                >
                    {{ method.name }}
                </span>
                <span
                    v-if="cafe.brew_methods.length > 3"
                    class="text-[10px] text-stone-400 px-1 py-0.5 leading-none"
                >
                    +{{ cafe.brew_methods.length - 3 }}
                </span>
            </div>
        </div>
    </button>
</template>

<script setup>
import { computed } from 'vue';
import { MapPinIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    cafe: { type: Object, required: true },
    active: { type: Boolean, default: false },
});

defineEmits(['select', 'hover', 'unhover']);

const locationText = computed(() => {
    const parts = [props.cafe.city, props.cafe.state || props.cafe.province].filter(Boolean);
    return parts.join(', ');
});
</script>
