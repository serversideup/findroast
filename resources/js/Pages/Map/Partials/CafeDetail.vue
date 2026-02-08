<template>
    <TransitionRoot :show="!!cafe" as="template">
        <Dialog class="relative z-50" @close="$emit('close')">
            <!-- Backdrop -->
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/20" />
            </TransitionChild>

            <!-- Panel -->
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="translate-x-full"
                enter-to="translate-x-0"
                leave="ease-in duration-200"
                leave-from="translate-x-0"
                leave-to="translate-x-full"
            >
                <div class="fixed inset-y-0 right-0 flex max-w-full">
                    <DialogPanel class="w-screen max-w-md bg-white shadow-xl flex flex-col">
                        <!-- Header -->
                        <div class="flex items-start justify-between gap-3 px-4 pt-4 pb-3 border-b border-stone-100 flex-shrink-0">
                            <div class="min-w-0" v-if="cafe">
                                <p class="text-[11px] font-medium text-amber-700 uppercase tracking-wide">
                                    {{ cafe.company?.name }}
                                </p>
                                <h2 class="text-lg font-semibold text-stone-900 leading-tight mt-0.5">
                                    {{ cafe.name }}
                                </h2>
                                <p class="text-sm text-stone-500 mt-0.5">
                                    {{ fullAddress }}
                                </p>
                            </div>
                            <button
                                type="button"
                                @click="$emit('close')"
                                class="p-1.5 text-stone-400 hover:text-stone-600 rounded-lg hover:bg-stone-50 flex-shrink-0"
                            >
                                <XMarkIcon class="h-5 w-5" />
                            </button>
                        </div>

                        <!-- Scrollable Content -->
                        <div v-if="cafe" class="flex-1 overflow-y-auto">
                            <!-- Image -->
                            <div
                                v-if="cafe.primary_image"
                                class="h-48 bg-stone-100"
                            >
                                <img
                                    :src="cafe.primary_image"
                                    :alt="cafe.name"
                                    class="w-full h-full object-cover"
                                />
                            </div>

                            <div class="px-4 py-4 space-y-5">
                                <!-- Get Directions -->
                                <a
                                    :href="directionsUrl"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 px-3 py-2 bg-amber-700 hover:bg-amber-800 text-white text-sm font-medium rounded-lg transition-colors"
                                >
                                    <MapPinIcon class="h-4 w-4" />
                                    Get Directions
                                </a>

                                <!-- Description -->
                                <div v-if="cafe.description">
                                    <p class="text-sm text-stone-600 leading-relaxed">
                                        {{ cafe.description }}
                                    </p>
                                </div>

                                <!-- Brew Methods -->
                                <DetailSection
                                    v-if="cafe.brew_methods?.length"
                                    title="Brew Methods"
                                >
                                    <div class="flex flex-wrap gap-1.5">
                                        <span
                                            v-for="method in cafe.brew_methods"
                                            :key="method.id"
                                            class="inline-flex items-center gap-1 text-xs bg-amber-50 text-amber-800 border border-amber-200 rounded-full px-2.5 py-1"
                                        >
                                            <span v-if="method.icon">{{ method.icon }}</span>
                                            {{ method.name }}
                                        </span>
                                    </div>
                                </DetailSection>

                                <!-- Amenities -->
                                <DetailSection
                                    v-if="cafe.amenities?.length"
                                    title="Amenities"
                                >
                                    <div class="flex flex-wrap gap-1.5">
                                        <span
                                            v-for="amenity in cafe.amenities"
                                            :key="amenity.id"
                                            class="inline-flex items-center gap-1 text-xs bg-stone-50 text-stone-700 border border-stone-200 rounded-full px-2.5 py-1"
                                        >
                                            <span v-if="amenity.icon">{{ amenity.icon }}</span>
                                            {{ amenity.name }}
                                        </span>
                                    </div>
                                </DetailSection>

                                <!-- Drink Options -->
                                <DetailSection
                                    v-if="cafe.drink_options?.length"
                                    title="Drink Options"
                                >
                                    <div class="flex flex-wrap gap-1.5">
                                        <span
                                            v-for="drink in cafe.drink_options"
                                            :key="drink.id"
                                            class="inline-flex items-center gap-1 text-xs bg-stone-50 text-stone-700 border border-stone-200 rounded-full px-2.5 py-1"
                                        >
                                            <span v-if="drink.icon">{{ drink.icon }}</span>
                                            {{ drink.name }}
                                        </span>
                                    </div>
                                </DetailSection>

                                <!-- Yelp -->
                                <div v-if="cafe.yelp_url">
                                    <a
                                        :href="cafe.yelp_url"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-700"
                                    >
                                        <ArrowTopRightOnSquareIcon class="h-3.5 w-3.5" />
                                        View on Yelp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </DialogPanel>
                </div>
            </TransitionChild>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { computed } from 'vue';
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';
import { XMarkIcon, MapPinIcon, ArrowTopRightOnSquareIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    cafe: { type: Object, default: null },
});

defineEmits(['close']);

const fullAddress = computed(() => {
    if (!props.cafe) return '';
    const parts = [
        props.cafe.address,
        props.cafe.city,
        props.cafe.state || props.cafe.province || props.cafe.territory,
        props.cafe.zip,
    ].filter(Boolean);
    return parts.join(', ');
});

const directionsUrl = computed(() => {
    if (!props.cafe) return '#';
    const q = encodeURIComponent(fullAddress.value || `${props.cafe.latitude},${props.cafe.longitude}`);
    return `https://www.google.com/maps/dir/?api=1&destination=${q}`;
});

// Tiny inline component for section headers
const DetailSection = {
    props: ['title'],
    template: `
        <div>
            <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wide mb-2">{{ title }}</h3>
            <slot />
        </div>
    `,
};
</script>
