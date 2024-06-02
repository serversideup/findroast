<template>
    <Head :title="'Create Cafe - ' +company.name" />

    <AdminHeader 
        :title="'Create Cafe'"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform' },
            { label: 'Companies', to: '/platform/companies' },
            { label: company.name, to: '/platform/companies/'+company.id },
            { label: 'Cafes', to: '/platform/companies/'+company.id+'/cafes' },
            { label: 'Create Cafe', to: '#' }
        ]">
    </AdminHeader>

    <div class="max-w-screen-xl mx-auto mt-5 lg:px-8">
        <form @submit.prevent="submit" @keydown.enter.prevent>
            <div class="grid grid-cols-2 gap-x-8">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">
                            Profile
                        </h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">
                            Basic information regarding the cafe.
                        </p>

                        <div
                            class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <InputLabel value="Status"/>
                                <select
                                    v-model="form.status"
                                    class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                                    <option value="draft">Draft</option>
                                    <option value="active">Active</option>
                                </select>
                            </div>

                            <div class="sm:col-span-4" v-show="!enterAddress">
                                <InputLabel value="Find on Google"/>
                                <div class="mt-1">
                                    <TextInput
                                        class="block w-full"
                                        ref="placesInput"
                                        v-model="placesSearch"/>
                                </div>
                                <button class="text-sm underline" type="button" @click="enterAddress = true">Can't find what you are looking for? Enter manually</button>
                            </div>

                            <div class="sm:col-span-6 p-4 rounded border border-gray-300 bg-gray-50" v-show="placeFound">
                                <dl class="divide-y divide-gray-100">
                                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-gray-900">Name</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ form.name }}</dd>
                                    </div>
                                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-gray-900">Country</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ form.country }}</dd>
                                    </div>
                                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-gray-900">Address</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ form.address }}</dd>
                                    </div>
                                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-gray-900">City</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ form.city}}</dd>
                                    </div>
                                    <div v-if="form.country == 'United States'" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-gray-900">State</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ form.state }}</dd>
                                    </div>
                                    <div v-if="form.country == 'Canada'" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-gray-900">Province</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ form.province }}</dd>
                                    </div>
                                    <div v-if="form.country == 'Australia'" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-gray-900">Territory</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ form.territory }}</dd>
                                    </div>
                                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-gray-900">Zip</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ form.zip }}</dd>
                                    </div>
                                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                        <dt class="text-sm font-medium leading-6 text-gray-900">Coordinates</dt>
                                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ form.latitude }}, {{ form.longitude }}</dd>
                                    </div>
                                </dl>
                            </div>

                           <div class="sm:col-span-4" v-show="enterAddress">
                                <InputLabel value="Cafe Name"/>
                                <TextInput
                                    class="mt-1 block w-full"
                                    id="name"
                                    v-model="form.name"
                                    required/>
                            </div>

                            <div class="col-span-full">
                                <InputLabel value="Description"/>
                                <div class="mt-2">
                                    <TextAreaInput
                                        class="block w-full"
                                        id="description"
                                        v-model="form.description"/>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-gray-600">
                                    Write a short description for the company.
                                </p>
                            </div>

                            <div class="col-span-full">
                                <InputLabel value="Primary Image"/>
                                <div
                                    class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                                    <div class="text-center">
                                        <PhotoIcon
                                            class="mx-auto h-12 w-12 text-gray-300"
                                            aria-hidden="true" />
                                        <div
                                            class="mt-4 flex text-sm leading-6 text-gray-600">
                                            <label
                                                for="cafe-primary-image"
                                                class="relative cursor-pointer rounded-md bg-white font-semibold text-gray-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-gray-600 focus-within:ring-offset-2 hover:text-gray-500">
                                                <span>Upload a file</span>
                                                <input
                                                    @change="handlePrimaryImageChange( $event )"
                                                    accept="image/*" 
                                                    id="cafe-primary-image"
                                                    name="cafe-primary-image"
                                                    type="file"
                                                    class="sr-only"
                                                    ref="primaryImageFile" />
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs leading-5 text-gray-600">
                                            PNG, JPG, GIF up to 10MB
                                        </p>
                                    </div>
                                </div>

                                <div v-if="primaryImageState == 'selected-primary-image'" class="mt-2 h-[190px] rounded-lg"
                                    :style="{
                                        'background-image': 'url('+primaryImagePreview+')',
                                        'background-size': 'cover',
                                        'background-position': 'center'
                                    }">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div ref="mapElement" v-show="!enterAddress">
                    
                </div>
            </div>

            <div class="grid grid-cols-2 gap-x-8 pt-12" v-show="enterAddress">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">
                            Location Information
                        </h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">
                            Where is this cafe Located?
                        </p>

                        <div
                            class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            
                            <div class="sm:col-span-4">
                                <InputLabel value="Country"/>
                
                                <select
                                    v-model="form.country"
                                    class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                                    <option value=""></option>
                                    <option v-for="country in countries"
                                        :key="country.abbr"
                                        :value="country.abbr"
                                        v-text="country.name"></option>
                                </select>
                            </div>

                            <div class="sm:col-span-4">
                                <InputLabel value="Address"/>
                                <div class="mt-1">
                                    <TextInput
                                        class="block w-full"
                                        id="address"
                                        v-model="form.address"/>
                                </div>
                            </div>

                            <div class="sm:col-span-4">
                                <InputLabel value="City"/>
                                <div class="mt-1">
                                    <TextInput
                                        class="block w-full"
                                        id="city"
                                        v-model="form.city"/>
                                </div>
                            </div>

                            <div class="sm:col-span-2" v-if="form.country == 'US'">
                                <InputLabel value="State"/>
                                <select
                                    v-model="form.state"
                                    class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                                    <option value=""></option>
                                    <option v-for="state in states"
                                        :key="state.abbr"
                                        :value="state.abbr"
                                        v-text="state.name"></option>
                                </select>
                            </div>

                            <div class="sm:col-span-2" v-if="form.country == 'AU'">
                                <InputLabel value="Territory"/>
                                <select
                                    v-model="form.territory"
                                    class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                                    <option value=""></option>
                                    <option v-for="territory in territories"
                                        :key="territory.abbr"
                                        :value="territory.abbr"
                                        v-text="territory.name"></option>
                                </select>
                            </div>

                            <div class="sm:col-span-2" v-if="form.country == 'CA'">
                                <InputLabel value="Province"/>
                                <select
                                    v-model="form.province"
                                    class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                                    <option value=""></option>
                                    <option v-for="province in provinces"
                                        :key="province.abbr"
                                        :value="province.abbr"
                                        v-text="province.name"></option>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel value="Zip"/>
                                <div class="mt-1">
                                    <TextInput
                                        class="block w-full"
                                        id="zip"
                                        v-model="form.zip"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-x-8 pt-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Online presence
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Where can this cafe be found online?
                    </p>

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <div class="sm:col-span-4">
                            <InputLabel value="Yelp"/>
                            <TextInput
                                class="mt-1 block w-full"
                                id="name"
                                v-model="form.yelp_url"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-x-8 pt-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Amenities
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        What amenities does this cafe offer?
                    </p>

                    <div class="mt-10">
                        <fieldset>
                            <div class="mt-6 space-y-6">
                                <div class="relative flex gap-x-3" v-for="amenity in amenities">
                                    <div class="flex h-6 items-center">
                                        <input v-model="form.amenities" :id="'amenity-'+amenity.id" :value="amenity.id" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-gray-600 focus:ring-gray-600" />
                                    </div>
                                    <div class="text-sm leading-6">
                                        <label :for="'amenity-'+amenity.id" class="font-medium text-gray-900">{{ amenity.name }}</label>
                                        <p class="text-gray-500">{{ amenity.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-x-8 pt-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Brew Methods
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        What brew methods are offered at this cafe?
                    </p>

                    <div class="mt-10">
                        <fieldset>
                            <div class="mt-6 space-y-6">
                                <div class="relative flex gap-x-3" v-for="brewMethod in brewMethods">
                                    <div class="flex h-6 items-center">
                                        <input v-model="form.brew_methods" :id="'brew-method-'+brewMethod.id" :value="brewMethod.id" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-gray-600 focus:ring-gray-600" />
                                    </div>
                                    <div class="text-sm leading-6">
                                        <label :for="'brew-method-'+brewMethod.id" class="font-medium text-gray-900">{{ brewMethod.name }}</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-x-8 pt-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Drink Options
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                       Besides delicious coffee, what are other drink options offered at this cafe?
                    </p>

                    <div class="mt-10">
                        <fieldset>
                            <div class="mt-6 space-y-6">
                                <div class="relative flex gap-x-3" v-for="drinkOption in drinkOptions">
                                    <div class="flex h-6 items-center">
                                        <input v-model="form.drink_options" :id="'drink-option-'+drinkOption.id" :value="drinkOption.id" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-gray-600 focus:ring-gray-600" />
                                    </div>
                                    <div class="text-sm leading-6">
                                        <label :for="'drink-option-'+drinkOption.id" class="font-medium text-gray-900">{{ drinkOption.name }}</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>

            <div class="my-6 flex items-center justify-end gap-x-3">
                <SecondaryLink :href="'/platform/companies'">
                    Cancel
                </SecondaryLink>
                <PrimaryButton
                    type="submit">
                    Save
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryLink from '@/Components/SecondaryLink.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import TextInput from '@/Components/TextInput.vue';
import AdminHeader from '../Partials/AdminHeader.vue';
import { useCountries } from '@/Composables/useCountries';
import { useProvinces } from '@/Composables/useProvinces';
import { useStates } from '@/Composables/useStates';
import { useTerritories } from '@/Composables/useTerritories';
import { computed, onMounted, ref } from "vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { PhotoIcon } from "@heroicons/vue/24/solid";
import { useEventBus } from '@vueuse/core';
import { Loader } from "@googlemaps/js-api-loader";

const company = computed(() => usePage().props.company);
const brewMethods = computed(() => usePage().props.brewMethods);
const amenities = computed(() => usePage().props.amenities);
const drinkOptions = computed(() => usePage().props.drinkOptions);
const googlMapsApiKey = computed(() => usePage().props.google_maps_api_key);

const form = useForm({
    name: '',
    status: 'draft',
    primary_image: '',
    description: '',
    country: '',
    address: '',
    city: '',
    state: '',
    province: '',
    territory: '',
    zip: '',
    latitude: '',
    longitude: '',
    yelp_url: '',
    google_place_id: '',
    brew_methods: [],
    amenities: [],
    drink_options: [],
});

const countries = useCountries();
const states = useStates();
const provinces = useProvinces();
const territories = useTerritories();

const loader = ref(null);

onMounted( async () => {
    loader.value = new Loader({
        apiKey: googlMapsApiKey.value,
        version: "beta"
    });

    await configurePlaces();
    await buildMap();
});

/**
 * Primary Image
 */
const primaryImageFile = ref(null);
const primaryImagePreview = ref(null);

const primaryImageState = computed(() => {
    if( form.primary_image != '' ){
        return 'selected-primary-image';
    }

    return 'no-primary-image';
});

const handlePrimaryImageChange = (event) => {
    form.primary_image = event.target.files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function(){
        primaryImagePreview.value = reader.result;
    }, false);

    if( form.primary_image ){
        if ( /\.(jpe?g|png|gif)$/i.test( form.primary_image.name ) ) {
            reader.readAsDataURL( form.primary_image );
        }
    }
};

/**
 * Places setup
 */
const enterAddress = ref(false);
const placesInput = ref(null);
const placesSearch = ref('');

const configurePlaces = async () => {
    const Places = await loader.value.importLibrary("places");

    const googlePlacesAutoComplete = new Places.Autocomplete(placesInput.value.$refs.input, {
        types: ['establishment'],
        fields: [
            'place_id', 
            'name', 
            'address_components',
            'geometry',
            'opening_hours'
        ]
    });

    googlePlacesAutoComplete.addListener('place_changed', () => {
        const place = googlePlacesAutoComplete.getPlace();
        
        setPlaceData( place );
    });
}

const placeFound = ref(false);

const setPlaceData = (place) => {
    placeFound.value = true;

    form.google_place_id = place.place_id;
    form.name = place.name;
    form.latitude = place.geometry.location.lat();
    form.longitude = place.geometry.location.lng();
    form.country = findCountry(place.address_components);
    form.address = findAddress(place.address_components);
    form.city = findCity(place.address_components);

    if( form.country == 'United States' ){
        form.state = findState(place.address_components);
    }

    if( form.country == 'Canada' ){
        form.province = findProvince(place.address_components);
    }

    if( form.country == 'Australia' ){
        form.territory = findTerritory(place.address_components);
    }
    
    form.zip = findZip(place.address_components);
    setCurrentLocation( place.geometry.location.lat(), place.geometry.location.lng() );
}

const findCountry = (addressComponents) => {
    return addressComponents.find( component => component.types.includes('country') ).long_name;
}

const findAddress = (addressComponents) => {
    return addressComponents.find( component => component.types.includes('street_number') ).long_name + ' ' + addressComponents.find( component => component.types.includes('route') ).long_name;
}

const findCity = (addressComponents) => {
    return addressComponents.find( component => component.types.includes('locality') ).long_name;
}

const findState = (addressComponents) => {
    return addressComponents.find( component => component.types.includes('administrative_area_level_1') ).long_name;
}

const findProvince = (addressComponents) => {
    return addressComponents.find( component => component.types.includes('administrative_area_level_1') ).long_name;
}

const findTerritory = (addressComponents) => {
    return addressComponents.find( component => component.types.includes('administrative_area_level_1') ).long_name;
}

const findZip = (addressComponents) => {
    return addressComponents.find( component => component.types.includes('postal_code') ).long_name;
}


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
        zoomControl: true,
        fullscreenControl: false,
        mapTypeControl: false,
        clickableIcons: false,
        streetViewControl: false,
        gestureHandling: 'greedy'
    });
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

const notificationBus = useEventBus('roast-notification');

const submit = () => {
    form.post('/platform/companies/'+company.value.id+'/cafes', {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Cafe created successfully'
            });
        }
    });
};
</script>
