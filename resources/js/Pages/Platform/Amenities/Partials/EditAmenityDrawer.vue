<template>
    <Drawer :show="open" @close="close">
        <template #title>
            Edit Amenity
        </template>

        <template #content>
            <div class="w-full flex flex-col space-y-3">
                <div class="w-full flex flex-col">
                    <InputLabel for="name" value="Name"/>
                    <TextInput name="name" class="w-full mt-1" v-model="form.name"/>
                </div>

                <div class="w-full flex flex-col">
                    <InputLabel for="name">Icon</InputLabel>
                    <div class="mt-2 flex items-center gap-x-3">
                        <input
                            class="absolute -top-[5000px]" 
                            @change="handleIconChange( $event )" 
                            accept="image/*" 
                            id="amenity-image" 
                            type="file"
                            ref="iconFile"/>
                        
                        <div 
                            class="w-12 h-12 flex items-center justify-center"
                            v-if="showIconPreview">
                                <img :src="iconPreview"/>
                        </div>

                        <div 
                            class="w-12 h-12 flex items-center justify-center"
                            v-if="!showIconPreview && amenity && amenity.icon != '' && amenity.icon != null">
                                <img :src="amenity.icon"/>
                        </div>
                        
                        <button
                            type="button"
                            class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            @click="selectIcon()">
                            Change
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <div class="w-full flex items-center justify-end gap-x-6 px-4 py-3">
                <SecondaryButton @click="close()">Cancel</SecondaryButton>
                <PrimaryButton @click="updateAmenity()">Update Amenity</PrimaryButton>
            </div>
        </template>
    </Drawer>
</template>

<script setup>
import Drawer from '@/Components/Drawer.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref } from 'vue';
import { useEventBus } from '@vueuse/core';
import { PhotoIcon } from '@heroicons/vue/24/solid';
import { router, useForm } from '@inertiajs/vue3';

const open = ref(false);

const form = useForm({
    _method: 'PUT',
    name: '',
    icon: ''
});

const amenity = ref(null);

const promptBus = useEventBus('roast-prompt-event-bus');

const promptBusListener = ( event, data ) => {
    if ( event === 'prompt-edit-amenity' ) {
        open.value = true;
        setAmenity(data);
    }
}
promptBus.on(promptBusListener);

const notificationBus = useEventBus('roast-notification');

const updateAmenity = () => {
    form.post('/platform/amenities/'+amenity.value.id, {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Amenity updated',
            });
            close();
        }
    });
};

const close = () => {
    open.value = false;
    form.reset();
    iconPreview.value = null;
    showIconPreview.value = false;
    iconFile.value = null;
    amenity.value = null;
}

const setAmenity = (data) => {
    amenity.value = data;
    form.name = data.name;
    form.icon = data.icon;
}

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