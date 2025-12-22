<template>
    <Drawer :show="open" @close="close">
        <template #title>
            View Message
        </template>

        <template #content>
            <div class="w-full flex flex-col space-y-3">
                <div class="w-full flex flex-col">
                    <InputLabel for="name" value="Name"/>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ message.name }}
                    </p>
                </div>

                <div class="w-full flex flex-col">
                    <p class="mt-1 text-sm text-gray-500">
                        {{ message.email }}
                    </p>
                </div>

                <div class="w-full flex flex-col">
                    <p class="mt-1 text-sm text-gray-500">
                        {{ message.message }}
                    </p>
                </div>
            </div>
        </template>

        <template #footer>
            <div class="w-full flex items-center justify-end gap-x-6 px-4 py-3">
                <SecondaryButton @click="close()">Cancel</SecondaryButton>
                <PrimaryButton @click="markAsRespondedTo()">Mark as Responded To</PrimaryButton>
            </div>
        </template>
    </Drawer>
</template>

<script setup>
import Drawer from '@/Components/Drawer.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref } from 'vue';
import { useEventBus } from '@vueuse/core';
import { router } from '@inertiajs/vue3';

const open = ref(false);

const message = ref(null);

const promptBus = useEventBus('roast-prompt-event-bus');

const promptBusListener = ( event, data ) => {
    if ( event === 'prompt-view-message' ) {
        open.value = true;
        setMessage(data);
    }
}
promptBus.on(promptBusListener);

const notificationBus = useEventBus('roast-notification');

const markAsRespondedTo = () => {
    router.put('/platform/messages/'+message.value.id, {
        onSuccess: () => {
            notificationBus.emit('show', {
                title: 'Message marked as responded to',
            });
            close();
        }
    });
};

const close = () => {
    open.value = false;
    message.value = null;
}

const setMessage = (data) => {
    message.value = data;
}
</script>