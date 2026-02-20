<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-stone-300 text-stone-900 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm"
        v-model="model"
        ref="input"
    />
</template>
