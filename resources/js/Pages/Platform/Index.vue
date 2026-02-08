<template>
    <Head title="Platform Settings" />

    <AdminHeader :title="'Platform Dashboard'" />

    <div class="p-6 space-y-8">
        <!-- Platform Management -->
        <div>
            <h2 class="text-lg font-semibold text-stone-900 mb-4">Platform Management</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link
                    href="/platform/companies"
                    class="group p-6 bg-white rounded-lg border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                    <h3 class="text-lg font-semibold text-stone-900 group-hover:text-amber-900 transition-colors">Companies</h3>
                    <p class="mt-1 text-sm text-stone-600">Manage the companies on the platform</p>
                </Link>

                <Link
                    href="/platform/roasts"
                    class="group p-6 bg-white rounded-lg border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                    <h3 class="text-lg font-semibold text-stone-900 group-hover:text-amber-900 transition-colors">Roasts</h3>
                    <p class="mt-1 text-sm text-stone-600">Manage the available roasts</p>
                </Link>

                <Link
                    href="/platform/users"
                    class="group p-6 bg-white rounded-lg border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                    <h3 class="text-lg font-semibold text-stone-900 group-hover:text-amber-900 transition-colors">Users</h3>
                    <p class="mt-1 text-sm text-stone-600">View platform users</p>
                </Link>

                <Link
                    href="/platform/messages"
                    class="group p-6 bg-white rounded-lg border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                    <h3 class="text-lg font-semibold text-stone-900 group-hover:text-amber-900 transition-colors">Messages</h3>
                    <p class="mt-1 text-sm text-stone-600">View and respond to messages</p>
                </Link>
            </div>
        </div>

        <!-- Roast Management -->
        <div>
            <h2 class="text-lg font-semibold text-stone-900 mb-4">Roast Management</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link
                    href="/platform/flavor-notes"
                    class="group p-6 bg-white rounded-lg border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                    <h3 class="text-lg font-semibold text-stone-900 group-hover:text-amber-900 transition-colors">Flavor Notes</h3>
                    <p class="mt-1 text-sm text-stone-600">Manage the flavor notes</p>
                </Link>

                <Link
                    href="/platform/countries"
                    class="group p-6 bg-white rounded-lg border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                    <h3 class="text-lg font-semibold text-stone-900 group-hover:text-amber-900 transition-colors">Countries</h3>
                    <p class="mt-1 text-sm text-stone-600">Manage countries</p>
                </Link>

                <Link
                    href="/platform/elevations"
                    class="group p-6 bg-white rounded-lg border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                    <h3 class="text-lg font-semibold text-stone-900 group-hover:text-amber-900 transition-colors">Elevations</h3>
                    <p class="mt-1 text-sm text-stone-600">Manage elevations</p>
                </Link>

                <Link
                    href="/platform/processes"
                    class="group p-6 bg-white rounded-lg border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                    <h3 class="text-lg font-semibold text-stone-900 group-hover:text-amber-900 transition-colors">Processes</h3>
                    <p class="mt-1 text-sm text-stone-600">Manage processing techniques</p>
                </Link>

                <Link
                    href="/platform/varieties"
                    class="group p-6 bg-white rounded-lg border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                    <h3 class="text-lg font-semibold text-stone-900 group-hover:text-amber-900 transition-colors">Varieties</h3>
                    <p class="mt-1 text-sm text-stone-600">Manage coffee varieties</p>
                </Link>
            </div>
        </div>

        <!-- Cafe Management -->
        <div>
            <h2 class="text-lg font-semibold text-stone-900 mb-4">Cafe Management</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link
                    href="/platform/amenities"
                    class="group p-6 bg-white rounded-lg border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                    <h3 class="text-lg font-semibold text-stone-900 group-hover:text-amber-900 transition-colors">Amenities</h3>
                    <p class="mt-1 text-sm text-stone-600">Manage the amenities available</p>
                </Link>

                <Link
                    href="/platform/brew-methods"
                    class="group p-6 bg-white rounded-lg border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                    <h3 class="text-lg font-semibold text-stone-900 group-hover:text-amber-900 transition-colors">Brew Methods</h3>
                    <p class="mt-1 text-sm text-stone-600">Manage the brew methods available</p>
                </Link>

                <Link
                    href="/platform/drink-options"
                    class="group p-6 bg-white rounded-lg border border-stone-200 hover:border-amber-300 hover:shadow-md transition-all">
                    <h3 class="text-lg font-semibold text-stone-900 group-hover:text-amber-900 transition-colors">Drink Options</h3>
                    <p class="mt-1 text-sm text-stone-600">Manage the drink options available</p>
                </Link>
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
import AdminHeader from './Partials/AdminHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>
