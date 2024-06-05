<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";


const props = defineProps({
    pagination: Object,
});
</script>

<template>
    <nav class="relative flex justify-between">
        <div
            class="flex items-center justify-center px-3 py-2 text-sm rounded-lg text-sky-900"
        >
            Mostrando {{ pagination.from }} a {{ pagination.to }} de
            {{ pagination.total }} registros
        </div>
        <div class="flex justify-end">
            <template v-for="item in pagination.links" :key="item.label">
                <Link
                    preserve-scroll=""
                    :href="`${item.url ?? ''}${
                        pagination.texto ? '&texto=' + pagination.texto : ''
                    }`"
                    v-html="item.label"
                    class="flex items-center justify-center px-3 py-2 text-sm rounded-lg text-sky-900"
                    :class="{
                        'bg-gray-400 text-white': item.active,
                        '!text-sky-400 cursor-auto': !item.url,
                    }"
                />
            </template>
        </div>
    </nav>
</template>
