<template>
    <div>
        <select
            :class="[
                'form-select block w-full mt-1',
                {
                    'border-red-500': error,
                    'border-gray-300': !error,
                },
            ]"
            v-bind="$attrs"
            :value="modelValue"
            @change="updateValue($event.target.value)"
        >
            <slot></slot>
        </select>
        <p v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</p>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    modelValue: [String, Number],
    error: String,
});

const emit = defineEmits(["update:modelValue"]);

const updateValue = (value) => {
    emit("update:modelValue", value);
};
</script>

<style scoped>
.form-select {
    border-radius: 0.375rem;
    border-width: 1px;
    padding: 0.5rem 0.75rem;
    transition: border-color 0.2s;
}
.form-select:focus {
    outline: none;
    border-color: #3182ce;
    box-shadow: 0 0 0 1px #3182ce;
}
</style>
