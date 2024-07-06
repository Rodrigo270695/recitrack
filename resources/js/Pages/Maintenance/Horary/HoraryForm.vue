<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits, ref } from "vue";

const props = defineProps({
    maintenance: Object,
    vehicleoccupants: Array,
    horary: Object,
});

const form = useForm({
    id: props.horary ? props.horary.id : "",
    day: props.horary ? props.horary.day : "",
    type: props.horary ? props.horary.type : "",
    start_time: props.horary ? props.horary.start_time : "",
    end_time: props.horary ? props.horary.end_time : "",
    maintenance_id: props.maintenance.id,
    vehicleoccupant_id: props.horary ? props.horary.vehicleoccupant_id : "",
});

const submit = () => {
    if (props.horary) {
        form.put(route("maintenances.horaries.update", props.horary), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("maintenances.horaries.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

const dias = [
    { id: 1, dia: "Lunes" },
    { id: 2, dia: "Martes" },
    { id: 3, dia: "Miércoles" },
    { id: 4, dia: "Jueves" },
    { id: 5, dia: "Viernes" },
    { id: 6, dia: "Sábado" },
    { id: 7, dia: "Domingo" }
];

const types = [
    { id: 1, name: "Limpieza" },
    { id: 2, name: "Reparación" },
];



</script>

<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-500 font-bold inline-flex items-center">
            {{ props.horary ? 'Actualizar Horario' : 'Registrar Horario' }}
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
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Día" />
                        <select
                            v-model="form.day"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="dia in dias"
                                :key="dia.id"
                                :value="dia.dia"
                            >
                                {{ dia.dia }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.day"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Tipo" />
                        <select
                            v-model="form.type"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="type in types"
                                :key="type.id"
                                :value="type.name"
                            >
                                {{ type.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.type"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Hora de Inicio" />
                        <TextInput
                            type="time"
                            class="w-full"
                            v-model="form.start_time"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.start_time"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Hora de Fin" />
                        <TextInput
                            type="time"
                            class="w-full"
                            v-model="form.end_time"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.end_time"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Ocupante del Vehículo" />
                        <select
                            v-model="form.vehicleoccupant_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="vehicleoccupant in vehicleoccupants"
                                :key="vehicleoccupant.id"
                                :value="vehicleoccupant.id"
                            >
                                {{ vehicleoccupant.vehicle.name}}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.vehicleoccupant_id"
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
