<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    vehiclecolor: Object,
});

const form = useForm({
    id: props.vehiclecolor ? props.vehiclecolor.id : "",
    name: props.vehiclecolor ? props.vehiclecolor.name : "",
    rgb: props.vehiclecolor ? props.vehiclecolor.rgb : "",
    description: props.vehiclecolor ? props.vehiclecolor.description : "",
});

const submit = () => {
    if (props.vehiclecolor) {
        form.put(route("vehiclecolors.update", props.vehiclecolor), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("vehiclecolors.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

const emit = defineEmits(["close-modal"]);

</script>

<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-500 font-bold inline-flex items-center">
            {{ form.id == 0 ? "Registrar Color de Vehículo" : "Actualizar Color de Vehículo" }}
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
                            @input="form.name = toTitleCase(form.name)"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.name"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-1">
                        <InputLabel value="Color RGB" class="text-slate-500 font-bold"/>
                        <input
                            type="color"
                            v-model="form.rgb"
                            class="w-full h-10 p-1 rounded-md border border-slate-300"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.rgb"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Descripción" class="text-slate-500 font-bold"/>
                        <TextArea
                            class="w-full h-28"
                            v-model="form.description"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.description"
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
