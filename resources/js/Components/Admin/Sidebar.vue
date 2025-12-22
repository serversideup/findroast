<template>
    <div class="w-64 px-4 py-20 bg-white h-screen overflow-y-auto border-r border-gray-200 flex flex-col gap-2">
        <h3 class="text-sm font-medium text-gray-500">Company Management</h3>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/companies">
            Companies
        </Link>
        <h3 class="text-sm font-medium text-gray-500">Cafe Management</h3>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/cafes">
            Cafes
        </Link>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/amenities">
            Amenities
        </Link>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/brew-methods">
            Brew Methods
        </Link>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/drink-options">
            Drink Options
        </Link>
        <h3 class="text-sm font-medium text-gray-500">Roast Management</h3>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/roasts">
            Roasts
        </Link>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/flavor-notes">
            Flavor Notes
        </Link>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/countries">
            Countries
        </Link>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/elevations">
            Elevations
        </Link>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/processes">
            Processes
        </Link>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/varieties">
            Varieties
        </Link>
        <h3 class="text-sm font-medium text-gray-500">Platform Management</h3>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/users">
            Users
        </Link>
        <Link class="p-2 hover:bg-gray-100 rounded-md" href="/platform/messages">
            Messages
        </Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>