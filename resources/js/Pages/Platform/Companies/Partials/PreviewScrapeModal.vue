<template>
    <Modal :max-width="'2xl'" :show="show" @close="close">
        <div class="p-6 flex flex-col">
            <div class="w-full flex items-center justify-between">
                <h2 class="text-2xl font-bold">Preview Scrape Results</h2>
                <button @click="close" class="text-gray-500 hover:text-gray-700">
                    <XMarkIcon class="w-6 h-6" />
                </button>
            </div>

            <div class="w-full grid grid-cols-3 gap-4 mt-5">
                <div class="col-span-2 flex flex-col">
                    <div class="overflow-y-auto max-h-[500px] space-y-2">
                        <div v-show="!loading" class="w-full flex items-start p-2 hover:bg-gray-50 rounded" v-for="(product, index) in products" :key="index">
                            <div class="flex items-center h-6 mr-3">
                                <input
                                    type="checkbox"
                                    :id="'product-' + index"
                                    v-model="selectedProducts"
                                    :value="product.url"
                                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                />
                            </div>
                            <label :for="'product-' + index" class="flex-1 cursor-pointer">
                                <h3 class="text-base font-bold">{{ product.name }}</h3>
                                <p class="text-xs text-gray-500 truncate">{{ product.url }}</p>
                            </label>
                        </div>
                        <div v-show="loading" class="w-full flex items-center justify-center p-5">
                            <div class="w-10 h-10 rounded-full border-2 border-gray-300 border-t-2 border-t-indigo-500 animate-spin"></div>
                        </div>
                    </div>

                    <!-- Mark as Invalid Section -->
                    <div v-if="selectedProducts.length > 0 && form.company_id" class="mt-4 p-4 border-t border-gray-200">
                        <div class="flex flex-col space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">
                                    {{ selectedProducts.length }} item(s) selected
                                </span>
                                <button
                                    @click="clearSelection"
                                    class="text-xs text-gray-500 hover:text-gray-700"
                                >
                                    Clear selection
                                </button>
                            </div>
                            <div class="w-full flex flex-col">
                                <InputLabel value="Reason (optional)" class="mb-1"/>
                                <TextInput
                                    class="block w-full text-sm"
                                    v-model="invalidReason"
                                    placeholder="e.g., gift card, apparel, merchandise"/>
                            </div>
                            <button
                                @click="markAsInvalid"
                                :disabled="markingInvalid"
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white rounded-md text-sm font-medium transition-colors"
                            >
                                <span v-if="!markingInvalid">Mark as Invalid</span>
                                <span v-else>Marking...</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-span-1 flex flex-col space-y-3">
                    <div class="w-full flex flex-col">
                        <InputLabel value="Is Shopify"/>
                        <select
                            v-model="form.is_shopify"
                            class="mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm w-full">
                            <option value=""></option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="w-full flex flex-col" v-if="form.is_shopify == '0'">
                        <InputLabel value="Collection Url"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="collection-url"
                            v-model="form.collection_url"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.is_shopify == '0'">
                        <InputLabel value="Container Selector"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="container-selector"
                            v-model="form.container_selector"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.is_shopify == '0'">
                        <InputLabel value="Product List Item Selector"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="product-list-item-selector"
                            v-model="form.product_list_item_selector"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.is_shopify == '0'">
                        <InputLabel value="Product Selector"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="product-selector"
                            v-model="form.product_selector"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.is_shopify == '1'">
                        <InputLabel value="Product Types" for="product-types" help="Comma separated list of product types to sync. Ex: Retail SO, Retail YR"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="product-types"
                            v-model="form.shopify_product_types"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.is_shopify == '1'">
                        <InputLabel value="Tags to Include" for="shopify-tags-include"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="shopify-tags-include"
                            v-model="form.shopify_tags_include"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.is_shopify == '1'">
                        <InputLabel value="Tags to Exclude" for="shopify-tags-exclude"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="shopify-tags-exclude"
                            v-model="form.shopify_tags_exclude"/>
                    </div>

                    <div class="w-full flex flex-col" v-if="form.is_shopify == '1'">
                        <InputLabel value="Collection URL" for="shopify-collection-url"/>
                        <TextInput
                            class="mt-1 block w-full"
                            id="shopify-collection-url"
                            v-model="form.shopify_collection_url"/>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <SecondaryButton @click="previewData()">Preview</SecondaryButton>
                        <PrimaryButton @click="useSettings()">Use Settings</PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ChevronDownIcon, XMarkIcon } from "@heroicons/vue/24/solid";
import { ref } from 'vue';
import { useEventBus } from '@vueuse/core';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    website: '',
    collection_url: '',
    container_selector: '',
    product_list_item_selector: '',
    product_selector: '',
    is_shopify: '',
    shopify_product_types: '',
    shopify_tags_include: '',
    shopify_tags_exclude: '',
    shopify_collection_url: '',
    company_id: null
});

const promptBus = useEventBus('roast-prompt-event-bus');

const show = ref(false);

const close = () => {
    show.value = false;
}

const listener = ( event, data ) => {
    if( event === 'prompt-preview-scrape-results' ){
        show.value = true;
        form.website = data.website;
        form.collection_url = data.collection_url;
        form.container_selector = data.container_selector;
        form.product_list_item_selector = data.product_list_item_selector;
        form.product_selector = data.product_selector;
        form.is_shopify = data.is_shopify;
        form.shopify_product_types = data.shopify_product_types;
        form.shopify_tags_include = data.shopify_tags_include;
        form.shopify_tags_exclude = data.shopify_tags_exclude;
        form.shopify_collection_url = data.shopify_collection_url;
        form.company_id = data.company_id || null;
        previewData();
    }
}

promptBus.on(listener);

const products = ref([]);
const loading = ref(false);
const selectedProducts = ref([]);
const invalidReason = ref('');
const markingInvalid = ref(false);

const previewData = async () => {
    loading.value = true;
    selectedProducts.value = [];
    invalidReason.value = '';
    const response = await axios.post('/platform/offerings/preview', form.data());
    products.value = response.data;
    loading.value = false;
}

const clearSelection = () => {
    selectedProducts.value = [];
    invalidReason.value = '';
}

const markAsInvalid = async () => {
    if (selectedProducts.value.length === 0) {
        return;
    }

    markingInvalid.value = true;

    try {
        // Get company_id from the form or current context
        // We need to pass the company_id - for preview we'll need to add this
        const urlsToMark = selectedProducts.value.map(url => ({
            url: url,
            reason: invalidReason.value || null
        }));

        await axios.post('/platform/offerings/mark-invalid', {
            company_id: form.company_id,
            urls: urlsToMark
        });

        // Remove marked products from the preview list
        products.value = products.value.filter(
            product => !selectedProducts.value.includes(product.url)
        );

        // Clear selection
        clearSelection();

        // Show success message (you can add a toast notification here if available)
        alert(`Successfully marked ${urlsToMark.length} URL(s) as invalid`);
    } catch (error) {
        console.error('Error marking URLs as invalid:', error);
        alert('Failed to mark URLs as invalid. Please try again.');
    } finally {
        markingInvalid.value = false;
    }
}

const eventBus = useEventBus('roast-event-bus');

const useSettings = () => {
    eventBus.emit('use-scraping-settings', form.data());
    close();
}
</script>
