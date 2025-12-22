<template>
    <Drawer :show="open" @close="close">
        <template #title>
            Edit Elevation
        </template>

        <template #content>
            <div class="w-full flex flex-col space-y-3">
                <div class="w-full flex flex-col">
                    <InputLabel for="name" value="Name"/>
                    <TextInput 
                        class="w-full block mt-1"
                        id="name"
                        v-model="form.name"/>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="w-full flex items-center justify-end gap-x-6 px-4 py-3">
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