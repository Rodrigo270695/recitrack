<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import MapComponent from "@/Components/MapComponent.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";
import 'leaflet/dist/leaflet.css';

const props = defineProps({
    zoneCoords: Array,
    zone: Object,
    lastCoord: Object,
});

const form = useForm({
    zone_id: props.zone.id,
    latitude: props.lastCoord ? props.lastCoord.latitude : null,
    longitude: props.lastCoord ? props.lastCoord.longitude : null,
});

const submit = () => {
    form.post(route("zone.coords.store"), {
        preserveScroll: true,
        onSuccess: () => emit("close-modal"),
    });
};

const emit = defineEmits(["close-modal"]);

const updateLat = (newLat) => {
  form.latitude = newLat;
};

const updateLng = (newLng) => {
  form.longitude = newLng;
};

</script>

<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-500 font-bold inline-flex items-center">
            Registrar Coordenada
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
                        <InputLabel value="Latitud" class="text-slate-500 font-bold"/>
                        <TextInput
                            class="w-full"
                            v-model="form.latitude"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.latitude"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Longitud" class="text-slate-500 font-bold"/>
                        <TextInput
                            class="w-full"
                            v-model="form.longitude"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.longitude"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6 shadow-abajo-2 rounded-md">
                        <div class="p-3">
                            <MapComponent
                                :initial-lat="form.latitude"
                                :initial-lng="form.longitude"
                                :zone-coords="props.zoneCoords"
                                @update:lat="updateLat"
                                @update:lng="updateLng"
                            />
                        </div>
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
