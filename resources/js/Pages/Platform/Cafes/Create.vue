<template>
    <Head title="Create Cafe" />

    <AdminHeader
        title="Create Cafe"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform' },
            { label: 'Cafes', to: '/platform/cafes' },
            { label: 'Create Cafe', to: '#' }
        ]">
    </AdminHeader>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form @submit.prevent="submit" @keydown.enter.prevent>
            <!-- Main Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column - Form Fields -->
                <div class="space-y-6">
                    <!-- Profile Card -->
                    <div class="bg-white rounded-lg border border-stone-200 shadow-sm">
                        <div class="px-6 py-4 border-b border-stone-200">
                            <h3 class="text-base font-semibold text-stone-900">Profile</h3>
                            <p class="mt-1 text-sm text-stone-600">
                                Basic information regarding the cafe
                            </p>
                        </div>
                        <div class="px-6 py-5 space-y-5">
                            <!-- Company and Status -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel value="Company"/>
                                    <select
                                        v-model="form.company_id"
                                        class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm"
                                        required>
                                        <option value="">Select a company...</option>
                                        <option v-for="company in companies" :key="company.id" :value="company.id">
                                            {{ company.name }}
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <InputLabel value="Status"/>
                                    <select
                                        v-model="form.status"
                                        class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                        <option value="draft">Draft</option>
                                        <option value="active">Active</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Google Places Search -->
                            <div v-show="!enterAddress">
                                <InputLabel value="Find on Google"/>
                                <TextInput
                                    class="mt-1.5 block w-full"
                                    ref="placesInput"
                                    v-model="placesSearch"
                                    placeholder="Search for a cafe..."/>
                                <button
                                    class="mt-2 text-xs text-amber-700 hover:text-amber-800 underline"
                                    type="button"
                                    @click="enterAddress = true">
                                    Can't find what you're looking for? Enter manually
                                </button>
                            </div>

                            <!-- Place Found Preview -->
                            <div v-show="placeFound" class="p-4 rounded-lg border border-amber-200 bg-amber-50">
                                <h4 class="text-sm font-medium text-amber-900 mb-3">Location Found</h4>
                                <dl class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <dt class="font-medium text-amber-900">Name:</dt>
                                        <dd class="text-amber-800">{{ form.name }}</dd>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <dt class="font-medium text-amber-900">Country:</dt>
                                        <dd class="text-amber-800">{{ form.country }}</dd>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <dt class="font-medium text-amber-900">Address:</dt>
                                        <dd class="text-amber-800">{{ form.address }}</dd>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <dt class="font-medium text-amber-900">City:</dt>
                                        <dd class="text-amber-800">{{ form.city}}</dd>
                                    </div>
                                    <div v-if="form.country == 'United States'" class="flex justify-between text-sm">
                                        <dt class="font-medium text-amber-900">State:</dt>
                                        <dd class="text-amber-800">{{ form.state }}</dd>
                                    </div>
                                    <div v-if="form.country == 'Canada'" class="flex justify-between text-sm">
                                        <dt class="font-medium text-amber-900">Province:</dt>
                                        <dd class="text-amber-800">{{ form.province }}</dd>
                                    </div>
                                    <div v-if="form.country == 'Australia'" class="flex justify-between text-sm">
                                        <dt class="font-medium text-amber-900">Territory:</dt>
                                        <dd class="text-amber-800">{{ form.territory }}</dd>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <dt class="font-medium text-amber-900">Coordinates:</dt>
                                        <dd class="text-amber-800">{{ form.latitude }}, {{ form.longitude }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Manual Entry Name -->
                            <div v-show="enterAddress">
                                <InputLabel value="Cafe Name"/>
                                <TextInput
                                    class="mt-1.5 block w-full"
                                    id="name"
                                    v-model="form.name"
                                    placeholder="Enter cafe name"
                                    required/>
                            </div>

                            <!-- Description -->
                            <div>
                                <InputLabel value="Description"/>
                                <TextAreaInput
                                    class="mt-1.5 block w-full"
                                    id="description"
                                    rows="3"
                                    v-model="form.description"
                                    placeholder="Write a short description..."/>
                                <p class="mt-1.5 text-xs text-stone-500">
                                    A brief overview of this cafe location
                                </p>
                            </div>

                            <!-- Primary Image -->
                            <div>
                                <InputLabel value="Primary Image"/>

                                <!-- Preview if exists -->
                                <div v-if="primaryImageState == 'selected-primary-image'" class="mt-2 h-40 rounded-lg overflow-hidden border-2 border-stone-200"
                                    :style="{
                                        'background-image': 'url('+primaryImagePreview+')',
                                        'background-size': 'cover',
                                        'background-position': 'center'
                                    }">
                                </div>

                                <!-- Upload area -->
                                <div
                                    class="mt-2 flex justify-center rounded-lg border-2 border-dashed border-stone-300 px-6 py-8 hover:border-amber-400 transition-colors"
                                    :class="{ 'mt-3': primaryImageState !== 'no-primary-image' }">
                                    <div class="text-center">
                                        <PhotoIcon
                                            class="mx-auto h-10 w-10 text-stone-400"
                                            aria-hidden="true" />
                                        <div
                                            class="mt-3 flex text-sm text-stone-600">
                                            <label
                                                for="cafe-primary-image"
                                                class="relative cursor-pointer rounded-md font-semibold text-amber-700 hover:text-amber-800">
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
                                        <p class="text-xs text-stone-500 mt-1">
                                            PNG, JPG, GIF up to 10MB
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information Card (Manual Entry) -->
                    <div v-show="enterAddress" class="bg-white rounded-lg border border-stone-200 shadow-sm">
                        <div class="px-6 py-4 border-b border-stone-200">
                            <h3 class="text-base font-semibold text-stone-900">Location Information</h3>
                            <p class="mt-1 text-sm text-stone-600">
                                Where is this cafe located?
                            </p>
                        </div>
                        <div class="px-6 py-5 space-y-5">
                            <!-- Country and Address -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <InputLabel value="Country"/>
                                    <select
                                        v-model="form.country"
                                        class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                        <option value="">Select a country...</option>
                                        <option v-for="country in countries"
                                            :key="country.abbr"
                                            :value="country.abbr"
                                            v-text="country.name"></option>
                                    </select>
                                </div>

                                <div class="sm:col-span-2">
                                    <InputLabel value="Street Address"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        id="address"
                                        v-model="form.address"
                                        placeholder="123 Main St"/>
                                </div>

                                <div>
                                    <InputLabel value="City"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        id="city"
                                        v-model="form.city"
                                        placeholder="Enter city"/>
                                </div>

                                <div v-if="form.country == 'US'">
                                    <InputLabel value="State"/>
                                    <select
                                        v-model="form.state"
                                        class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                        <option value="">Select state...</option>
                                        <option v-for="state in states"
                                            :key="state.abbr"
                                            :value="state.abbr"
                                            v-text="state.name"></option>
                                    </select>
                                </div>

                                <div v-if="form.country == 'AU'">
                                    <InputLabel value="Territory"/>
                                    <select
                                        v-model="form.territory"
                                        class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                        <option value="">Select territory...</option>
                                        <option v-for="territory in territories"
                                            :key="territory.abbr"
                                            :value="territory.abbr"
                                            v-text="territory.name"></option>
                                    </select>
                                </div>

                                <div v-if="form.country == 'CA'">
                                    <InputLabel value="Province"/>
                                    <select
                                        v-model="form.province"
                                        class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                        <option value="">Select province...</option>
                                        <option v-for="province in provinces"
                                            :key="province.abbr"
                                            :value="province.abbr"
                                            v-text="province.name"></option>
                                    </select>
                                </div>

                                <div>
                                    <InputLabel value="Zip / Postal Code"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        id="zip"
                                        v-model="form.zip"
                                        placeholder="12345"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Online Presence Card -->
                    <div class="bg-white rounded-lg border border-stone-200 shadow-sm">
                        <div class="px-6 py-4 border-b border-stone-200">
                            <h3 class="text-base font-semibold text-stone-900">Online Presence</h3>
                            <p class="mt-1 text-sm text-stone-600">
                                Where can this cafe be found online?
                            </p>
                        </div>
                        <div class="px-6 py-5 space-y-5">
                            <div>
                                <InputLabel value="Yelp URL"/>
                                <TextInput
                                    class="mt-1.5 block w-full"
                                    id="yelp"
                                    v-model="form.yelp_url"
                                    placeholder="https://yelp.com/biz/..."/>
                            </div>
                        </div>
                    </div>

                    <!-- Amenities Card -->
                    <div class="bg-white rounded-lg border border-stone-200 shadow-sm">
                        <div class="px-6 py-4 border-b border-stone-200">
                            <h3 class="text-base font-semibold text-stone-900">Amenities</h3>
                            <p class="mt-1 text-sm text-stone-600">
                                What amenities does this cafe offer?
                            </p>
                        </div>
                        <div class="px-6 py-5">
                            <fieldset>
                                <div class="space-y-4">
                                    <div class="flex items-start gap-3" v-for="amenity in amenities" :key="amenity.id">
                                        <div class="flex h-5 items-center">
                                            <input
                                                v-model="form.amenities"
                                                :id="'amenity-'+amenity.id"
                                                :value="amenity.id"
                                                type="checkbox"
                                                class="h-4 w-4 rounded border-stone-300 text-amber-700 focus:ring-amber-500" />
                                        </div>
                                        <div class="text-sm">
                                            <label :for="'amenity-'+amenity.id" class="font-medium text-stone-900 cursor-pointer">
                                                {{ amenity.name }}
                                            </label>
                                            <p class="text-stone-500 text-xs mt-0.5">{{ amenity.description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <!-- Brew Methods Card -->
                    <div class="bg-white rounded-lg border border-stone-200 shadow-sm">
                        <div class="px-6 py-4 border-b border-stone-200">
                            <h3 class="text-base font-semibold text-stone-900">Brew Methods</h3>
                            <p class="mt-1 text-sm text-stone-600">
                                What brew methods are offered at this cafe?
                            </p>
                        </div>
                        <div class="px-6 py-5">
                            <fieldset>
                                <div class="space-y-4">
                                    <div class="flex items-start gap-3" v-for="brewMethod in brewMethods" :key="brewMethod.id">
                                        <div class="flex h-5 items-center">
                                            <input
                                                v-model="form.brew_methods"
                                                :id="'brew-method-'+brewMethod.id"
                                                :value="brewMethod.id"
                                                type="checkbox"
                                                class="h-4 w-4 rounded border-stone-300 text-amber-700 focus:ring-amber-500" />
                                        </div>
                                        <div class="text-sm">
                                            <label :for="'brew-method-'+brewMethod.id" class="font-medium text-stone-900 cursor-pointer">
                                                {{ brewMethod.name }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <!-- Drink Options Card -->
                    <div class="bg-white rounded-lg border border-stone-200 shadow-sm">
                        <div class="px-6 py-4 border-b border-stone-200">
                            <h3 class="text-base font-semibold text-stone-900">Drink Options</h3>
                            <p class="mt-1 text-sm text-stone-600">
                                Besides delicious coffee, what other drink options are offered?
                            </p>
                        </div>
                        <div class="px-6 py-5">
                            <fieldset>
                                <div class="space-y-4">
                                    <div class="flex items-start gap-3" v-for="drinkOption in drinkOptions" :key="drinkOption.id">
                                        <div class="flex h-5 items-center">
                                            <input
                                                v-model="form.drink_options"
                                                :id="'drink-option-'+drinkOption.id"
                                                :value="drinkOption.id"
                                                type="checkbox"
                                                class="h-4 w-4 rounded border-stone-300 text-amber-700 focus:ring-amber-500" />
                                        </div>
                                        <div class="text-sm">
                                            <label :for="'drink-option-'+drinkOption.id" class="font-medium text-stone-900 cursor-pointer">
                                                {{ drinkOption.name }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Map -->
                <div class="lg:sticky lg:top-6 lg:self-start">
                    <div class="bg-white rounded-lg border border-stone-200 shadow-sm overflow-hidden" v-show="!enterAddress">
                        <div class="px-6 py-4 border-b border-stone-200">
                            <h3 class="text-base font-semibold text-stone-900">Map Preview</h3>
                            <p class="mt-1 text-sm text-stone-600">
                                Location will be shown here
                            </p>
                        </div>
                        <div ref="mapElement" class="h-[600px] bg-stone-100"></div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex items-center justify-between gap-4 p-6 bg-white rounded-lg border border-stone-200 shadow-sm">
                <SecondaryLink href="/platform/cafes">
                    Cancel
                </SecondaryLink>
                <PrimaryButton
                    type="submit"
                    :disabled="form.processing">
                    {{ form.processing ? 'Creating...' : 'Create Cafe' }}
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
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

defineOptions({
    layout: AdminLayout
});

const companies = computed(() => usePage().props.companies);
const brewMethods = computed(() => usePage().props.brewMethods);
const amenities = computed(() => usePage().props.amenities);
const drinkOptions = computed(() => usePage().props.drinkOptions);
const googlMapsApiKey = computed(() => usePage().props.google_maps_api_key);

const form = useForm({
    company_id: '',
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

const { countries } = useCountries();
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
    form.post('/platform/cafes', {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Cafe created successfully'
            });
        }
    });
};
</script>
