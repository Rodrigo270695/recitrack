<script setup>
import { ref, defineProps, defineEmits } from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    vehicleroute: Object,
    statuses: Array,
});

const visible = ref(false);

const emit = defineEmits(["close-modal"]);

const form = useForm({
    id: props.vehicleroute.id,
    vehicle_id: props.vehicleroute.vehicle_id,
    route_id: props.vehicleroute.route_id,
    description: props.vehicleroute.description,
    statusroute_id: props.vehicleroute.statusroute_id ,
    date_from: '',
    date_to: ''
});

const submit = () => {
    form.put(route("vehicleroutes.update", props.vehicleroute), {
        preserveScroll: true,
        onSuccess: () => emit("close-modal"),
    });
};

</script>
<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-500 font-bold inline-flex items-center">
            Actualizar Programación
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
                    <div class=" flex col-span-6 sm:col-span-6">
                        <input type="checkbox" v-model="visible" class="accent-slate-500 h-5 w-5 rounded-md" >
                        <InputLabel value="Masivo" class="text-slate-500 font-bold ml-2" />
                    </div>
                    <div v-if="visible" class="col-span-6 sm:col-span-3">
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
                    <div v-if="visible" class="col-span-6 sm:col-span-3">
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
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Marca" />
                        <select
                            id="select"
                            v-model="form.statusroute_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="status in statuses"
                                :key="status.id"
                                :value="status.id"
                            >
                                {{ status.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.statusroute_id"
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
                    Actualizar
                </button>
            </div>
        </div>
    </form>
</template>
