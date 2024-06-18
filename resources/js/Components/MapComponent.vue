<script setup>
import { onMounted, ref, nextTick, watch } from "vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

const props = defineProps({
    initialLat: {
        type: Number,
        default: null,
    },
    initialLng: {
        type: Number,
        default: null,
    },
    height: {
        type: String,
        default: "350px",
    },
    zoneCoords: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["update:lat", "update:lng"]);

const mapContainer = ref(null);
const mapHeight = ref(props.height);
const map = ref(null);
const marker = ref(null);

const initializeMap = () => {
    if (mapContainer.value) {
        const lat = props.initialLat !== null ? props.initialLat : -6.7011;
        const lng = props.initialLng !== null ? props.initialLng : -79.9061;

        map.value = L.map(mapContainer.value).setView([lat, lng], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution:
                'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map.value);

        marker.value = L.marker([lat, lng], {
            draggable: true,
        }).addTo(map.value);

        marker.value.on("dragend", () => {
            const { lat, lng } = marker.value.getLatLng();
            emit("update:lat", lat);
            emit("update:lng", lng);
        });

        // Forzar la actualización del tamaño del mapa
        map.value.invalidateSize();

        // Dibujar el polígono basado en las coordenadas de la zona
        if (props.zoneCoords.length > 0) {
            const polygonCoords = props.zoneCoords.map(coord => [coord.latitude, coord.longitude]);
            L.polygon(polygonCoords, { color: 'red' }).addTo(map.value);
        }

        // Intentar obtener la ubicación actual del usuario si las coordenadas iniciales son nulas
        if (props.initialLat === null || props.initialLng === null) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const { latitude, longitude } = position.coords;
                        map.value.setView([latitude, longitude], 13);
                        marker.value.setLatLng([latitude, longitude]);
                        emit("update:lat", latitude);
                        emit("update:lng", longitude);
                    },
                    (error) => {
                        console.error("Error obteniendo la ubicación: ", error);
                    }
                );
            } else {
                console.error("Geolocalización no es soportada por este navegador.");
            }
        }
    }
};

onMounted(async () => {
    await nextTick();
    initializeMap();
});

watch([() => props.initialLat, () => props.initialLng], () => {
    if (map.value && marker.value) {
        const lat = props.initialLat !== null ? props.initialLat : -6.7011;
        const lng = props.initialLng !== null ? props.initialLng : -79.9061;
        marker.value.setLatLng([lat, lng]);
    }
});
</script>

<template>
    <div ref="mapContainer" :style="{ height: mapHeight, width: '100%' }"></div>
</template>
