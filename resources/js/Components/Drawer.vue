<template>
    <TransitionRoot as="template" :show="show">
        <Dialog as="div" class="relative z-[9999]" @close="close">
            <div class="fixed inset-0" />

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                        <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700" enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="translate-x-full">
                            <DialogPanel class="pointer-events-auto w-screen"
                                :class="{
                                    'max-w-md': maxWidth === 'md',
                                    'max-w-lg': maxWidth === 'lg',
                                    'max-w-xl': maxWidth === 'xl',
                                }">
                                <div class="flex h-full flex-col overflow-y-auto shadow-xl"
                                :class="[bgColor, { 'py-6' : !$slots.footer, 'pt-6' : $slots.footer }]">
                                    <div class="px-4 sm:px-6">
                                        <div class="flex items-start justify-between">
                                            <DialogTitle>
                                                <div class="flex items-start">
                                                    <div class="w-10 h-10 flex flex-shrink-0 items-center justify-center mr-4" v-if="$slots.icon">
                                                        <slot name="icon" />
                                                    </div>

                                                    <div class="flex flex-col">
                                                        <h2 class="text-base font-semibold text-gray-900">
                                                            <slot name="title" />
                                                        </h2>
                                                        <p class="text-sm text-[#475467] mt-1" v-if="$slots.subtitle">
                                                            <slot name="subtitle" />
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                            </DialogTitle>
                                            <div class="ml-3 flex h-7 items-center">
                                                <button type="button" class="relative rounded-md text-gray-400 hover:text-gray-500 focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:outline-hidden" @click="close">
                                                    <span class="absolute -inset-2.5" />
                                                    <span class="sr-only">Close panel</span>
                                                    <XMarkIcon class="size-6" aria-hidden="true" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative mt-6 flex-1 overflow-y-auto px-4 sm:px-6">
                                        <slot name="content" />
                                    </div>

                                    <div v-if="$slots.footer">
                                        <slot name="footer" />
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
  
<script setup>
import { onMounted, onUnmounted } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: 'md',
    },
    bgColor: {
        type: String,
        default: 'bg-white',
    },
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
}

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));
</script>