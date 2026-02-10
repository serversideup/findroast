<template>
    <Head :title="cafe.name" />

    <AdminHeader
        :title="cafe.name"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform'},
            { label: 'Cafes', to: '/platform/cafes'},
            { label: cafe.name, to: '#'}
        ]">
        <template #actions>
            <div class="flex items-center gap-3">
                <button
                    @click="confirmDelete"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors shadow-sm">
                    <TrashIcon class="h-4 w-4" />
                    Delete
                </button>
                <PrimaryLink :href="`/platform/cafes/${cafe.id}/edit`">
                    Edit Cafe
                </PrimaryLink>
            </div>
        </template>
    </AdminHeader>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column - Information -->
            <div class="space-y-6">
                <!-- Profile Card -->
                <div class="bg-white rounded-lg border border-stone-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-stone-200">
                        <h3 class="text-base font-semibold text-stone-900">Profile</h3>
                        <p class="mt-1 text-sm text-stone-600">
                            Basic information about the cafe
                        </p>
                    </div>
                    <div class="px-6 py-5">
                        <dl class="space-y-4">
                            <div class="flex justify-between py-3 border-b border-stone-100">
                                <dt class="text-sm font-medium text-stone-700">Status</dt>
                                <dd class="text-sm">
                                    <span
                                        :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            cafe.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-stone-100 text-stone-800'
                                        ]">
                                        {{ cafe.status }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between py-3 border-b border-stone-100">
                                <dt class="text-sm font-medium text-stone-700">Name</dt>
                                <dd class="text-sm text-stone-900">{{ cafe.name }}</dd>
                            </div>
                            <div v-if="cafe.description" class="py-3 border-b border-stone-100">
                                <dt class="text-sm font-medium text-stone-700 mb-2">Description</dt>
                                <dd class="text-sm text-stone-600">{{ cafe.description }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Location Card -->
                <div class="bg-white rounded-lg border border-stone-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-stone-200">
                        <h3 class="text-base font-semibold text-stone-900">Location</h3>
                        <p class="mt-1 text-sm text-stone-600">
                            Physical address information
                        </p>
                    </div>
                    <div class="px-6 py-5">
                        <dl class="space-y-4">
                            <div v-if="cafe.address" class="flex justify-between py-3 border-b border-stone-100">
                                <dt class="text-sm font-medium text-stone-700">Address</dt>
                                <dd class="text-sm text-stone-900">{{ cafe.address }}</dd>
                            </div>
                            <div v-if="cafe.city" class="flex justify-between py-3 border-b border-stone-100">
                                <dt class="text-sm font-medium text-stone-700">City</dt>
                                <dd class="text-sm text-stone-900">{{ cafe.city }}</dd>
                            </div>
                            <div v-if="cafe.country == 'US' && cafe.state" class="flex justify-between py-3 border-b border-stone-100">
                                <dt class="text-sm font-medium text-stone-700">State</dt>
                                <dd class="text-sm text-stone-900">{{ cafe.state }}</dd>
                            </div>
                            <div v-if="cafe.country == 'CA' && cafe.province" class="flex justify-between py-3 border-b border-stone-100">
                                <dt class="text-sm font-medium text-stone-700">Province</dt>
                                <dd class="text-sm text-stone-900">{{ cafe.province }}</dd>
                            </div>
                            <div v-if="cafe.country == 'AU' && cafe.territory" class="flex justify-between py-3 border-b border-stone-100">
                                <dt class="text-sm font-medium text-stone-700">Territory</dt>
                                <dd class="text-sm text-stone-900">{{ cafe.territory }}</dd>
                            </div>
                            <div v-if="cafe.country" class="flex justify-between py-3 border-b border-stone-100">
                                <dt class="text-sm font-medium text-stone-700">Country</dt>
                                <dd class="text-sm text-stone-900">{{ cafe.country }}</dd>
                            </div>
                            <div v-if="cafe.zip" class="flex justify-between py-3 border-b border-stone-100">
                                <dt class="text-sm font-medium text-stone-700">Zip / Postal Code</dt>
                                <dd class="text-sm text-stone-900">{{ cafe.zip }}</dd>
                            </div>
                            <div v-if="cafe.latitude && cafe.longitude" class="flex justify-between py-3">
                                <dt class="text-sm font-medium text-stone-700">Coordinates</dt>
                                <dd class="text-sm text-stone-600 font-mono">{{ cafe.latitude }}, {{ cafe.longitude }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Primary Image Card -->
                <div v-if="cafe.primary_image" class="bg-white rounded-lg border border-stone-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-stone-200">
                        <h3 class="text-base font-semibold text-stone-900">Primary Image</h3>
                    </div>
                    <div class="h-64"
                        :style="{
                            'background-image': 'url('+cafe.primary_image+')',
                            'background-size': 'cover',
                            'background-position': 'center'
                        }">
                    </div>
                </div>

                <!-- Online Presence Card -->
                <div v-if="cafe.yelp_url" class="bg-white rounded-lg border border-stone-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-stone-200">
                        <h3 class="text-base font-semibold text-stone-900">Online Presence</h3>
                    </div>
                    <div class="px-6 py-5">
                        <dl class="space-y-4">
                            <div class="flex justify-between py-3">
                                <dt class="text-sm font-medium text-stone-700">Yelp URL</dt>
                                <dd class="text-sm">
                                    <a :href="cafe.yelp_url" target="_blank" class="text-amber-700 hover:text-amber-800 underline">
                                        View on Yelp
                                    </a>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Amenities Card -->
                <div class="bg-white rounded-lg border border-stone-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-stone-200">
                        <h3 class="text-base font-semibold text-stone-900">Amenities</h3>
                        <p class="mt-1 text-sm text-stone-600">
                            {{ cafe.amenities.length }} amenities offered
                        </p>
                    </div>
                    <div class="px-6 py-5">
                        <div v-if="cafe.amenities.length > 0" class="flex flex-wrap gap-2">
                            <span
                                v-for="amenity in cafe.amenities"
                                :key="amenity.id"
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-stone-100 text-stone-800">
                                {{ amenity.name }}
                            </span>
                        </div>
                        <p v-else class="text-sm text-stone-500">No amenities listed</p>
                    </div>
                </div>

                <!-- Brew Methods Card -->
                <div class="bg-white rounded-lg border border-stone-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-stone-200">
                        <h3 class="text-base font-semibold text-stone-900">Brew Methods</h3>
                        <p class="mt-1 text-sm text-stone-600">
                            {{ cafe.brew_methods.length }} brew methods available
                        </p>
                    </div>
                    <div class="px-6 py-5">
                        <div v-if="cafe.brew_methods.length > 0" class="flex flex-wrap gap-2">
                            <span
                                v-for="brewMethod in cafe.brew_methods"
                                :key="brewMethod.id"
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                {{ brewMethod.name }}
                            </span>
                        </div>
                        <p v-else class="text-sm text-stone-500">No brew methods listed</p>
                    </div>
                </div>

                <!-- Drink Options Card -->
                <div class="bg-white rounded-lg border border-stone-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-stone-200">
                        <h3 class="text-base font-semibold text-stone-900">Drink Options</h3>
                        <p class="mt-1 text-sm text-stone-600">
                            {{ cafe.drink_options.length }} drink options available
                        </p>
                    </div>
                    <div class="px-6 py-5">
                        <div v-if="cafe.drink_options.length > 0" class="flex flex-wrap gap-2">
                            <span
                                v-for="drinkOption in cafe.drink_options"
                                :key="drinkOption.id"
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-stone-100 text-stone-800">
                                {{ drinkOption.name }}
                            </span>
                        </div>
                        <p v-else class="text-sm text-stone-500">No drink options listed</p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Map -->
            <div class="lg:sticky lg:top-6 lg:self-start">
                <div class="bg-white rounded-lg border border-stone-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-stone-200">
                        <h3 class="text-base font-semibold text-stone-900">Map Location</h3>
                        <p class="mt-1 text-sm text-stone-600">
                            Current cafe location
                        </p>
                    </div>
                    <div ref="mapElement" class="h-[600px] bg-stone-100"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <TransitionRoot as="template" :show="showDeleteModal">
        <Dialog as="div" class="relative z-50" @close="showDeleteModal = false">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-stone-900/75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <ExclamationTriangleIcon class="h-6 w-6 text-red-600" aria-hidden="true" />
                                    </div>
                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <DialogTitle as="h3" class="text-base font-semibold leading-6 text-stone-900">
                                            Delete Cafe
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <p class="text-sm text-stone-600">
                                                Are you sure you want to delete <span class="font-semibold">{{ cafe.name }}</span>? This action cannot be undone.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-stone-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                                <button
                                    type="button"
                                    class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 sm:w-auto transition-colors"
                                    @click="deleteCafe"
                                    :disabled="deleteForm.processing">
                                    {{ deleteForm.processing ? 'Deleting...' : 'Delete' }}
                                </button>
                                <button
                                    type="button"
                                    class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-stone-900 shadow-sm ring-1 ring-inset ring-stone-300 hover:bg-stone-50 sm:mt-0 sm:w-auto transition-colors"
                                    @click="showDeleteModal = false"
                                    :disabled="deleteForm.processing">
                                    Cancel
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminHeader from '../Partials/AdminHeader.vue';
import PrimaryLink from '@/Components/PrimaryLink.vue';
import { computed, onMounted, ref } from "vue";
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import { Loader } from "@googlemaps/js-api-loader";
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { ExclamationTriangleIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { useEventBus } from '@vueuse/core';

defineOptions({
    layout: AdminLayout
});

const cafe = computed(() => usePage().props.cafe);
const googlMapsApiKey = computed(() => usePage().props.google_maps_api_key);

const loader = ref(null);

onMounted( async () => {
    loader.value = new Loader({
        apiKey: googlMapsApiKey.value,
        version: "beta"
    });

    await buildMap( cafe.value.latitude, cafe.value.longitude );
});

/**
 * Maps setup
 */
const mapElement = ref(null);

let googleMap = null;
let googleMapMarker = null;

const buildMap = async ( latitude = 39.50, longitude = -98.35, zoom = 5 ) => {
    const { Map } = await loader.value.importLibrary("maps");

    googleMap = new Map( mapElement.value, {
        center: {
            lat: parseFloat(latitude) || 39.50,
            lng: parseFloat(longitude) || -98.35
        },
        mapId: (Math.random() + 1).toString(36).substring(7),
        zoom: latitude && longitude ? 14 : 4,
        zoomControl: true,
        fullscreenControl: false,
        mapTypeControl: false,
        clickableIcons: false,
        streetViewControl: false,
        gestureHandling: 'greedy'
    });

    if (latitude && longitude) {
        setCurrentLocation( latitude, longitude );
    }
}

const setCurrentLocation = async ( latitude, longitude ) => {
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    googleMapMarker = new AdvancedMarkerElement({
        map: googleMap,
        position: {
            lat: parseFloat( latitude ),
            lng: parseFloat( longitude )
        }
    });

    googleMap.panTo( {
        lat: parseFloat( latitude ),
        lng: parseFloat( longitude )
    } );

    googleMap.setZoom( 14 );
}

/**
 * Delete functionality
 */
const showDeleteModal = ref(false);
const deleteForm = useForm({});

const confirmDelete = () => {
    showDeleteModal.value = true;
};

const notificationBus = useEventBus('roast-notification');

const deleteCafe = () => {
    deleteForm.delete(`/platform/cafes/${cafe.value.id}`, {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Cafe deleted successfully'
            });
        }
    });
};
</script>
