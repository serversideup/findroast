<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdateProfilePictureForm from './Partials/UpdateProfilePictureForm.vue';
import ManageSubscriptionsForm from './Partials/ManageSubscriptionsForm.vue';
import ManageApiTokensForm from './Partials/ManageApiTokensForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    subscriptions: {
        type: Array,
        default: () => []
    },
    tokens: {
        type: Array,
        default: () => []
    },
    newToken: {
        type: String,
        default: null
    }
});
</script>

<template>
    <Head title="My Account" />

    <AppLayout>
        <div class="min-h-screen bg-stone-50 py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-stone-900">My Account</h1>
                    <p class="mt-2 text-stone-600">Manage your profile settings and preferences.</p>
                </div>

                <!-- Cards Container -->
                <div class="space-y-6">
                    <!-- Profile Picture Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-stone-200 p-6">
                        <UpdateProfilePictureForm />
                    </div>

                    <!-- Personal Information Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-stone-200 p-6">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </div>

                    <!-- Password Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-stone-200 p-6">
                        <UpdatePasswordForm />
                    </div>

                    <!-- Subscriptions Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-stone-200 p-6">
                        <ManageSubscriptionsForm :subscriptions="subscriptions" />
                    </div>

                    <!-- API Tokens Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-stone-200 p-6">
                        <ManageApiTokensForm :tokens="tokens" :new-token="newToken" />
                    </div>

                    <!-- Delete Account Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-stone-200 p-6">
                        <DeleteUserForm />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
