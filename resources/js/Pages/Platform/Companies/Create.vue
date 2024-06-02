<template>
    <Head :title="'Create Company'" />

    <AdminHeader 
        :title="'Create Company'"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform' },
            { label: 'Companies', to: '/platform/companies' },
            { label: 'Create Company', to: '#' }
        ]">
    </AdminHeader>

    <div class="max-w-screen-xl mx-auto mt-5 lg:px-8">
        <form @submit.prevent="submit">
            <div class="grid grid-cols-2 gap-x-8">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">
                            Profile
                        </h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">
                            Basic information regarding the company.
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

                            <div class="sm:col-span-4">
                                <InputLabel value="Company Name"/>
                                <TextInput
                                    class="mt-1 block w-full"
                                    id="name"
                                    v-model="form.name"
                                    required
                                    autofocus/>
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

                            <div class="sm:col-span-4">
                                <InputLabel value="Roasts their own coffee"/>
                                <select
                                    v-model="form.roaster"
                                    class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="sm:col-span-4">
                                <InputLabel value="Offers a subscription"/>
                                <select
                                    v-model="form.subscription"
                                    class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="col-span-full">
                                <InputLabel value="Logo"/>
                                <div class="mt-2 flex items-center gap-x-3">
                                    <input
                                        class="absolute -top-[5000px]" 
                                        @change="handleLogoChange( $event )" 
                                        accept="image/*" 
                                        id="company-logo" 
                                        type="file"
                                        ref="logoFile"/>

                                    <div 
                                        class="w-12 h-12 flex items-center justify-center"
                                        v-if="logoState == 'company-logo'">
                                            <img :src="company.logo"/>
                                    </div>

                                    <div 
                                        class="w-12 h-12 flex items-center justify-center"
                                        v-if="logoState == 'selected-logo'">
                                            <img :src="logoPreview"/>
                                    </div>

                                    <UserCircleIcon
                                        class="h-12 w-12 text-gray-300"
                                        aria-hidden="true"
                                        v-show="logoState == 'no-logo'" />

                                    <button
                                        type="button"
                                        class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                                        @click="selectLogo()">
                                        Change
                                    </button>
                                </div>
                            </div>

                            <div class="col-span-full">
                                <InputLabel value="Cover Photo"/>
                                <div
                                    class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                                    <div class="text-center">
                                        <PhotoIcon
                                            class="mx-auto h-12 w-12 text-gray-300"
                                            aria-hidden="true" />
                                        <div
                                            class="mt-4 flex text-sm leading-6 text-gray-600">
                                            <label
                                                for="company-header-image"
                                                class="relative cursor-pointer rounded-md bg-white font-semibold text-gray-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-gray-600 focus-within:ring-offset-2 hover:text-gray-500">
                                                <span>Upload a file</span>
                                                <input
                                                    @change="handleHeaderChange( $event )"
                                                    accept="image/*" 
                                                    id="company-header-image"
                                                    name="company-header-image"
                                                    type="file"
                                                    class="sr-only"
                                                    ref="headerFile" />
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs leading-5 text-gray-600">
                                            PNG, JPG, GIF up to 10MB
                                        </p>
                                    </div>
                                </div>

                                <div v-if="headerState == 'company-header'" class="mt-2 h-[190px] rounded-lg"
                                    :style="{
                                        'background-image': 'url('+company.header_image+')',
                                        'background-size': 'cover',
                                        'background-position': 'center'
                                    }">

                                </div>

                                <div v-if="headerState == 'selected-header'" class="mt-2 h-[190px] rounded-lg"
                                    :style="{
                                        'background-image': 'url('+headerPreview+')',
                                        'background-size': 'cover',
                                        'background-position': 'center'
                                    }">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-x-8 pt-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Location Information
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Where is this company Located?
                    </p>

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
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

                        <div class="sm:col-span-2 sm:col-start-1">
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
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-x-8 pt-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Online presence
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Where can this company be found online?
                    </p>

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <div class="sm:col-span-4">
                            <InputLabel value="Website"/>
                            <TextInput
                                class="mt-1 block w-full"
                                id="name"
                                v-model="form.website"/>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel value="Facebook"/>
                            <TextInput
                                class="mt-1 block w-full"
                                id="name"
                                v-model="form.facebook_url"/>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel value="Instagram"/>
                            <TextInput
                                class="mt-1 block w-full"
                                id="name"
                                v-model="form.instagram_url"/>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel value="Twitter"/>
                            <TextInput
                                class="mt-1 block w-full"
                                id="name"
                                v-model="form.twitter"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-x-8 pt-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">
                        Offerings Information
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                       Settings to control how the company's roasts are synced to the offerings directory.
                    </p>

                    <div
                        class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <div class="sm:col-span-4">
                            <InputLabel value="Enabled"/>
                            <select
                                v-model="form.offerings.enabled"
                                class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                                <option value=""></option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel value="API URL"/>
                            <TextInput
                                class="mt-1 block w-full"
                                id="name"
                                v-model="form.offerings.api_url"/>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel value="Day to Sync"/>
                            <select
                                v-model="form.offerings.day"
                                class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                                <option value=""></option>
                                <option value="sunday">Sunday</option>
                                <option value="monday">Monday</option>
                                <option value="tuesday">Tuesday</option>
                                <option value="wednesday">Wednesday</option>
                                <option value="thursday">Thursday</option>
                                <option value="friday">Friday</option>
                                <option value="saturday">Saturday</option>
                            </select>
                        </div>
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
import { computed, ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { PhotoIcon, UserCircleIcon } from "@heroicons/vue/24/solid";
import { useEventBus } from '@vueuse/core';

const form = useForm({
    name: '',
    status: 'draft',
    header_image: '',
    logo: '',
    roaster: 0,
    subscription: 0,
    description: '',
    website: '',
    city: '',
    state: '',
    province: '',
    territory: '',
    country: '',
    facebook_url: '',
    instagram_url: '',
    twitter_url: ''
    offerings: {
        enabled: 0,
        api_url: '',
        day: ''
    }
});

const countries = useCountries();
const states = useStates();
const provinces = useProvinces();
const territories = useTerritories();

/**
 * Logo methods
 */
const logoFile = ref(null);
const logoPreview = ref(null);

const logoState = computed(() => {
    if( form.logo != '' ){
        return 'selected-logo';
    }

    return 'no-logo';
});

const selectLogo = () => {
    logoFile.value.click();
};

const handleLogoChange = (event) => {
    form.logo = event.target.files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function(){
        logoPreview.value = reader.result;
    }, false);

    if( form.logo ){
        if ( /\.(jpe?g|png|gif)$/i.test( form.logo.name ) ) {
            reader.readAsDataURL( form.logo );
        }
    }
};

/**
 * Cover photo methods
 */
const headerFile = ref(null);
const headerPreview = ref(null);

const headerState = computed(() => {
    if( form.header_image != '' ){
        return 'selected-header';
    }

    return 'no-header';
});

const handleHeaderChange = (event) => {
    form.header_image = event.target.files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function(){
        headerPreview.value = reader.result;
    }, false);

    if( form.header_image ){
        if ( /\.(jpe?g|png|gif)$/i.test( form.header_image.name ) ) {
            reader.readAsDataURL( form.header_image );
        }
    }
};

const notificationBus = useEventBus('roast-notification');

const submit = () => {
    form.post('/platform/companies', {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Company created successfully'
            });
        }
    });
};
</script>
