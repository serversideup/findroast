<template>
    <Head title="Create Changelog Entry" />

    <AdminHeader
        title="Create Changelog Entry"
        :breadcrumbs="[
            { label: 'Platform', to: '/platform' },
            { label: 'Changelog', to: '/platform/changelog' },
            { label: 'Create Entry', to: '#' },
        ]" />

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form @submit.prevent="submit">
            <div class="bg-white rounded-lg border border-stone-200 shadow-sm">
                <div class="px-6 py-4 border-b border-stone-200">
                    <h3 class="text-base font-semibold text-stone-900">Entry Details</h3>
                    <p class="mt-1 text-sm text-stone-600">Add a new changelog entry to document what changed in this release.</p>
                </div>
                <div class="px-6 py-5 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <InputLabel value="Version" />
                            <TextInput
                                class="mt-1.5 block w-full"
                                v-model="form.version"
                                placeholder="e.g. 1.2.0"
                                required />
                            <InputError class="mt-1.5" :message="form.errors.version" />
                        </div>

                        <div>
                            <InputLabel value="Release Date" />
                            <TextInput
                                type="date"
                                class="mt-1.5 block w-full"
                                v-model="form.date"
                                required />
                            <InputError class="mt-1.5" :message="form.errors.date" />
                        </div>
                    </div>

                    <div>
                        <InputLabel value="Description" />
                        <TextAreaInput
                            class="mt-1.5 block w-full"
                            rows="8"
                            v-model="form.description"
                            placeholder="Describe what was added, improved, or fixed in this release..."
                            required />
                        <p class="mt-1.5 text-xs text-stone-500">Use line breaks to separate individual changes. This will be shown publicly on the changelog page.</p>
                        <InputError class="mt-1.5" :message="form.errors.description" />
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between gap-4">
                <SecondaryLink href="/platform/changelog">
                    Cancel
                </SecondaryLink>
                <PrimaryButton type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Creating...' : 'Create Entry' }}
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminHeader from '../Partials/AdminHeader.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryLink from '@/Components/SecondaryLink.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useEventBus } from '@vueuse/core';

defineOptions({ layout: AdminLayout });

const form = useForm({
    version: '',
    date: new Date().toISOString().slice(0, 10),
    description: '',
});

const notificationBus = useEventBus('roast-notification');

const submit = () => {
    form.post('/platform/changelog', {
        onSuccess: () => {
            notificationBus.emit('show', { title: 'Changelog entry created.' });
        },
    });
};
</script>
