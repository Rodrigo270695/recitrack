<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

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
        ref="input"
        class="hover:border-slate-200 focus:border-blue-50 bg-3D-50 h-10 px-5 pr-16 rounded-lg focus:outline-none shadow-abajo-2 text-slate-500 border-slate-200 font-bold"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
