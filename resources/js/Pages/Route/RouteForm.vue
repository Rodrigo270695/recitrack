<script setup>
import { ref, onMounted } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";
import axios from 'axios';

const props = defineProps({
    route: Object,
});

const form = useForm({
    id: props.route ? props.route.id : "",
    name: props.route ? props.route.name : "",
    latitude_start: props.route ? props.route.latitude_start : "",
    longitude_start: props.route ? props.route.longitude_start : "",
    latitude_end: props.route ? props.route.latitude_end : "",
    longitude_end: props.route ? props.route.longitude_end : "",
    status: props.route ? props.route.status : true,
});

const map = ref(null);
const markers = ref([]);
const polyline = ref(null);
const API_KEY = '5b3ce3597851110001cf6248a3a577bdaabf4a7f83518a13d25492b3';

async function getRoute(start, end) {
    const url = `https://api.openrouteservice.org/v2/directions/driving-car?api_key=${API_KEY}&start=${start.lng},${start.lat}&end=${end.lng},${end.lat}`;
    try {
        const response = await axios.get(url);
        const coords = response.data.features[0].geometry.coordinates.map(coord => [coord[1], coord[0]]);
        return coords;
    } catch (error) {
        console.error('Error fetching route:', error);
        return [];
    }
}

async function updateRoute() {
    if (markers.value.length === 2) {
        const latlngs = await getRoute(markers.value[0].getLatLng(), markers.value[1].getLatLng());
        if (polyline.value) {
            polyline.value.setLatLngs(latlngs);
        } else {
            polyline.value = L.polyline(latlngs, { color: 'red' }).addTo(map.value);
        }
    }
}

onMounted(() => {
    map.value = L.map('map').setView([0, 0], 1);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map.value);

    function updateForm() {
        form.latitude_start = markers.value[0].getLatLng().lat;
        form.longitude_start = markers.value[0].getLatLng().lng;
        if (markers.value.length > 1) {
            form.latitude_end = markers.value[1].getLatLng().lat;
            form.longitude_end = markers.value[1].getLatLng().lng;
        }
    }

    async function onMapClick(e) {
        if (markers.value.length < 2) {
            const marker = L.marker(e.latlng, { draggable: true }).addTo(map.value);
            marker.on('dragend', async () => {
                await updateRoute();
                updateForm();
            });
            markers.value.push(marker);

            if (markers.value.length === 2) {
                await updateRoute();
                updateForm();
            }
        }
    }

    map.value.on('click', onMapClick);

    if (props.route) {
        // Estamos editando una ruta existente
        const startMarker = L.marker([form.latitude_start, form.longitude_start], { draggable: true }).addTo(map.value);
        const endMarker = L.marker([form.latitude_end, form.longitude_end], { draggable: true }).addTo(map.value);
        markers.value = [startMarker, endMarker];

        markers.value.forEach(marker => {
            marker.on('dragend', async () => {
                await updateRoute();
                updateForm();
            });
        });

        //map.value.fitBounds(L.latLngBounds([startMarker.getLatLng(), endMarker.getLatLng()]));
        map.value.fitBounds(L.latLngBounds([startMarker.getLatLng(), endMarker.getLatLng()]), { padding: [10, 10], maxZoom: 15 });

        updateRoute();
    } else {
        // Obtén la ubicación actual y agrega un marcador
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const { latitude, longitude } = position.coords;
                const currentLocation = [latitude, longitude];
                map.value.setView(currentLocation, 15); // Ajusta el nivel de zoom según sea necesario
                const currentMarker = L.marker(currentLocation, { draggable: true }).addTo(map.value);
                currentMarker.on('dragend', async () => {
                    updateForm();
                    await updateRoute();
                });
                markers.value.push(currentMarker);
                updateForm();
            }, () => {
                console.error('Geolocation failed');
            });
        } else {
            console.error('Geolocation is not supported by this browser.');
        }
    }

    const resizeObserver = new ResizeObserver(() => {
        map.value.invalidateSize();
    });
    resizeObserver.observe(document.getElementById('map'));
});

const submit = () => {
    if (props.route) {
        form.put(route("routes.update", props.route), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("routes.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);
</script>

<template >
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-500 font-bold inline-flex items-center">
            {{ form.id == 0 ? "Registrar Ruta" : "Actualizar Ruta" }}
        </div>
        <button @click="emit('close-modal')">
            <v-icon
                class="text-white rounded-md bg-red-400 hover:bg-red-500"
                name="io-close"
                scale="1.5"
            />
        </button>
    </div>
    <div id="map" style="height: 700px; width: 100%;"></div>
    <form @submit.prevent="submit">
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-6 sm:col-span-4">
                        <InputLabel value="Nombre de la ruta" class="text-slate-500 font-bold"/>
                        <TextInput class="w-full" v-model="form.name"/>
                        <InputError class="w-full" :message="form.errors.name"/>
                    </div>
                    <div class="col-span-6 sm:col-span-2">
                        <InputLabel value="Latitud Inicio" class="text-slate-500 font-bold"/>
                        <TextInput class="w-full" v-model="form.latitude_start"/>
                        <InputError class="w-full" :message="form.errors.latitude_start"/>
                    </div>
                    <div class="col-span-6 sm:col-span-2">
                        <InputLabel value="Longitud Inicio" class="text-slate-500 font-bold"/>
                        <TextInput class="w-full" v-model="form.longitude_start"/>
                        <InputError class="w-full" :message="form.errors.longitude_start"/>
                    </div>
                    <div class="col-span-6 sm:col-span-2">
                        <InputLabel value="Latitud Fin" class="text-slate-500 font-bold"/>
                        <TextInput class="w-full" v-model="form.latitude_end"/>
                        <InputError class="w-full" :message="form.errors.latitude_end"/>
                    </div>
                    <div class="col-span-6 sm:col-span-2">
                        <InputLabel value="Longitud Fin" class="text-slate-500 font-bold"/>
                        <TextInput class="w-full" v-model="form.longitude_end"/>
                        <InputError class="w-full" :message="form.errors.longitude_end"/>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    class="bg-blue-100 hover:bg-blue-200 text-slate-500 font-bold px-4 py-2 rounded-md mr-2 shadow-abajo-1"
                    :disabled="form.processing"
                >
                    {{ form.id == 0 ? "Registrar Ruta" : "Actualizar Ruta" }}
                </button>
            </div>
        </div>
    </form>
</template>


