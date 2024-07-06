<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted, reactive } from "vue";
import Pagination from "@/Components/Pagination.vue";
import RouteForm from "./RouteForm.vue";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import axios from 'axios';
import AddZonesForm from "./AddZonesForm.vue";


const API_KEY = '5b3ce3597851110001cf6248ed2bfaf93cbe4a9e83f21ca902eeb546';

const getRandomColor = (index) => {
    const hue = (index * 137.508) % 360;
    return `hsl(${hue}, 70%, 50%)`;
};


const map = ref(null);
const formZones = ref([]);

const getRoute = async (start, end) => {
    const url = `https://api.openrouteservice.org/v2/directions/driving-car?api_key=${API_KEY}&start=${start.lng},${start.lat}&end=${end.lng},${end.lat}`;
    try {
        const response = await axios.get(url);
        const coords = response.data.features[0].geometry.coordinates.map(coord => [coord[1], coord[0]]);
        return coords;
    } catch (error) {
        console.error('Error fetching route:', error);
        return [];
    }
};

const props = defineProps({
    routes: Object,
    texto: String,
    zones: Object,
});


const form = useForm({
    route: Object,
    zone: Object,
});



let routeObj = ref(null);
let zoneObj = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);
let showZonesModal = ref(false);



const toggleOptions = (routeId) => {
    if (openMenuId.value === routeId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = routeId;
    }
};

const addroute = () => {
    routeObj.value = null;
    showModal.value = true;

};



const assignZonesToRoute = async (route) => {
    openMenuId.value = null;
    zoneObj.value = route;
    showZonesModal.value = true;

    try {
        // Cargar todas las zonas
        const allZonesResponse = await axios.get('/zone/getAllZones');
        const allZones = allZonesResponse.data.zones;

        // Cargar las zonas asignadas a la ruta
        const assignedZonesResponse = await axios.get(`/route-zones/${route.id}`);
        const assignedZones = assignedZonesResponse.data.zones;

        // Marcar las zonas asignadas
        formZones.value = allZones.map(zone => ({
            ...zone,
            selected: assignedZones.some(assignedZone => assignedZone.id === zone.id)
        }));
    } catch (error) {
        console.error('Error al cargar las zonas:', error);
    }
};


const registerZonesToRoute = (zones) => {
    console.log('Zonas a registrar:', zones);
    const zoneIds = zones.map(zone => zone.id); // Extraer solo los IDs de las zonas
    axios.post(route("routeZones.store", { routeId: zoneObj.value.id }), { zones: zoneIds })
        .then(response => {
            console.log('Zonas asignadas exitosamente:', response.data);
            showZonesModal.value = false;
        })
        .catch(error => {
            console.error('Error al asignar zonas:', error);
            console.error('Detalles del error:', error.response.data);
        });
};


const loadZones = () => {
    axios.get(route("zone.getAllZones"))
        .then(response => {
            formZones.value = response.data.zones;
        })
        .catch(error => {
            console.error('Error al cargar las zonas:', error);
        });
};


const editroute = (route) => {
    openMenuId.value = null;
    routeObj.value = route;
    showModal.value = true;

};

const onKeydown = (e) => {
    if (e.key === "Escape") {
        closeModal();
    }
};


onMounted(() => {
    window.addEventListener("keydown", onKeydown);
});

onUnmounted(() => {
    window.removeEventListener("keydown", onKeydown);
});

const calculateCenter = (routes) => {
    let totalLat = 0;
    let totalLng = 0;

    routes.forEach((route) => {
        const latStart = parseFloat(route.latitude_start);
        const latEnd = parseFloat(route.latitude_end);
        const lngStart = parseFloat(route.longitude_start);
        const lngEnd = parseFloat(route.longitude_end);

        totalLat += (latStart + latEnd) / 2;
        totalLng += (lngStart + lngEnd) / 2;
    });

    const centerLat = totalLat / routes.length;
    const centerLng = totalLng / routes.length;

    const center = [centerLat, centerLng];

    return center;
};

const initializeMap = async () => {
    const mapElement = document.getElementById('map');
    if (mapElement) {
        map.value = L.map('map').setView([0, 0], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map.value);

        if (props.routes && props.routes.data) {


            props.routes.data.forEach((route, index) => {
                const routeColor = getRandomColor(index);

                const startMarker = L.marker([route.latitude_start, route.longitude_start]).addTo(map.value);
                const endMarker = L.marker([route.latitude_end, route.longitude_end]).addTo(map.value);

                const center = calculateCenter(props.routes.data);
                map.value.setView(center, 10);

                const getRouteCoords = async () => {
                    const latlngs = await getRoute(startMarker.getLatLng(), endMarker.getLatLng());

                    const polyline = L.polyline(latlngs, {
                        color: routeColor,
                        weight: 5
                    }).addTo(map.value);

                    polyline.bindPopup(`<b>${route.name}</b>`);

                    map.value.fitBounds(L.latLngBounds([startMarker.getLatLng(), endMarker.getLatLng()]), { padding: [10, 10], maxZoom: 17 });

                    // Agregar evento de clic a cada marcador y resaltar la ruta correspondiente
                    startMarker.on('click', () => {
                        highlightRoute(startMarker, polyline);
                    });

                    endMarker.on('click', () => {
                        highlightRoute(endMarker, polyline);
                    });

                    const startIcon = L.divIcon({
                        className: 'custom-icon',
                        html: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="${routeColor}"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/></svg>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32]
                    });

                    const endIcon = L.divIcon({
                        className: 'custom-icon',
                        html: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="${routeColor}"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/></svg>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32]
                    });

                    startMarker.setIcon(startIcon);
                    endMarker.setIcon(endIcon);
                };

                getRouteCoords();
            });
        }
    }
};


