<script setup>

import { onMounted } from 'vue'

const props = defineProps({
    message: String,
    duration: {
        type: Number,
        default: 5000
    },
    action: String
})

const emit = defineEmits(['remove'])

onMounted(() => {
    setTimeout(() => emit('remove'), props.duration);
})

</script>

<template>

    <div id="toast-default" class="flex items-center w-full max-w-xs p-4 text-gray-500 rounded-lg shadow white:text-gray-400 white:bg-gray-800 mt-14" role="alert" :class="{ ' bg-green-200': action == 'success', ' bg-red-200': action == 'danger', ' bg-orange-200': action == 'warning'}">

        <div v-if="action == 'success'" class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-100 dark:text-green-600">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>
            <span class="sr-only">Check icon</span>
        </div>

        <div v-if="action == 'danger'" class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-100 dark:text-red-600">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
            </svg>
            <span class="sr-only">Error icon</span>
        </div>

        <div v-if="action == 'warning'" class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-100 dark:text-orange-600">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
            </svg>
            <span class="sr-only">Warning icon</span>
        </div>

        <div class="ml-3 text-sm font-normal">{{ message }}</div>
        <button @click="emit('remove')" type="button" class="ml-auto -mx-1.5 -my-1.5 text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 white:text-gray-500 white:hover:text-white white:bg-gray-800 white:hover:bg-gray-700" data-dismiss-target="#toast-default" aria-label="Close" :class="{ ' bg-green-200': action == 'success', ' bg-red-200': action == 'danger', ' bg-orange-200': action == 'warning'}">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>

</template>
