<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="shadow-abajo-1 inline-flex items-center px-4 py-2 bg-3D-50 border border-transparent rounded-md font-bold text-xs text-slate-500 uppercase tracking-widest hover:bg-blue-200 focus:bg-blue-300 active:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
