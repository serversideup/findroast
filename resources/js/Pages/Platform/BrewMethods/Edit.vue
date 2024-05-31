<template>
    <Head title="Brew Methods" />

    <AdminHeader 
        :title="'Edit '+brewMethod.name"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform' },
            { label: 'Brew Methods', to: '/platform/brew-methods' },
            { label: 'Edit '+brewMethod.name, to: '#'}
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
                    id="brew-method-image" 
                    type="file"
                    ref="iconFile"/>
                
                <div 
                    class="w-12 h-12 flex items-center justify-center"
                    v-if="showIconPreview">
                        <img :src="iconPreview"/>
                </div>

                <div 
                    class="w-12 h-12 flex items-center justify-center"
                    v-if="!showIconPreview && brewMethod.icon != '' && brewMethod.icon != null">
                        <img :src="brewMethod.icon"/>
                </div>

                <PhotoIcon
                    class="h-12 w-12 text-gray-300"
                    aria-hidden="true"
                    v-show="form.icon == '' && ( brewMethod.icon == '' || brewMethod.icon == null )" />
                
                <button
                    type="button"
                    class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                    @click="selectIcon()">
                    Change
                </button>
            </div>
        </div>
        <div class="max-w-md mt-6 flex items-center justify-end gap-x-6">
            <Link href="/platform/brew-methods" class="text-sm font-semibold leading-6 text-gray-900">Cancel</Link>
            <PrimaryButton>Update</PrimaryButton>
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
import { computed, ref } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { PhotoIcon } from '@heroicons/vue/24/solid';
import { useEventBus } from '@vueuse/core';

const brewMethod = computed(() => usePage().props.brewMethod);

const form = useForm({
    _method: 'PUT',
    name: brewMethod.value.name,
    icon: ''
});

const notificationBus = useEventBus('roast-notification');

const submit = () => {
    router.post('/platform/brew-methods/'+brewMethod.value.id, form.data(), {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Brew Method Updated',
            });
        }
    });
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