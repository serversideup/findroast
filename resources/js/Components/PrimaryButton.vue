<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'gray',
        validator: (value) => ['gray', 'amber'].includes(value),
    },
});

const buttonClasses = computed(() => {
    const baseClasses = 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150';

    const variantClasses = {
        gray: 'bg-gray-800 hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:ring-gray-500',
        amber: 'bg-amber-700 hover:bg-amber-800 focus:bg-amber-800 active:bg-amber-900 focus:ring-amber-500',
    };

    return `${baseClasses} ${variantClasses[props.variant]}`;
});
</script>

<template>
    <button :class="buttonClasses">
        <slot />
    </button>
</template>
