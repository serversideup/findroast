<template>
    <Head title="Drink Options" />

    <AdminHeader 
        :title="'Create Drink Option'"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform' },
            { label: 'Drink Options', to: '/platform/drink-options' },
            { label: 'Create Drink Option', to: '#'}
        ]"/>

    <form class="max-w-screen-xl mx-auto mt-8 flex flex-col space-y-4" @submit.prevent="submit()">
        <div class="max-w-md">
            <InputLabel for="name">Name</InputLabel>
            <TextInput name="name" class="w-full mt-1" v-model="form.name"/>
        </div>
        <div class="max-w-md">
            <InputLabel for="name">Icon</InputLabel>
            <div class="mt-2 flex items-center gap-x-3">
                <input
                    class="absolute -top-[5000px]" 
                    @change="handleIconChange( $event )" 
                    accept="image/*" 
                    id="drink-option-image" 
                    type="file"
                    ref="iconFile"/>
                
                <div 
                    class="w-12 h-12 flex items-center justify-center"
                    v-if="showIconPreview">
                        <img :src="iconPreview"/>
                </div>

                <PhotoIcon
                    class="h-12 w-12 text-gray-300"
                    aria-hidden="true"
                    v-show="form.icon == ''" />
                
                <button
                    type="button"
                    class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                    @click="selectIcon()">
                    Change
                </button>
            </div>
        </div>
        <div class="max-w-md mt-6 flex items-center justify-end gap-x-6">
            <Link href="/platform/drink-options" class="text-sm font-semibold leading-6 text-gray-900">Cancel</Link>
            <PrimaryButton>Save</PrimaryButton>
        </div>
    </form>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { PhotoIcon } from '@heroicons/vue/24/solid';

const form = useForm({
    name: '',
    icon: ''
});

const submit = () => {
    form.post('/platform/drink-options');
};

/**
 * Icon preview
 */
const iconFile = ref(null);
const selectIcon = () => {
    iconFile.value.click();
};
const showIconPreview = ref(false);
const iconPreview = ref(null);

const handleIconChange = (event) => {
    form.icon = event.target.files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function(){
        showIconPreview.value = true;
        iconPreview.value = reader.result;
    }, false);

    if( form.icon ){
        if ( /\.(jpe?g|png|gif)$/i.test( form.icon.name ) ) {
            reader.readAsDataURL( form.icon );
        }
    }
};
</script>