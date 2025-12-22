<template>
    <Drawer :show="open" @close="close">
        <template #title>
            Edit Flavor Note
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

                <div class="w-full flex flex-col">
                    <InputLabel for="slug" value="Slug"/>
                    <TextInput 
                        class="w-full block mt-1"
                        id="slug"
                        v-model="form.slug"/>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    The slug is used to identify the flavor note in the database.
                </p>
            </div>
        </template>
        <template #footer>
            <div class="w-full flex items-center justify-end gap-x-6 px-4 py-3">
                <DangerButton @click="deleteFlavorNote()">Delete</DangerButton>
                <PrimaryButton @click="updateFlavorNote()">Update Flavor Note</PrimaryButton>
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
const flavorNote = ref(null);

const form = useForm({
    _method: 'PUT',
    name: '',
    slug: ''
});

const notificationBus = useEventBus('roast-notification');

const updateFlavorNote = () => {
    form.post('/platform/flavor-notes/'+flavorNote.value.id, {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Flavor Note updated successfully'
            });
            close();
        }
    });
}

const deleteFlavorNote = () => {
    router.delete('/platform/flavor-notes/'+flavorNote.value.id, {
        onSuccess: () => {
            notificationBus.emit('roast-notification', {
                title: 'Flavor Note deleted successfully'
            });
            close();
        }
    });
}

const promptBus = useEventBus('roast-prompt-event-bus');
const promptBusListener = ( event, data ) => {
    if ( event === 'prompt-edit-flavor-note' ) {
        setFlavorNote(data);
        open.value = true;
    }
}
promptBus.on(promptBusListener);

const close = () => {
    open.value = false;
    form.reset();
    flavorNote.value = null;
}

const setFlavorNote = (data) => {
    flavorNote.value = data;
    form.name = data.name;
    form.slug = data.slug;
}
</script>