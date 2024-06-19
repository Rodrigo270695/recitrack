<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted } from "vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import VehiclerouteForm from "./VehiclerouteForm.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    vehicleroutes: Object,
    statuses: Array,
    date_from: String,
    date_to: String,
    texto: String,
});

const form = useForm({
    vehicleroute: Object,
});

let vehiclerouteObject = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);
let dateFrom = ref(props.date_from);
let dateTo = ref(props.date_to);
let query = ref(props.texto);

const toggleOptions = (routeId) => {
    if (openMenuId.value === routeId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = routeId;
    }
};

const editVehicleRoute = (vehicleroute) => {
    vehiclerouteObject.value = vehicleroute;
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

const closeModal = () => {
    showModal.value = false;
    vehicleroute.value = null;
};

const search = () => {
    form.get(route("vehicleroutes.searchTwo", { texto: query.value, date_from: dateFrom.value, date_to: dateTo.value }));
};

</script>

<template>
    <AppLayout title="Programación">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-bold text-xl text-slate-500 ">
                    Programaciones
                </h2>
                <button class="bg-green-100 hover:bg-green-2000 w-12 rounded-md shadow-abajo-1" @click="form.get(route('vehicleroutes.index'))">
                    <v-icon class="text-slate-500" name="io-reload-circle-sharp" scale="1.7" />
                </button>
            </div>
        </template>
        <div class="pt-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">

                    <div class="">
                        <div class="sm:flex py-2 px-3 my-3 gap-3">

                            <input type="search" v-model="query"
                                class="w-64 md:w-72 lg:w-96 hover:border-slate-200 focus:border-blue-50 bg-3D-50 h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none shadow-abajo-2 text-slate-500 border-slate-200 font-bold"
                                placeholder="Buscar Marca"  />
                            <input type="date" v-model="dateFrom" class="bg-3D-50 border border-gray-300 text-slate-500 text-sm rounded-lg focus:ring-slate-500 focus:border-slate-500 block p-2.5 shadow-abajo-2 font-bold">
                            <input type="date" v-model="dateTo" class="bg-3D-50 border border-gray-300 text-slate-500 text-sm rounded-lg focus:ring-slate-500 focus:border-slate-500 block p-2.5 shadow-abajo-2 font-bold">
                            <button class="bg-blue-100 shadow-abajo-1 p-2 text-slate-500 font-bold rounded-lg flex items-center hover:bg-blue-200 cursor-pointer" @click="search">
                                <v-icon name="fa-search" scale="1.1" />
                                <p class="sm:block hidden ml-2">Buscar</p>
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
                                                class="pl-4 py-2 text-left text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Fecha
                                            </th>
                                            <th scope="col"
                                                class="px-1 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Marca
                                            </th>
                                            <th scope="col"
                                                class="px-1 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Vehiculo
                                            </th>
                                            <th scope="col"
                                                class="px-1 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Ruta
                                            </th>
                                            <th scope="col"
                                                class="px-1 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Estado
                                            </th>
                                            <th scope="col" class="border-l"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr v-for="vehicleroute in vehicleroutes.data" :key="vehicleroute.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2">
                                            <td
                                                class="text-xs md:text-base text-slate-500 pl-4 py-3 whitespace-nowrap ">
                                                {{ new Date(vehicleroute.date + 'T00:00:00').toLocaleDateString('es-PE', { timeZone: 'America/Lima' }) }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-1 py-3 whitespace-nowrap text-center">
                                                {{ vehicleroute.vehicle.brandmodel.brand.name}}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-1 py-3 whitespace-nowrap text-center">
                                                {{ vehicleroute.vehicle.brandmodel.name+ '/'+ vehicleroute.vehicle.name}}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-1 py-3 whitespace-nowrap text-center">
                                                {{ vehicleroute.route.name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-1 py-3 whitespace-nowrap text-center">
                                                {{ vehicleroute.statusroute.name }}
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-center gap-x-3">
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-yellow-200 text-slate-500 p-1 rounded-md hover:bg-yellow-300 cursor-pointer shadow-abajo-1"
                                                            @click="
                                                                editVehicleRoute(vehicleroute)
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
                                                                Actualizar
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="vehicleroutes.data.length <= 0">
                                            <td class="text-center font-bold text-slate-500 text-md sm:text-lg bg-3D-500 shadow-abajo-2"
                                                colspan="5">
                                                No hay registros
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- tarjetas -->

                        <Pagination class="mt-2" :pagination="vehicleroutes" />
                    </div>
                    <Modal :show="showModal">
                        <VehiclerouteForm :vehicleroute="vehiclerouteObject" :statuses="props.statuses" @close-modal="closeModal" />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
