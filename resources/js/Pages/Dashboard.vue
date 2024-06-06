<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-bold text-xl text-slate-500 ">
                    Dashboard
                </h2>
            </div>
        </template>
        <div class="py-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sed iste ducimus repellat numquam libero officiis accusamus possimus, consectetur minima blanditiis eos ea recusandae excepturi placeat aliquid corporis quia accusantium quaerat!
                </div>
            </div>
        </div>
    </AppLayout>
</template>
