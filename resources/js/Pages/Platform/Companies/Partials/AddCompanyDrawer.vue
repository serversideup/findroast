<template>
    <Drawer :show="open" @close="close" max-width="xl" bg-color="bg-stone-50">
        <template #title>
            Add Company
        </template>

        <template #subtitle>
            Create a new coffee company profile
        </template>

        <template #content>
            <div class="w-full flex flex-col space-y-6">
                <!-- General Information Section -->
                <Disclosure as="div" :default-open="true" v-slot="{ open: isOpen }">
                    <div class="bg-white rounded-lg border border-stone-200 shadow-sm overflow-hidden">
                        <DisclosureButton class="w-full px-6 py-4 flex items-center justify-between hover:bg-stone-50 transition-colors">
                            <div class="flex flex-col items-start">
                                <h3 class="text-base font-semibold text-stone-900">
                                    General Information
                                </h3>
                                <p class="mt-1 text-sm text-stone-600">
                                    Basic information about the company
                                </p>
                            </div>
                            <ChevronDownIcon
                                :class="[isOpen ? 'rotate-180' : '', 'h-5 w-5 text-stone-500 transition-transform duration-200']"
                            />
                        </DisclosureButton>

                        <DisclosurePanel class="px-6 pb-6 pt-4 space-y-5 border-t border-stone-100">
                            <!-- Status and Name -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="status" value="Status"/>
                                    <select
                                        v-model="form.status"
                                        class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                        <option value="draft">Draft</option>
                                        <option value="active">Active</option>
                                    </select>
                                </div>

                                <div>
                                    <InputLabel for="name" value="Company Name"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        v-model="form.name"
                                        placeholder="e.g., Blue Bottle Coffee"/>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <InputLabel value="Description"/>
                                <TextAreaInput
                                    class="mt-1.5 block w-full"
                                    id="description"
                                    v-model="form.description"
                                    rows="3"
                                    placeholder="Write a short description about the company..."/>
                                <p class="mt-1.5 text-xs text-stone-500">
                                    A brief overview of the company and their coffee philosophy.
                                </p>
                            </div>

                            <!-- Website -->
                            <div>
                                <InputLabel value="Website"/>
                                <TextInput
                                    class="mt-1.5 block w-full"
                                    id="website"
                                    v-model="form.website"
                                    placeholder="https://example.com"/>
                            </div>

                            <!-- Roaster and Subscription -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel value="Roasts their own coffee"/>
                                    <select
                                        v-model="form.roaster"
                                        class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                                <div>
                                    <InputLabel value="Offers a subscription"/>
                                    <select
                                        v-model="form.subscription"
                                        class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Logo -->
                            <div>
                                <InputLabel value="Company Logo"/>
                                <div class="mt-2 flex items-center gap-x-4">
                                    <input
                                        class="sr-only"
                                        @change="handleLogoChange( $event )"
                                        accept="image/*"
                                        id="company-logo"
                                        type="file"
                                        ref="logoFile"/>

                                    <div
                                        class="w-16 h-16 rounded-lg overflow-hidden bg-stone-100 flex items-center justify-center border-2 border-stone-200"
                                        v-if="logoState == 'company-logo'">
                                        <img :src="company.logo" class="w-full h-full object-cover"/>
                                    </div>

                                    <div
                                        class="w-16 h-16 rounded-lg overflow-hidden bg-stone-100 flex items-center justify-center border-2 border-stone-200"
                                        v-if="logoState == 'selected-logo'">
                                        <img :src="logoPreview" class="w-full h-full object-cover"/>
                                    </div>

                                    <div
                                        class="w-16 h-16 rounded-lg bg-stone-100 flex items-center justify-center border-2 border-dashed border-stone-300"
                                        v-show="logoState == 'no-logo'">
                                        <UserCircleIcon
                                            class="h-10 w-10 text-stone-400"
                                            aria-hidden="true" />
                                    </div>

                                    <button
                                        type="button"
                                        class="px-4 py-2 text-sm font-medium text-amber-900 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors"
                                        @click="selectLogo()">
                                        {{ logoState === 'no-logo' ? 'Upload Logo' : 'Change Logo' }}
                                    </button>
                                </div>
                            </div>

                            <!-- Cover Photo -->
                            <div>
                                <InputLabel value="Cover Photo"/>

                                <!-- Preview if exists -->
                                <div v-if="headerState == 'company-header'" class="mt-2 h-40 rounded-lg overflow-hidden border-2 border-stone-200"
                                    :style="{
                                        'background-image': 'url('+company.header_image+')',
                                        'background-size': 'cover',
                                        'background-position': 'center'
                                    }">
                                </div>

                                <div v-if="headerState == 'selected-header'" class="mt-2 h-40 rounded-lg overflow-hidden border-2 border-stone-200"
                                    :style="{
                                        'background-image': 'url('+headerPreview+')',
                                        'background-size': 'cover',
                                        'background-position': 'center'
                                    }">
                                </div>

                                <!-- Upload area -->
                                <div
                                    class="mt-2 flex justify-center rounded-lg border-2 border-dashed border-stone-300 px-6 py-8 hover:border-amber-400 transition-colors"
                                    :class="{ 'mt-3': headerState !== 'no-header' }">
                                    <div class="text-center">
                                        <PhotoIcon
                                            class="mx-auto h-10 w-10 text-stone-400"
                                            aria-hidden="true" />
                                        <div
                                            class="mt-3 flex text-sm text-stone-600">
                                            <label
                                                for="company-header-image"
                                                class="relative cursor-pointer rounded-md font-semibold text-amber-700 hover:text-amber-800">
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
                                        <p class="text-xs text-stone-500 mt-1">
                                            PNG, JPG, GIF up to 10MB
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </DisclosurePanel>
                    </div>
                </Disclosure>

                <!-- Location Information Section -->
                <Disclosure as="div" v-slot="{ open: isOpen }">
                    <div class="bg-white rounded-lg border border-stone-200 shadow-sm overflow-hidden">
                        <DisclosureButton class="w-full px-6 py-4 flex items-center justify-between hover:bg-stone-50 transition-colors">
                            <div class="flex flex-col items-start">
                                <h3 class="text-base font-semibold text-stone-900">
                                    Location Information
                                </h3>
                                <p class="mt-1 text-sm text-stone-600">
                                    Where is this company located?
                                </p>
                            </div>
                            <ChevronDownIcon
                                :class="[isOpen ? 'rotate-180' : '', 'h-5 w-5 text-stone-500 transition-transform duration-200']"
                            />
                        </DisclosureButton>

                        <DisclosurePanel class="px-6 pb-6 pt-4 space-y-5 border-t border-stone-100">
                            <!-- Country and City -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
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

                                <div>
                                    <InputLabel value="City"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        id="city"
                                        v-model="form.city"
                                        placeholder="Enter city"/>
                                </div>
                            </div>

                            <!-- State (US only) -->
                            <div v-if="form.country == 'US'">
                                <InputLabel value="State"/>
                                <select
                                    v-model="form.state"
                                    class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                    <option value="">Select a state...</option>
                                    <option v-for="state in states"
                                        :key="state.abbr"
                                        :value="state.abbr"
                                        v-text="state.name"></option>
                                </select>
                            </div>

                            <!-- Territory (AU only) -->
                            <div v-if="form.country == 'AU'">
                                <InputLabel value="Territory"/>
                                <select
                                    v-model="form.territory"
                                    class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                    <option value="">Select a territory...</option>
                                    <option v-for="territory in territories"
                                        :key="territory.abbr"
                                        :value="territory.abbr"
                                        v-text="territory.name"></option>
                                </select>
                            </div>

                            <!-- Province (CA only) -->
                            <div v-if="form.country == 'CA'">
                                <InputLabel value="Province"/>
                                <select
                                    v-model="form.province"
                                    class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                    <option value="">Select a province...</option>
                                    <option v-for="province in provinces"
                                        :key="province.abbr"
                                        :value="province.abbr"
                                        v-text="province.name"></option>
                                </select>
                            </div>
                        </DisclosurePanel>
                    </div>
                </Disclosure>

                <!-- Offerings Information Section -->
                <Disclosure as="div" v-slot="{ open: isOpen }">
                    <div class="bg-white rounded-lg border border-stone-200 shadow-sm overflow-hidden">
                        <DisclosureButton class="w-full px-6 py-4 flex items-center justify-between hover:bg-stone-50 transition-colors">
                            <div class="flex flex-col items-start">
                                <h3 class="text-base font-semibold text-stone-900">
                                    Offerings Information
                                </h3>
                                <p class="mt-1 text-sm text-stone-600">
                                    Settings to control how the company's roasts are synced to the offerings directory
                                </p>
                            </div>
                            <ChevronDownIcon
                                :class="[isOpen ? 'rotate-180' : '', 'h-5 w-5 text-stone-500 transition-transform duration-200']"
                            />
                        </DisclosureButton>

                        <DisclosurePanel class="px-6 pb-6 pt-4 space-y-5 border-t border-stone-100">
                            <!-- Enabled and Is Shopify -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel value="Enabled"/>
                                    <select
                                        v-model="form.offerings.enabled"
                                        class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                        <option value="">Select...</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                                <div>
                                    <InputLabel value="Is Shopify"/>
                                    <select
                                        v-model="form.offerings.is_shopify"
                                        class="mt-1.5 border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm w-full text-sm">
                                        <option value="">Select...</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Non-Shopify Settings -->
                            <div v-if="form.offerings.is_shopify == '0'" class="space-y-4 p-4 bg-stone-50 rounded-lg border border-stone-200">
                                <h4 class="text-sm font-medium text-stone-900">Custom Scraping Configuration</h4>

                                <div>
                                    <InputLabel value="Collection URL"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        id="collection-url"
                                        v-model="form.offerings.collection_url"
                                        placeholder="https://example.com/collections/coffee"/>
                                </div>

                                <div>
                                    <InputLabel value="Container Selector"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        id="container-selector"
                                        v-model="form.offerings.container_selector"
                                        placeholder=".product-grid"/>
                                    <p class="mt-1.5 text-xs text-stone-500">CSS selector for the product container</p>
                                </div>

                                <div>
                                    <InputLabel value="Product List Item Selector"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        id="product-list-item-selector"
                                        v-model="form.offerings.product_list_item_selector"
                                        placeholder=".product-item"/>
                                    <p class="mt-1.5 text-xs text-stone-500">CSS selector for individual product items</p>
                                </div>

                                <div>
                                    <InputLabel value="Product Selector"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        id="product-selector"
                                        v-model="form.offerings.product_selector"
                                        placeholder="a.product-link"/>
                                    <p class="mt-1.5 text-xs text-stone-500">CSS selector for product links</p>
                                </div>
                            </div>

                            <!-- Shopify Settings -->
                            <div v-if="form.offerings.is_shopify == '1'" class="space-y-4 p-4 bg-amber-50 rounded-lg border border-amber-200">
                                <h4 class="text-sm font-medium text-amber-900">Shopify Configuration</h4>

                                <div>
                                    <InputLabel value="Product Types" for="product-types"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        id="product-types"
                                        v-model="form.offerings.shopify_product_types"
                                        placeholder="Retail SO, Retail YR"/>
                                    <p class="mt-1.5 text-xs text-amber-800">Comma separated list of product types to sync</p>
                                </div>

                                <div>
                                    <InputLabel value="Tags to Include" for="shopify-tags-include"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        id="shopify-tags-include"
                                        v-model="form.offerings.shopify_tags_include"
                                        placeholder="coffee, single-origin"/>
                                    <p class="mt-1.5 text-xs text-amber-800">Comma separated list of tags to include</p>
                                </div>

                                <div>
                                    <InputLabel value="Tags to Exclude" for="shopify-tags-exclude"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        id="shopify-tags-exclude"
                                        v-model="form.offerings.shopify_tags_exclude"
                                        placeholder="merchandise, subscription"/>
                                    <p class="mt-1.5 text-xs text-amber-800">Comma separated list of tags to exclude</p>
                                </div>

                                <div>
                                    <InputLabel value="Collection URL" for="shopify-collection-url"/>
                                    <TextInput
                                        class="mt-1.5 block w-full"
                                        id="shopify-collection-url"
                                        v-model="form.offerings.shopify_collection_url"
                                        placeholder="https://store.myshopify.com/collections/coffee"/>
                                </div>
                            </div>

                            <!-- Preview Button -->
                            <div class="pt-2">
                                <SecondaryButton @click="previewScrapeResults()" class="w-full justify-center">
                                    Preview Scrape Results
                                </SecondaryButton>
                            </div>
                        </DisclosurePanel>
                    </div>
                </Disclosure>
            </div>
        </template>

        <template #footer>
            <div class="w-full flex items-center justify-between gap-x-4 px-6 py-4 bg-white border-t border-stone-200">
                <SecondaryButton @click="close">Cancel</SecondaryButton>
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
import { ChevronDownIcon, PhotoIcon, UserCircleIcon } from "@heroicons/vue/24/solid";
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
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

const close = () => {
    open.value = false;
    form.reset();
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
