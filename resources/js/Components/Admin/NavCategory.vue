<template>
    <Disclosure v-slot="{ open }" :defaultOpen="!collapsed">
        <div class="px-2 mb-3">
            <!-- Category Header -->
            <DisclosureButton
                v-if="!collapsed"
                class="flex items-center justify-between w-full px-3 py-2 text-xs font-bold uppercase tracking-wider text-stone-800 bg-stone-100 hover:bg-stone-200 rounded-lg transition-colors group"
            >
                <div class="flex items-center gap-2.5">
                    <component :is="icon" class="h-4 w-4 text-stone-700" />
                    <span>{{ title }}</span>
                </div>
                <ChevronDownIcon
                    :class="[
                        'h-4 w-4 text-stone-600 transition-transform',
                        open ? 'rotate-180' : ''
                    ]"
                />
            </DisclosureButton>

            <!-- Collapsed state - just show icon -->
            <div
                v-else
                class="flex items-center justify-center px-2 py-2 bg-stone-100 rounded-lg"
                :title="title"
            >
                <component :is="icon" class="h-5 w-5 text-stone-700" />
            </div>

            <!-- Category Items -->
            <DisclosurePanel class="space-y-0.5 mt-2 ml-2 pl-3 border-l-2 border-stone-200">
                <Link
                    v-for="item in items"
                    :key="item.href"
                    :href="item.href"
                    :class="[
                        'flex items-center gap-2 px-3 py-1.5 text-sm rounded-md transition-colors group',
                        collapsed ? 'justify-center' : '',
                        isActive(item.href)
                            ? 'bg-amber-100 text-amber-900 font-medium border-l-2 border-amber-600 -ml-[14px] pl-[14px]'
                            : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900'
                    ]"
                    :title="collapsed ? item.label : ''"
                >
                    <component
                        :is="item.icon"
                        :class="[
                            'h-3.5 w-3.5 flex-shrink-0',
                            isActive(item.href)
                                ? 'text-amber-700'
                                : 'text-stone-400 group-hover:text-stone-600'
                        ]"
                    />
                    <span v-if="!collapsed" class="truncate text-[13px]">{{ item.label }}</span>
                </Link>
            </DisclosurePanel>
        </div>
    </Disclosure>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { ChevronDownIcon } from '@heroicons/vue/20/solid';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    icon: {
        type: Object,
        required: true
    },
    title: {
        type: String,
        required: true
    },
    items: {
        type: Array,
        required: true
    },
    collapsed: {
        type: Boolean,
        default: false
    }
});

const page = usePage();
const currentUrl = computed(() => page.url);

const isActive = (href) => {
    return currentUrl.value === href || currentUrl.value.startsWith(href + '/');
};
</script>
