<template>
    <Drawer :show="open" @close="close">
        <template #title>
            Message Details
        </template>

        <template #content>
            <div v-if="message" class="w-full flex flex-col space-y-6">
                <!-- Status Badge -->
                <div class="flex items-center justify-between">
                    <span
                        v-if="message.responded_to"
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        Responded
                    </span>
                    <span
                        v-else
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                        </svg>
                        Pending Response
                    </span>
                    <span class="text-xs text-stone-500">
                        {{ formatDate(message.created_at) }}
                    </span>
                </div>

                <!-- Contact Information -->
                <div class="bg-stone-50 rounded-lg p-4 space-y-3">
                    <h3 class="text-sm font-semibold text-stone-900 uppercase tracking-wider">Contact Information</h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Name" class="text-stone-600 font-medium mb-1"/>
                            <p class="text-sm text-stone-900 font-medium">
                                {{ message.name }}
                            </p>
                        </div>

                        <div>
                            <InputLabel value="Email" class="text-stone-600 font-medium mb-1"/>
                            <a :href="`mailto:${message.email}`" class="text-sm text-amber-700 hover:text-amber-800 font-medium">
                                {{ message.email }}
                            </a>
                        </div>
                    </div>

                    <div>
                        <InputLabel value="Subject" class="text-stone-600 font-medium mb-1"/>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-stone-200 text-stone-800">
                            {{ formatSubject(message.subject) }}
                        </span>
                    </div>
                </div>

                <!-- Company Information (if applicable) -->
                <div v-if="message.company_name || message.company_url" class="bg-amber-50 rounded-lg p-4 space-y-3 border border-amber-200">
                    <h3 class="text-sm font-semibold text-amber-900 uppercase tracking-wider">Suggested Company</h3>

                    <div v-if="message.company_name">
                        <InputLabel value="Company Name" class="text-amber-800 font-medium mb-1"/>
                        <p class="text-sm text-amber-900 font-medium">
                            {{ message.company_name }}
                        </p>
                    </div>

                    <div v-if="message.company_url">
                        <InputLabel value="Website" class="text-amber-800 font-medium mb-1"/>
                        <a :href="message.company_url" target="_blank" rel="noopener noreferrer" class="text-sm text-amber-700 hover:text-amber-800 font-medium inline-flex items-center">
                            {{ message.company_url }}
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Message Content -->
                <div class="space-y-3">
                    <InputLabel value="Message" class="text-stone-700 font-semibold"/>
                    <div class="bg-white border border-stone-200 rounded-lg p-4">
                        <p class="text-sm text-stone-700 whitespace-pre-wrap leading-relaxed">
                            {{ message.message }}
                        </p>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="pt-4 border-t border-stone-200">
                    <details class="text-xs text-stone-500">
                        <summary class="cursor-pointer hover:text-stone-700 font-medium">
                            Technical Information
                        </summary>
                        <div class="mt-2 space-y-1 pl-4">
                            <p><span class="font-medium">Message ID:</span> #{{ message.id }}</p>
                            <p><span class="font-medium">IP Address:</span> {{ message.ip_address || 'N/A' }}</p>
                            <p><span class="font-medium">Submitted:</span> {{ formatDateTime(message.created_at) }}</p>
                        </div>
                    </details>
                </div>
            </div>
        </template>

        <template #footer>
            <div class="w-full flex items-center justify-between gap-x-3 px-4 py-3">
                <button
                    v-if="message && !message.responded_to"
                    @click="deleteMessage()"
                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
                <div class="flex-1"></div>
                <SecondaryButton @click="close()">Close</SecondaryButton>
                <PrimaryButton
                    v-if="message && !message.responded_to"
                    @click="markAsRespondedTo()"
                    class="bg-amber-700 hover:bg-amber-800">
                    Mark as Responded
                </PrimaryButton>
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
const notificationBus = useEventBus('roast-notification');

const promptBusListener = ( event, data ) => {
    if ( event === 'prompt-view-message' ) {
        open.value = true;
        setMessage(data);
    }
}
promptBus.on(promptBusListener);

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

const deleteMessage = () => {
    if (confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
        router.delete('/platform/messages/'+message.value.id, {
            onSuccess: () => {
                notificationBus.emit('show', {
                    title: 'Message deleted successfully',
                });
                close();
            }
        });
    }
};

const close = () => {
    open.value = false;
    message.value = null;
}

const setMessage = (data) => {
    message.value = data;
}

const formatSubject = (subject) => {
    return subject
        .split('_')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
}

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

const formatDateTime = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
}
</script>