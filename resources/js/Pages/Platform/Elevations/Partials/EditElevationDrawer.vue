<template>
    <Drawer :show="open" @close="close" max-width="xl" bg-color="bg-stone-50">
        <template #title>
            Edit Elevation
        </template>

        <template #subtitle>
            Update elevation information
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
                            placeholder="e.g., 1200-1500m, High Altitude, Low Altitude"/>
                        <p class="mt-1.5 text-xs text-stone-500">
                            The display name for this elevation range
                        </p>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="w-full flex items-center justify-between gap-x-4 px-6 py-4 bg-white border-t border-stone-200">
                <DangerButton @click="deleteElevation()">Delete</DangerButton>
                <PrimaryButton @click="updateElevation()">Update Elevation</PrimaryButton>
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
const elevation = ref(null);

const form = useForm({
    _method: 'PUT',
    name: '',
});

const notificationBus = useEventBus('roast-notification');

const updateElevation = () => {
    form.post('/platform/elevations/'+elevation.value.id, {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Elevation updated successfully'
            });
            close();
        }
    });
}

const deleteElevation = () => {
    router.delete('/platform/elevations/'+elevation.value.id, {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Elevation deleted successfully'
            });
            close();
        }
    });
}

const promptBus = useEventBus('roast-prompt-event-bus');
const promptBusListener = ( event, data ) => {
    if ( event === 'prompt-edit-elevation' ) {
        setElevation(data);
        open.value = true;
    }
}
promptBus.on(promptBusListener);

const close = () => {
    open.value = false;
    form.reset();
    elevation.value = null;
}

const setElevation = (data) => {
    elevation.value = data;
    form.name = data.name;
}
</script>