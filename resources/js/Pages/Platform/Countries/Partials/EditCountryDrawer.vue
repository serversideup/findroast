<template>
    <Drawer :show="open" @close="close" max-width="xl" bg-color="bg-stone-50">
        <template #title>
            Edit Country
        </template>

        <template #subtitle>
            Update country information
        </template>

        <template #content>
            <div class="w-full flex flex-col space-y-6">
                <div class="bg-white rounded-lg border border-stone-200 shadow-sm p-6 space-y-5">
                    <div>
                        <InputLabel for="name" value="Name"/>
                        <TextInput
                            class="mt-1.5 block w-full"
                            id="name"
                            v-model="form.name"
                            placeholder="e.g., Ethiopia, Colombia, Costa Rica"/>
                        <p class="mt-1.5 text-xs text-stone-500">
                            The display name for this country
                        </p>
                    </div>

                    <div>
                        <InputLabel for="slug" value="Slug"/>
                        <TextInput
                            class="mt-1.5 block w-full"
                            id="slug"
                            v-model="form.slug"
                            placeholder="e.g., ethiopia, colombia, costa-rica"/>
                        <p class="mt-1.5 text-xs text-stone-500">
                            The slug is used to identify the country in the database
                        </p>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="w-full flex items-center justify-between gap-x-4 px-6 py-4 bg-white border-t border-stone-200">
                <DangerButton @click="deleteCountry()">Delete</DangerButton>
                <PrimaryButton @click="updateCountry()">Update Country</PrimaryButton>
            </div>
        </template>
    </Drawer>
</template>

<script setup>
import Drawer from '@/Components/Drawer.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { ref } from 'vue';
import { useEventBus } from '@vueuse/core';
import { router, useForm } from '@inertiajs/vue3';

const open = ref(false);
const country = ref(null);

const form = useForm({
    _method: 'PUT',
    name: '',
    slug: ''
});

const notificationBus = useEventBus('roast-notification');

const updateCountry = () => {
    form.post('/platform/countries/'+country.value.id, {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Country updated successfully'
            });
            close();
        }
    });
}

const deleteCountry = () => {
    router.delete('/platform/countries/'+country.value.id, {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Country deleted successfully'
            });
            close();
        }
    });
}

const promptBus = useEventBus('roast-prompt-event-bus');
const promptBusListener = ( event, data ) => {
    if ( event === 'prompt-edit-country' ) {
        setCountry(data);
        open.value = true;
    }
}
promptBus.on(promptBusListener);

const close = () => {
    open.value = false;
    form.reset();
    country.value = null;
}

const setCountry = (data) => {
    country.value = data;
    form.name = data.name;
    form.slug = data.slug;
}
</script>