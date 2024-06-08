<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits, computed, ref, watch } from "vue";

const props = defineProps({
    vehicle: Object,
    brandmodels: Array,
    vehicletypes: Array,
    vehiclecolors: Array,
    brands: Array,
});

const form = useForm({
    id: props.vehicle ? props.vehicle.id : "",
    name: props.vehicle ? props.vehicle.name : "",
    code: props.vehicle ? props.vehicle.code : "",
    plate: props.vehicle ? props.vehicle.plate : "",
    year: props.vehicle ? props.vehicle.year : "",
    capacity: props.vehicle ? props.vehicle.capacity : "",
    status: props.vehicle ? props.vehicle.status : "Disponible",
    description: props.vehicle ? props.vehicle.description : "",
    brandmodel_id: props.vehicle ? props.vehicle.brandmodel_id : "",
    vehicletype_id: props.vehicle ? props.vehicle.vehicletype_id : "",
    vehiclecolor_id: props.vehicle ? props.vehicle.vehiclecolor_id : "",
});

const selectedBrandId = ref(props.vehicle ? props.vehicle.brandmodel.brand.id : props.brands.length > 0 ? props.brands[0].id : null);

const filteredBrandModels = computed(() => {
    return props.brandmodels.filter(
        (brandmodel) => brandmodel.brand_id === selectedBrandId.value
    );
});

watch(selectedBrandId, (newBrandId) => {
    if (filteredBrandModels.value.length > 0) {
        form.brandmodel_id = filteredBrandModels.value[0].id;
    } else {
        form.brandmodel_id = "";
    }
});


const submit = () => {
    if (props.vehicle) {
        form.put(route("vehicles.update", props.vehicle), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("vehicles.store"), {
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
            {{ form.id == 0 ? "Registrar Vehículo" : "Actualizar Vehículo" }}
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
                    <div class="col-span-6 sm:col-span-2">
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
                    <div class="col-span-3 sm:col-span-2">
                        <InputLabel value="Código" class="text-slate-500 font-bold"/>
                        <TextInput
                            class="w-full"
                            v-model="form.code"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.code"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <InputLabel value="Placa" class="text-slate-500 font-bold"/>
                        <TextInput
                            class="w-full"
                            v-model="form.plate"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.plate"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <InputLabel value="Año" class="text-slate-500 font-bold"/>
                        <TextInput
                            type="number"
                            class="w-full"
                            v-model="form.year"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.year"
                        />
                    </div>
                    <div class="col-span-3 sm:col-span-2">
                        <InputLabel value="Capacidad" class="text-slate-500 font-bold"/>
                        <TextInput
                            type="number"
                            class="w-full"
                            v-model="form.capacity"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.capacity"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-2">
                        <InputLabel value="Estado" class="text-slate-500 font-bold"/>
                        <select
                            v-model="form.status"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option value="Disponible">Disponible</option>
                            <option value="No Disponible">No Disponible</option>
                            <option value="En Mantenimiento">En Mantenimiento</option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.status"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Marca" class="text-slate-500 font-bold"/>
                        <select
                            v-model="selectedBrandId"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="brand in props.brands" :key="brand.id" :value="brand.id">
                                {{ brand.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.brand_id"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Modelo" class="text-slate-500 font-bold"/>
                        <select
                            v-model="form.brandmodel_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="brandmodel in filteredBrandModels" :key="brandmodel.id" :value="brandmodel.id">
                                {{ brandmodel.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.brandmodel_id"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Tipo" class="text-slate-500 font-bold"/>
                        <select
                            v-model="form.vehicletype_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="vehicletype in props.vehicletypes" :key="vehicletype.id" :value="vehicletype.id">
                                {{ vehicletype.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.vehicletype_id"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Color" class="text-slate-500 font-bold"/>
                        <select
                            v-model="form.vehiclecolor_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option v-for="vehiclecolor in props.vehiclecolors" :key="vehiclecolor.id" :value="vehiclecolor.id">
                                {{ vehiclecolor.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.vehiclecolor_id"
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
