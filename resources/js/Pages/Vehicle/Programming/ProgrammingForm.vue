<script setup>
import { ref, defineProps, defineEmits } from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    vehicle: Object,
    routes: Array,
});

const emit = defineEmits(["close-modal"]);

const form = useForm({
    vehicle_id: props.vehicle.id,
    route_id: '',
    date_from: "",
    date_to: "",
});

const submit = () => {
    form.post(route("vehicleroutes.store"), {
        preserveScroll: true,
        onSuccess: () => emit("close-modal"),
    });
};

</script>
<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-500 font-bold inline-flex items-center">
            Programación de Rutas
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
                        <InputLabel value="Ruta" class="text-slate-500 font-bold"/>
                        <select
                            v-model="form.route_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="route in props.routes" :key="route.id" :value="route.id">
                                {{ route.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.route_id"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Desde" class="text-slate-500 font-bold"/>
                        <TextInput
                            type="date"
                            class="w-full"
                            v-model="form.date_from"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.date_from"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Hasta" class="text-slate-500 font-bold"/>
                        <TextInput
                            type="date"
                            class="w-full"
                            v-model="form.date_to"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.date_to"
                        />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    class="bg-blue-100 hover:bg-blue-200 text-slate-500 font-bold px-4 py-2 rounded-md mr-2 shadow-abajo-1"
                    :disabled="form.processing"
                >
                    Registrar
                </button>
            </div>
        </div>
    </form>
</template>
