


<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted, reactive } from "vue";
import Pagination from "@/Components/Pagination.vue";
import RouteForm from "./RouteForm.vue";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";



const props = defineProps({
    routes: Object,
    texto: String,
});

const form = useForm({
    route: Object,
});

let routeObj = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);

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

const closeModal = () => {
    showModal.value = false;
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



</script>

<template>
    <AppLayout title="route">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-bold text-xl text-slate-500 ">
                    Agregar rutas a zonas
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
                                                Ruta
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Descripción
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr v-for="routeZone in routes.data" :key="routeZone.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2">
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center">
                                                {{ routeZone.route.name }}
                                            </td>

                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center">
                                                {{ routeZone.route.status === 1 ? 'Vigente' : 'No vigente' }}

                                            </td>


                                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-center gap-x-3">

                                                    <div class="relative group">
                                                        <button
                                                            :disabled="route.status === 0"
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
                </div>
            </div>
        </div>
    </AppLayout>
</template>
