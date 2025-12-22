<template>
    <Drawer :show="open" @close="close">
        <template #title>
            Add Company
        </template>
    
        <template #content>
            <div class="w-full flex flex-col space-y-3">
                <div class="flex items-start justify-between">
                    <div class="flex flex-col">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">
                            General Information
                        </h2>
                        <p class="mt- text-sm leading-6 text-gray-600">
                            Basic information about the company
                        </p>
                    </div>
                    <button @click="toggleShowGeneralInformation()" class="text-gray-900 p-3 w-10 h-10 flex items-center justify-center">
                        <ChevronDownIcon v-show="showGeneralInformation"/>
                        <ChevronUpIcon v-show="!showGeneralInformation"/>
                    </button>
                </div>

                <div v-show="showGeneralInformation" class="w-full flex flex-col space-y-3">
                    <div class="w-full flex flex-col">
                        <InputLabel for="status" value="Status"/>
                        <select
                            v-model="form.status"
                            class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                            <option value="draft">Draft</option>
                            <option value="active">Active</option>
                        </select>
                    </div>

                    <div class="w-full flex flex-col">
                        <InputLabel for="name" value="Name"/>
                        <TextInput
                            class="mt-1 block w-full"
                            v-model="form.name"/>
                    </div>

                    <div class="w-full flex flex-col">
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

                    <div class="w-full flex flex-col">
                        <InputLabel value="Website"/>
                        <div class="mt-2">
                            <TextInput
                                class="block w-full"
                                id="website"
                                v-model="form.website"/>
                        </div>
                    </div>

                    <div class="w-full flex flex-col">
                        <InputLabel value="Roasts their own coffee"/>
                        <select
                            v-model="form.roaster"
                            class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="w-full flex flex-col">
                        <InputLabel value="Offers a subscription"/>
                        <select
                            v-model="form.subscription"
                            class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="w-full flex flex-col">
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

                    <div class="w-full flex flex-col">
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

                <div class="flex items-start justify-between">
                    <div class="flex flex-col">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">
                            Location Information
                        </h2>
                        <p class="mt- text-sm leading-6 text-gray-600">
                            Where is this company Located?
                        </p>
                    </div>
                    <button @click="toggleShowLocationInformation()" class="text-gray-900 p-3 w-10 h-10 flex items-center justify-center">
                        <ChevronDownIcon v-show="showLocationInformation"/>
                        <ChevronUpIcon v-show="!showLocationInformation"/>
                    </button>
                </div>

                <div class="flex flex-col space-y-3" v-show="showLocationInformation">
                    <div class="w-full flex flex-col">
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

                    <div class="w-full flex flex-col">
                        <InputLabel value="City"/>
                        <div class="mt-1">
                            <TextInput
                                class="block w-full"
                                id="city"
                                v-model="form.city"/>
                        </div>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.country == 'US'">
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

                    <div class="w-full flex flex-col" v-if="form.country == 'AU'">
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

                    <div class="w-full flex flex-col" v-if="form.country == 'CA'">
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

                <div class="flex items-start justify-between">
                    <div class="flex flex-col">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">
                            Offerings Information
                        </h2>
                        <p class="mt- text-sm leading-6 text-gray-600">
                            Settings to control how the company's roasts are synced to the offerings directory.
                        </p>
                    </div>
                    <button @click="toggleShowOfferingInformation()" class="flex-shrink-0 text-gray-900 p-3 w-10 h-10 flex items-center justify-center">
                        <ChevronDownIcon v-show="showOfferingInformation"/>
                        <ChevronUpIcon v-show="!showOfferingInformation"/>
                    </button>
                </div>

                <div class="flex flex-col space-y-3" v-show="showOfferingInformation">
                    <div class="w-full flex flex-col">
                        <InputLabel value="Enabled"/>
                        <select
                            v-model="form.offerings.enabled"
                            class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                            <option value=""></option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="w-full flex flex-col">
                        <InputLabel value="Is Shopify"/>
                        <select
                            v-model="form.offerings.is_shopify"
                            class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                            <option value=""></option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.offerings.is_shopify == '0'">
                        <InputLabel value="Collection Url"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="collection-url"
                            v-model="form.offerings.collection_url"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.offerings.is_shopify == '0'">
                        <InputLabel value="Container Selector"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="container-selector"
                            v-model="form.offerings.container_selector"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.offerings.is_shopify == '0'">
                        <InputLabel value="Product List Item Selector"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="product-list-item-selector"
                            v-model="form.offerings.product_list_item_selector"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.offerings.is_shopify == '0'">
                        <InputLabel value="Product Selector"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="product-selector"
                            v-model="form.offerings.product_selector"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.offerings.is_shopify == '1'">
                        <InputLabel value="Product Types" for="product-types" help="Comma separated list of product types to sync. Ex: Retail SO, Retail YR"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="product-types"
                            v-model="form.offerings.shopify_product_types"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.offerings.is_shopify == '1'">
                        <InputLabel value="Tags to Include" for="shopify-tags-include"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="shopify-tags-include"
                            v-model="form.offerings.shopify_tags_include"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.offerings.is_shopify == '1'">
                        <InputLabel value="Tags to Exclude" for="shopify-tags-exclude"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="shopify-tags-exclude"
                            v-model="form.offerings.shopify_tags_exclude"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.offerings.is_shopify == '1'">
                        <InputLabel value="Collection URL" for="shopify-collection-url"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="shopify-collection-url"
                            v-model="form.offerings.shopify_collection_url"/>
                    </div>

                    <div class="w-full flex flex-col">
                        <SecondaryButton @click="previewScrapeResults()" class="justify-center">Preview Scrape Results</SecondaryButton>
                    </div>
                </div>
            </div>
        </template>
    
        <template #footer>
            <div class="w-full flex items-center justify-end gap-x-6 px-4 py-3">
                <PrimaryButton @click="addCompany()">Add Company</PrimaryButton>
            </div>
        </template>
    </Drawer>
