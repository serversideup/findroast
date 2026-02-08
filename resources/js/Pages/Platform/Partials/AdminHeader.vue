<template>
    <div class="border-b border-stone-200 bg-white">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-semibold text-stone-900">
                    {{ title }}
                </h1>
                <span v-if="count !== undefined" class="text-sm text-stone-500">
                    {{ count }} {{ count === 1 ? 'item' : 'items' }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                <slot name="actions"/>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    title: String,
    count: Number
});
</script>
