<template>
    <Popover class="relative flex-shrink-0" v-slot="{ open, close }">
        <PopoverButton
            :class="[
                'inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg border transition-colors whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-amber-500/40',
                modelValue.length > 0
                    ? 'bg-amber-50 border-amber-300 text-amber-800 font-medium'
                    : 'bg-white border-stone-200 text-stone-600 hover:border-stone-300 hover:text-stone-800'
            ]"
        >
            {{ label }}
            <span
                v-if="modelValue.length"
                class="bg-amber-600 text-white text-[10px] font-bold rounded-full h-4 min-w-[16px] flex items-center justify-center px-1"
            >
                {{ modelValue.length }}
            </span>
            <ChevronDownIcon
                :class="['h-3 w-3 transition-transform', open ? 'rotate-180' : '']"
            />
        </PopoverButton>

        <!-- Mobile backdrop -->
        <div
            v-if="open"
            class="fixed inset-0 bg-black/25 z-40 lg:hidden"
            aria-hidden="true"
        />

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <PopoverPanel
                :class="[
                    'fixed bottom-0 left-0 right-0 rounded-t-2xl',
                    'lg:absolute lg:bottom-auto lg:top-full lg:mt-1.5 lg:rounded-xl lg:w-72',
                    align === 'right' ? 'lg:right-0 lg:left-auto' : 'lg:left-0 lg:right-auto',
                    'bg-white shadow-xl border border-stone-200 z-50'
                ]"
            >
                <!-- Mobile drag handle -->
                <div class="flex justify-center pt-2 pb-1 lg:hidden">
                    <div class="w-10 h-1 bg-stone-300 rounded-full" />
                </div>

                <div class="px-3 pb-3 lg:p-3">
                    <!-- Mobile header -->
                    <div class="flex items-center justify-between mb-2 lg:hidden">
                        <h3 class="font-semibold text-stone-900">{{ label }}</h3>
                        <button
                            type="button"
                            @click="close"
                            class="p-1 text-stone-400 hover:text-stone-600 rounded"
                        >
                            <XMarkIcon class="h-5 w-5" />
                        </button>
                    </div>

                    <!-- Search within filter -->
                    <div class="relative mb-2">
                        <MagnifyingGlassIcon class="absolute left-2.5 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-stone-400" />
                        <input
                            v-model="searchQuery"
                            type="text"
                            :placeholder="searchPlaceholder"
                            class="w-full text-sm border border-stone-200 rounded-lg pl-8 pr-3 py-1.5 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder:text-stone-400"
                        />
                    </div>

                    <!-- Options list -->
                    <div class="overflow-y-auto max-h-[50vh] lg:max-h-64 -mx-1">
                        <label
                            v-for="option in filteredOptions"
                            :key="option.id"
                            class="flex items-center gap-2 px-2 py-1.5 rounded-md hover:bg-stone-50 cursor-pointer text-sm"
                        >
                            <input
                                type="checkbox"
                                :value="option.id"
                                :checked="modelValue.includes(option.id)"
                                @change="toggleOption(option.id)"
                                class="rounded border-stone-300 text-amber-700 focus:ring-amber-600 h-3.5 w-3.5"
                            />
                            <span class="flex-1 text-stone-700 truncate">
                                <slot name="option-label" :option="option">
                                    {{ option.name }}
                                </slot>
                            </span>
                            <span class="text-[11px] text-stone-400 tabular-nums">
                                {{ option.roasts_count }}
                            </span>
                        </label>

                        <p
                            v-if="filteredOptions.length === 0"
                            class="text-xs text-stone-400 text-center py-4"
                        >
                            No matches found
                        </p>
                    </div>

                    <!-- Selected count footer (mobile) -->
                    <div v-if="modelValue.length > 0" class="mt-2 pt-2 border-t border-stone-100 lg:hidden">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-stone-500">{{ modelValue.length }} selected</span>
                            <button
                                type="button"
                                @click="clearAll"
                                class="text-xs text-amber-700 font-medium hover:text-amber-800"
                            >
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </PopoverPanel>
        </transition>
    </Popover>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue';
import { ChevronDownIcon, XMarkIcon, MagnifyingGlassIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    label: { type: String, required: true },
    options: { type: Array, default: () => [] },
    modelValue: { type: Array, default: () => [] },
    searchPlaceholder: { type: String, default: 'Search...' },
    align: { type: String, default: 'left' }
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

const clearAll = () => {
    emit('update:modelValue', []);
};
</script>