onMounted(() => {
    try {
        initializeMap();
    } catch (error) {
        console.error('Error en el gancho mounted:', error);
    }
});

const closeModal = () => {
    showModal.value = false;
    showZonesModal.value = false;
    routeObj.value = null;
};

const deleteroute = (routeData) => {
    openMenuId.value = null;
    Swal.fire({
        title: "¿Estás seguro?",
        text: "No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("routes.destroy", routeData), {
                preserveScroll: true,
            });
        }
    });

};

const search = () => {
    form.get(route("routes.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("routes.index"));
};

const cambiarEstado = (route) => {
    if (route.status === 1) {
        route.status = 0;
    } else {
        route.status = 1;
    }
    changeStatus(route);
};

const changeStatus = (route) => {
    form.post(route("routes.changeStatus", route.id))
        .then(() => {
            console.log("Estado de la ruta cambiado exitosamente!");
        })
        .catch((error) => {
            console.error("Error al cambiar el estado de la ruta:", error);
        });
};


let selectedMarker = null;
let selectedPolyline = null;

// Función para resaltar el marcador y la ruta seleccionados
const highlightRoute = (marker, polyline) => {
    if (selectedMarker) {
        selectedMarker.getElement().classList.remove('selected-marker');
    }
    if (selectedPolyline) {
        selectedPolyline.setStyle({ weight: 5 }); // Restaura el grosor de la ruta
    }

    selectedMarker = marker;
    selectedPolyline = polyline;

    selectedMarker.getElement().classList.add('selected-marker');
    selectedPolyline.setStyle({ weight: 8 }); // Aplica un grosor mayor a la ruta
};

</script>


<style>
.selected-marker {
    transform: scale(3.5);
    /* Aumenta el tamaño del marcador */
}

.selected-polyline {
    stroke-width: 10px;
    /* Aumenta el grosor de la ruta */
}
</style>

<template>
    <AppLayout title="route">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-bold text-xl text-slate-500 ">
                    Gestionar Ruta
                </h2>
                <button class="bg-green-100 hover:bg-green-200 w-12 rounded-md shadow-abajo-1" @click="goToIndex">
                    <v-icon class="text-slate-500" name="io-reload-circle-sharp" scale="1.7" />
                </button>
            </div>
        </template>
        <div class="pt-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">
                    <div class="flex justify-between py-2 px-3 my-3">
                        <div class="relative">
                            <input type="search" v-model="query"
                                class="w-64 md:w-72 lg:w-96 hover:border-slate-200 focus:border-blue-50 bg-3D-50 h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none shadow-abajo-2 text-slate-500 border-slate-200 font-bold focus:ring-slate-500"
                                placeholder="Buscar ruta" @keyup.enter="search" />
                            <button @click.prevent="search"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-500 bg-blue-100 rounded-e-md hover:bg-blue-200 shadow-abajo-1">
                                <v-icon name="fa-search" scale="1.5" />
                            </button>
                        </div>
                        <div>
                            <button
                                class="bg-blue-100 shadow-abajo-1 p-2 text-slate-500 font-bold rounded-lg flex items-center hover:bg-blue-200 cursor-pointer"
                                @click="addroute">
                                <v-icon name="io-add-circle-sharp" scale="1.1" />
                                <p class="sm:block hidden ml-2">Agregar</p>
                            </button>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="hidden sm:block">
                            <div class="overflow-x-auto rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-blue-200 shadow-abajo-2">
                                        <tr class="">
                                            <th scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Nombre
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Lat. de inicio
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Lat. de fin
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Long. de inicio
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Long. de fin
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Estado
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr v-for="route in routes.data" :key="route.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2">
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center">
                                                {{ route.name }}
                                            </td>

                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center">
                                                {{ route.latitude_start }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center">
                                                {{ route.latitude_end }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center">
                                                {{ route.longitude_start }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center">
                                                {{ route.longitude_end }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center">
                                                {{ route.status === 1 ? 'Vigente' : 'No vigente' }}
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-center gap-x-3">
                                                    <div class="relative group">
                                                        <button :disabled="route.status === 0"
                                                            class="bg-yellow-200 text-slate-500 p-1 rounded-md hover:bg-yellow-300 cursor-pointer shadow-abajo-1"
                                                            @click="
                                                                editroute(route)
                                                                ">
                                                            <v-icon name="md-modeedit-round" />
                                                            <span
                                                                class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                                style="
                                                                    left: 50%;
                                                                    transform: translateX(
                                                                        -50%
                                                                    );
                                                                    transition: opacity
                                                                        0.3s;
                                                                ">
                                                                Editar Ruta
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="relative group">
                                                        <button :disabled="route.status === 0"
                                                            class="bg-blue-300 text-slate-500 p-1 rounded-md hover:bg-blue-400 shadow-abajo-1 cursor-pointer"
                                                            @click="
                                                                assignZonesToRoute(route)
                                                                ">
                                                            <v-icon name="md-adsclick" />
                                                        </button>
                                                        <span
                                                            class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                            style="
                                                                left: 50%;
                                                                transform: translateX(
                                                                    -50%
                                                                );
                                                                transition: opacity
                                                                    0.3s;
                                                            ">
                                                            Asignar Zonas
                                                        </span>
                                                    </div>
                                                    <div class="relative group">
                                                        <button :disabled="route.status === 0"
                                                            class="bg-red-300 text-slate-500 p-1 rounded-md hover:bg-red-400 shadow-abajo-1 cursor-pointer"
                                                            @click="
                                                                deleteroute(route)
                                                                ">
                                                            <v-icon name="bi-trash" />
                                                        </button>
                                                        <span
                                                            class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                            style="
                                                                left: 50%;
                                                                transform: translateX(
                                                                    -50%
                                                                );
                                                                transition: opacity
                                                                    0.3s;
                                                            ">
                                                            Eliminar Ruta
                                                        </span>
                                                    </div>
                                                    <div class="relative group">
                                                        <button type="button"
                                                            class="bg-orange-300 text-slate-500 p-1 rounded-md hover:bg-orange-400 shadow-abajo-1 cursor-pointer"
                                                            @click="cambiarEstado(route)">
                                                            <v-icon
                                                                :name="route.status === 1 ? 'co-toggle-on' : 'fa-toggle-off'" />
                                                        </button>
                                                        <span
                                                            class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                            style="left: 50%; transform: translateX(-50%); transition: opacity 0.3s;">
                                                            {{ route.status === 1 ? 'Desactivar Ruta' : 'Activar Ruta'
                                                            }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="routes.data.length <= 0">
                                            <td class="text-center font-bold text-slate-500 text-md sm:text-lg bg-3D-50 shadow-abajo-2"
                                                colspan="7">
                                                No hay registros
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="block sm:hidden rounded-lg">
                            <div v-for="route in routes.data" :key="route.id"
                                class="p-4 mx-1 mt-4 bg-blue-50 hover:bg-blue-100 rounded-lg relative shadow-abajo-1">
                                <!-- Contenido de la tarjeta -->
                                <div class="flex items-center space-x-2 mb-4">
                                    <svg class="h-6 w-6 text-sky-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7h18M3 12h18m-9 5h9" />
                                    </svg>
                                    <h3 class="text-lg font-bold text-slate-700">
                                        Nombre:
                                        <span class="font-normal text-gray-600">{{
                                            route.name
                                        }}</span>
                                    </h3>
                                </div>
                                <!-- Detalles de la tarjeta -->
                                <div class="text-md text-slate-700">
                                    <p>
                                        <strong>Descripcion:</strong>
                                        <span class="text-gray-600 ml-1">{{
                                            route.description
                                        }}</span>
                                    </p>
                                </div>
                                <!-- Menú de tres puntos -->
                                <div class="absolute top-0 right-0 p-2 z-10">
                                    <button @click="toggleOptions(route.id)"
                                        class="text-gray-600 hover:text-gray-900 shadow-md shadow-sky-100">
                                        <v-icon name="oi-apps" />
                                    </button>
                                    <div v-if="openMenuId === route.id"
                                        class="bg-white flex justify-between shadow-abajo-1 rounded-lg absolute right-0 mt-1 w-[86px] z-20 text-center">
                                        <a href="#" @click="editroute(route)"
                                            class="block px-3 py-1 text-sm text-slate-500 bg-yellow-200 hover:bg-yellow-300 rounded-l-lg">
                                            <v-icon name="md-modeedit-round" class="text-slate-500" />
                                        </a>
                                        <a href="#" @click="deleteroute(route)"
                                            class="block px-3 py-1 text-sm text-slate-500 bg-red-300 hover:bg-red-400 rounded-r-lg">
                                            <v-icon name="bi-trash" class="text-slate-500" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <Pagination class="mt-2" :pagination="routes" />
                    </div>
                    <Modal :show="showModal">
                        <routeForm :route="routeObj" @close-modal="closeModal" />
                    </Modal>
                    <Modal :show="showZonesModal">
                        <AddZonesForm :route="zoneObj" :formZones="formZones" @close-modal="closeModal"
                            @register-zones="registerZonesToRoute" />
                    </Modal>
                </div>
            </div>
        </div>
        <div id="map" style="height: 700px;"></div>
    </AppLayout>
</template>
