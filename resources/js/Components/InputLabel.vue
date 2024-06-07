<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label class="block text-sm text-slate-500 font-semibold">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
