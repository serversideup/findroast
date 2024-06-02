<template>
    <div class="max-w-screen-xl mx-auto mt-8 lg:px-8">
        <div>
            <nav class="sm:hidden" aria-label="Back">
                <a
                    href="#"
                    class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <ChevronLeftIcon
                        class="-ml-1 mr-1 h-5 w-5 flex-shrink-0 text-gray-400"
                        aria-hidden="true" />
                    Back
                </a>
            </nav>
            <nav class="hidden sm:flex" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li v-for="(link, linkIndex) in breadcrumbs">
                        <div class="flex items-center">
                            <ChevronRightIcon
                                v-if="linkIndex > 0"
                                class="h-5 w-5 flex-shrink-0 text-gray-400"
                                aria-hidden="true" />
                            <Link
                                :href="link.to"
                                class="text-sm font-medium text-gray-500 hover:text-gray-700"
                                :class="{
                                    'ml-4': linkIndex > 0
                                }">
                                {{ link.label }}
                            </Link>
                        </div>
                        
                    </li>
                </ol>
            </nav>
        </div>
        <div class="mt-2 md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2
                    class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    {{ title }}
                </h2>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <slot name="actions"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
    title: String,
    breadcrumbs: Array
});
</script>
