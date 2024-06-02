<template>
    <Head :title="cafe.name+' - '+company.name" />

    <AdminHeader 
        :title="company.name"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform'},
            { label: 'Companies', to: '/platform/companies'},
            { label: company.name, to: '/platform/companies/'+company.id},
            { label: 'Cafes', to: '/platform/companies/'+company.id+'/cafes'},
            { label: cafe.name, to: '#'}
        ]">
        <template #actions>
            <PrimaryLink :href="'/platform/companies/'+company.id+'/cafes/'+cafe.id+'/edit'">
                Edit
            </PrimaryLink>
        </template>
    </AdminHeader>

    <div class="max-w-screen-xl mx-auto mt-5 lg:px-8">
        <div class="grid grid-cols-2 gap-x-8">
            <div class="px-4 sm:px-0">
                <h3 class="text-base font-semibold leading-7 text-gray-900">
                    Profile
                </h3>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                    Basic information regarding the cafe.
                </p>
                <div class="mt-6 border-t border-gray-100">
                    <dl class="divide-y divide-gray-100">
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Status
                            </dt>
                            <dd
                                class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ cafe.status }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Name
                            </dt>
                            <dd
                                class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ cafe.name }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Description
                            </dt>
                            <dd
                                class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ cafe.description }}
                            </dd>
                        </div>
                        
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Country
                            </dt>
                            <dd
                                class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ cafe.country }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Address
                            </dt>
                            <dd
                                class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ cafe.address }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                City
                            </dt>
                            <dd
                                class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ cafe.city }}
                            </dd>
                        </div>
                        <div v-if="cafe.country == 'United States'" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                State
                            </dt>
                            <dd
                                class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ cafe.state }}
                            </dd>
                        </div>
                        <div v-if="cafe.country == 'Canada'" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Province
                            </dt>
                            <dd
                                class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ cafe.province }}
                            </dd>
                        </div>
                        <div v-if="cafe.country == 'Australia'" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Territory
                            </dt>
                            <dd
                                class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ cafe.territory }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Zip
                            </dt>
                            <dd
                                class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ cafe.zip }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Coordinates
                            </dt>
                            <dd
                                class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ cafe.latitude }}, {{ cafe.longitude }}
                            </dd>
                        </div>
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0" v-if="cafe.primary_image">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Primary Image
                            </dt>
                            <dd
                                class="mt-1 sm:mt-0 sm:col-span-2">
                                <div class="h-[190px] rounded-lg" :style="{
                                    'background-image': 'url('+cafe.primary_image+')',
                                    'background-size': 'cover',
                                    'background-position': 'center'
                                }"></div>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            <div ref="mapElement">
                
            </div>
        </div>
        <div class="grid grid-cols-2 gap-x-8 mt-8">
            <div class="px-4 sm:px-0">
                <h3 class="text-base font-semibold leading-7 text-gray-900">
                    Online presence
                </h3>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                    Where can this cafe be found online?
                </p>
                <div class="mt-6 border-t border-gray-100">
                    <dl class="divide-y divide-gray-100">
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">
                                Yelp URL
                            </dt>
                            <dd
                                class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ cafe.yelp_url }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-x-8 mt-8">
            <div class="px-4 sm:px-0">
                <h3 class="text-base font-semibold leading-7 text-gray-900">
                    Amenities
                </h3>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                    Amenities offered by this cafe.
                </p>
                <div class="mt-6 border-t border-gray-100">
                    <ul class="mt-2 text-sm text-gray-600 list-disc pl-8">
                        <li v-for="amenity in cafe.amenities">
                            {{ amenity.name }}
                        </li>
                    </ul>
                    <div v-if="cafe.amenities.length == 0">
                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            No amenities listed.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-x-8 mt-8">
            <div class="px-4 sm:px-0">
                <h3 class="text-base font-semibold leading-7 text-gray-900">
                    Brew Methods
                </h3>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                    How this cafe brews their coffee.
                </p>
                <div class="mt-6 border-t border-gray-100">
                    <ul class="mt-2 text-sm text-gray-600 list-disc pl-8">
                        <li v-for="brewMethod in cafe.brew_methods">
                            {{ brewMethod.name }}
                        </li>
                    </ul>
                    <div v-if="cafe.brew_methods.length == 0">
                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            No brew methods listed.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-x-8 mt-8">
            <div class="px-4 sm:px-0">
                <h3 class="text-base font-semibold leading-7 text-gray-900">
                    Drink Options
                </h3>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                    Besides delicious coffee, here are the other drink options.
                </p>
                <div class="mt-6 border-t border-gray-100">
                    <ul class="mt-2 text-sm text-gray-600 list-disc pl-8">
                        <li v-for="drinkOption in cafe.drink_options">
                            {{ drinkOption.name }}
                        </li>
                    </ul>
                    <div v-if="cafe.drink_options.length == 0">
                        <p class="mt-2 text-sm leading-6 text-gray-600">
                            No drink options listed.
                        </p>
                    </div>
                </div>
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
import AdminHeader from '../Partials/AdminHeader.vue';
import PrimaryLink from '@/Components/PrimaryLink.vue';
import SecondaryLink from '@/Components/SecondaryLink.vue';
import { computed, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import { Loader } from "@googlemaps/js-api-loader";

const company = computed(() => usePage().props.company);
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
            lat: latitude, 
            lng: longitude
        },
        mapId: (Math.random() + 1).toString(36).substring(7),
        zoom: 4,
        zoomControl: false,
        fullscreenControl: false,
        mapTypeControl: false,
        clickableIcons: false,
        streetViewControl: false,
        gestureHandling: 'greedy'
    });

    setCurrentLocation( latitude, longitude );
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
</script>