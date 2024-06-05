<script setup>
import { usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import ToastListItem from './ToastListItem.vue';
import { onUnmounted } from 'vue';
import toast from '@/Stores/toast';

const page = usePage();

let removeFinishEventListener = Inertia.on('finish', () => {
    if (page.props.toast && page.props.toast[0]) {
        toast.add({
            message: page.props.toast[0],
            action: page.props.toast[1]
        });
    }
});

onUnmounted(() => removeFinishEventListener());

function remove(index) {
    toast.remove(index);
}
</script>

<template>
    <TransitionGroup tag="div" enter-active-class="duration-500" enter-from-class="translate-x-full opacity-0" leave-active-class="duration-500" leave-to-class="translate-x-full opacity-0" class="fixed top-4 right-4 z-50 space-y-4 w-full max-w-xs">
        <ToastListItem v-for="(item, index) in toast.items" :key="item.key" :message="item.message" :action="item.action" :duration="5000" @remove="remove(index)" />
    </TransitionGroup>
</template>
