<template>
    <Disclosure
        as="div"
        class="px-4"
        v-slot="{ open }"
        :default-open="defaultOpen"
    >
        <DisclosureButton class="flex w-full items-center justify-between py-3 text-left">
            <span class="text-sm font-medium text-stone-900">
                {{ label }}
                <span
                    v-if="modelValue.length > 0"
                    class="ml-1.5 text-[11px] font-semibold text-amber-700"
                >
                    ({{ modelValue.length }})
                </span>
            </span>
            <ChevronDownIcon
                :class="['h-4 w-4 text-stone-400 transition-transform', open ? 'rotate-180' : '']"
            />
        </DisclosureButton>

        <DisclosurePanel class="pb-3">
            <!-- Search within section -->
            <div class="relative mb-2">
                <MagnifyingGlassIcon class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-stone-400" />
                <input
                    v-model="searchQuery"
                    type="text"
                    :placeholder="searchPlaceholder"
                    class="w-full text-base border border-stone-200 rounded-lg pl-8 pr-3 py-1.5 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder:text-stone-400"
                />
            </div>

            <!-- Options -->
            <div class="max-h-48 overflow-y-auto -mx-1 space-y-0.5">
                <label
                    v-for="option in filteredOptions"
                    :key="option.id"
                    class="flex items-center gap-2.5 px-2 py-2 rounded-md active:bg-stone-50 cursor-pointer"
                >
                    <input
                        type="checkbox"
                        :value="option.id"
                        :checked="modelValue.includes(option.id)"
                        @change="toggleOption(option.id)"
                        class="rounded border-stone-300 text-amber-700 focus:ring-amber-600 h-4 w-4"
                    />
                    <span class="flex-1 text-sm text-stone-700">
                        <slot name="option-label" :option="option">
                            {{ option.name }}
                        </slot>
                    </span>
                    <span class="text-xs text-stone-400 tabular-nums">{{ option.roasts_count }}</span>
                </label>

                <p
                    v-if="filteredOptions.length === 0"
                    class="text-xs text-stone-400 text-center py-3"
                >
                    No matches found
                </p>
            </div>
        </DisclosurePanel>
    </Disclosure>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { ChevronDownIcon, MagnifyingGlassIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    label: { type: String, required: true },
    options: { type: Array, default: () => [] },
    modelValue: { type: Array, default: () => [] },
    searchPlaceholder: { type: String, default: 'Search...' },
    defaultOpen: { type: Boolean, default: false }
});

const emit = defineEmits(['update:modelValue']);

const searchQuery = ref('');

const filteredOptions = computed(() => {
    if (!searchQuery.value) return props.options;
    const query = searchQuery.value.toLowerCase();
    return props.options.filter(opt => opt.name.toLowerCase().includes(query));
});

const toggleOption = (id) => {
    const current = [...props.modelValue];
    const idx = current.indexOf(id);
    if (idx > -1) {
        current.splice(idx, 1);
    } else {
        current.push(id);
    }
    emit('update:modelValue', current);
};
</script>
