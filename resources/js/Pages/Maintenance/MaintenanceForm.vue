<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    maintenance: Object,
});

const form = useForm({
    id: props.maintenance ? props.maintenance.id : "",
    name: props.maintenance ? props.maintenance.name : "",
    initial_date: props.maintenance ? props.maintenance.initial_date : "",
    final_date: props.maintenance ? props.maintenance.final_date : "",
});

const submit = () => {
    if (props.maintenance) {
        form.put(route("maintenances.update", props.maintenance), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("maintenances.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

</script>

<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-500 font-bold inline-flex items-center">
            {{ form.id == 0 ? "Registrar Mantenimiento" : "Actualizar Mantenimiento" }}
        </div>
        <button @click="emit('close-modal')" >
            <v-icon
                class="text-white rounded-md bg-red-400 hover:bg-red-500"
                name="io-close"
                scale="1.5"
            />
        </button>
    </div>
    <form @submit.prevent="submit" >
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Nombre" class="text-slate-500 font-bold"/>
                        <TextInput
                            class="w-full"
                            v-model="form.name"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.name"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Fecha Inicial" class="text-slate-500 font-bold"/>
                        <TextInput
                            type="date"
                            class="w-full"
                            v-model="form.initial_date"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.initial_date"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Fecha Final" class="text-slate-500 font-bold"/>
                        <TextInput
                            type="date"
                            class="w-full"
                            v-model="form.final_date"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.final_date"
                        />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    class="bg-blue-100 hover:bg-blue-200 text-slate-500 font-bold px-4 py-2 rounded-md mr-2 shadow-abajo-1"
                    :disabled="form.processing"
                >
                    {{ form.id == 0 ? "Registrar" : "Actualizar" }}
                </button>
            </div>
        </div>
    </form>
</template>
