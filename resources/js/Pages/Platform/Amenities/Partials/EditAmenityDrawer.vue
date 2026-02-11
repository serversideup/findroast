<template>
    <Drawer :show="open" @close="close" max-width="xl" bg-color="bg-stone-50">
        <template #title>
            Edit Amenity
        </template>

        <template #subtitle>
            Update amenity information
        </template>

        <template #content>
            <div class="w-full flex flex-col space-y-6">
                <div class="bg-white rounded-lg border border-stone-200 shadow-sm p-6 space-y-5">
                    <div>
                        <InputLabel for="name" value="Name"/>
                        <TextInput
                            name="name"
                            class="mt-1.5 block w-full"
                            v-model="form.name"
                            placeholder="e.g., WiFi, Outdoor Seating, Pet Friendly"/>
                        <p class="mt-1.5 text-xs text-stone-500">
                            The display name for this amenity
                        </p>
                    </div>

                    <div>
                        <InputLabel for="icon" value="Icon"/>
                        <div class="mt-2 flex items-center gap-x-4">
                            <input
                                class="sr-only"
                                @change="handleIconChange( $event )"
                                accept="image/*"
                                id="amenity-image"
                                type="file"
                                ref="iconFile"/>

                            <div
                                class="w-16 h-16 rounded-lg overflow-hidden bg-stone-100 flex items-center justify-center border-2 border-stone-200"
                                v-if="showIconPreview">
                                <img :src="iconPreview" class="w-full h-full object-cover"/>
                            </div>

                            <div
                                class="w-16 h-16 rounded-lg overflow-hidden bg-stone-100 flex items-center justify-center border-2 border-stone-200"
                                v-else-if="amenity && amenity.icon != '' && amenity.icon != null">
                                <img :src="amenity.icon" class="w-full h-full object-cover"/>
                            </div>

                            <div
                                class="w-16 h-16 rounded-lg bg-stone-100 flex items-center justify-center border-2 border-dashed border-stone-300"
                                v-else>
                                <PhotoIcon
                                    class="h-10 w-10 text-stone-400"
                                    aria-hidden="true" />
                            </div>

                            <button
                                type="button"
                                class="px-4 py-2 text-sm font-medium text-amber-900 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition-colors"
                                @click="selectIcon()">
                                Change Icon
                            </button>
                        </div>
                        <p class="mt-1.5 text-xs text-stone-500">
                            PNG, JPG, or SVG recommended
                        </p>
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <div class="w-full flex items-center justify-between gap-x-4 px-6 py-4 bg-white border-t border-stone-200">
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