</template>
    
<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import Drawer from '@/Components/Drawer.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ChevronDownIcon, ChevronUpIcon, PhotoIcon, UserCircleIcon } from "@heroicons/vue/24/solid";
import { useEventBus } from '@vueuse/core';
import { computed, ref } from 'vue';
import { useCountries } from '@/Composables/useCountries';
import { useProvinces } from '@/Composables/useProvinces';
import { useStates } from '@/Composables/useStates';
import { useTerritories } from '@/Composables/useTerritories';
import { useForm } from '@inertiajs/vue3';

const open = ref(false);
    
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
    twitter_url: '',
    offerings: {
        enabled: 0,
        collection_url: '',
        container_selector: '',
        product_list_item_selector: '',
        product_selector: '',
        is_shopify: '',
        shopify_product_types: '',
        shopify_tags_include: '',
        shopify_tags_exclude: '',
        shopify_collection_url: ''
    }
});
    
const { countries } = useCountries();
const states = useStates();
const provinces = useProvinces();
const territories = useTerritories();

const promptBus = useEventBus('roast-prompt-event-bus');
const promptBusListener = ( event, data ) => {
    if ( event === 'prompt-add-company' ) {
        open.value = true;
    }
}
promptBus.on(promptBusListener);

const eventBus = useEventBus('roast-event-bus');
const eventBusListener = ( event, data ) => {
    if ( event === 'use-scraping-settings' && open.value ) {
        form.offerings.is_shopify = data.is_shopify;
        form.offerings.collection_url = data.collection_url;
        form.offerings.container_selector = data.container_selector;
        form.offerings.product_list_item_selector = data.product_list_item_selector;
        form.offerings.product_selector = data.product_selector;
        form.offerings.shopify_product_types = data.shopify_product_types;
        form.offerings.shopify_tags_include = data.shopify_tags_include;
        form.offerings.shopify_tags_exclude = data.shopify_tags_exclude;
        form.offerings.shopify_collection_url = data.shopify_collection_url;
    }
}
eventBus.on(eventBusListener);

const showGeneralInformation = ref(true);
const showLocationInformation = ref(false);
const showOfferingInformation = ref(false);

const toggleShowGeneralInformation = () => {
    showGeneralInformation.value = !showGeneralInformation.value;
}

const toggleShowLocationInformation = () => {
    showLocationInformation.value = !showLocationInformation.value;
}

const toggleShowOfferingInformation = () => {
    showOfferingInformation.value = !showOfferingInformation.value;
}

const close = () => {
    open.value = false;
    form.reset();
    showGeneralInformation.value = false;
    showLocationInformation.value = false;
    showOfferingInformation.value = false;
    logoFile.value = null;
    logoPreview.value = null;
    headerFile.value = null;
    headerPreview.value = null;
}

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

const addCompany = () => {
    form.post('/platform/companies', {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Company created successfully'
            });

            close();
        }
    });
};

const previewScrapeResults = () => {
    promptBus.emit('prompt-preview-scrape-results', {
        ...form.data().offerings,
        website: form.data().website
    });
}
</script>