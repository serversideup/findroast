<template>
    <Head :title="company.name" />

    <AdminHeader 
        :title="company.name"
        :breadcrumbs="[
            { label: 'Platform Settings', to: '/platform' },
            { label: 'Companies', to: '/platform/companies' },
            { label: company.name, to: '#' }
        ]">
        <template #actions>
            <SecondaryLink :href="'/platform/companies/'+company.id+'/cafes'" class="mr-3">
                View Cafes
            </SecondaryLink>
            <PrimaryLink :href="'/platform/companies/'+company.id+'/edit'">
                Edit
            </PrimaryLink>
        </template>
    </AdminHeader>

    <div class="max-w-screen-xl mx-auto mt-5">
        <div class="max-w-screen-md">
            <div class="px-4 sm:px-0">
                <h3 class="text-base font-semibold leading-7 text-gray-900">
                    Profile
                </h3>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                    Basic information regarding the company.
                </p>
            </div>
            <div class="mt-6 border-t border-gray-100">
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Name
                        </dt>
                        <dd
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.name }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Status
                        </dt>
                        <dd
                            class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.status }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Roasts their own coffee
                        </dt>
                        <dd
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.roaster ? 'Yes' : 'No' }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Has a subscription service
                        </dt>
                        <dd
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.subscription ? 'Yes' : 'No' }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Description
                        </dt>
                        <dd
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.description }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <hr/>

        <div class="max-w-screen-md mt-8">
            <div class="px-4 sm:px-0">
                <h3 class="text-base font-semibold leading-7 text-gray-900">
                    Location
                </h3>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                    Where is this company located?
                </p>
            </div>
            <div class="mt-6 border-t border-gray-100">
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Country
                        </dt>
                        <dd
                            class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.country }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            City
                        </dt>
                        <dd
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.city }}
                        </dd>
                    </div>
                    <div v-if="company.country == 'US'" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            State
                        </dt>
                        <dd
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.state }}
                        </dd>
                    </div>
                    <div v-if="company.country == 'AU'" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Territory
                        </dt>
                        <dd
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.territory }}
                        </dd>
                    </div>
                    <div v-if="company.country == 'CA'" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Province
                        </dt>
                        <dd
                            class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.province }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <hr/>
        
        <div class="max-w-screen-md mt-8">
            <div class="px-4 sm:px-0">
                <h3 class="text-base font-semibold leading-7 text-gray-900">
                    Online Presence
                </h3>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">
                    Where to find the company online.
                </p>
            </div>
            <div class="mt-6 border-t border-gray-100">
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Website
                        </dt>
                        <dd
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.website }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Instagram
                        </dt>
                        <dd
                            class="capitalize mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.instagram_url }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Facebook
                        </dt>
                        <dd
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.facebook_url }}
                        </dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">
                            Twitter
                        </dt>
                        <dd
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ company.twitter_url }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    layout: AppLayout
};
</script>

<script setup>
import AdminHeader from '../Partials/AdminHeader.vue';
import PrimaryLink from '@/Components/PrimaryLink.vue';
import SecondaryLink from '@/Components/SecondaryLink.vue';
import { computed } from "vue";
import { Head, usePage } from "@inertiajs/vue3";

const company = computed(() => usePage().props.company);
</script>
