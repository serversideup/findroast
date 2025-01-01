<template>
    <RoastMoreInfo :roast="roast" />
</template>

<script setup>
import RoastMoreInfo from './Partials/RoastMoreInfo.vue';
const props = defineProps({
    roast: Object
});
</script>