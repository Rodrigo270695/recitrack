<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted } from "vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import OccupantForm from "./OccupantForm.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    vehicleoccupants: Object,
    users: Array,
    vehicle: Object,
    types: Array,
});

const form = useForm({
    vehicleoccupant: Object,
});

let vehicleoccupant = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);

const toggleOptions = (occupantId) => {
    if (openMenuId.value === occupantId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = occupantId;
    }
};

const addVehicleOccupant = () => {
    vehicleoccupant.value = props.vehicleoccupants;
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
    vehicleoccupant.value = null;
};

const deleteVehicleOccupant = (vehicleoccupant) => {
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
            form.delete(route("vehicles.occupants.destroy", vehicleoccupant), {
                preserveScroll: true,
            });
        }
    });
};

</script>

<template>
    <AppLayout title="Occupants">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-bold text-xl text-slate-500 ">
                    Gestionar Ocupantes del Vehiculo <p class="inline-flex text-blue-400">{{ props.vehicle.name }}</p> con <p class="inline-flex text-blue-400">{{ props.vehicle.capacity }}</p> ocupantes
                </h2>
                <button class="bg-green-100 hover:bg-green-200 w-12 rounded-md shadow-abajo-1" @click="form.get(route('vehicles.occupants.index', props.vehicle.id))">
                    <v-icon class="text-slate-500" name="io-reload-circle-sharp" scale="1.7" />
                </button>
            </div>
        </template>
        <div class="pt-5">
            <div class="">
                <div class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg">
                    <div class="flex justify-between py-2 px-3 my-3">
                        <div>
                            <button
                                class="bg-red-100 shadow-abajo-1 p-2 text-slate-500 font-bold rounded-lg flex items-center hover:bg-red-200 cursor-pointer"
                                @click="form.get(route('vehicles.index'))">
                                <v-icon name="fa-arrow-left" scale="1.1" />
                                <p class="sm:block hidden ml-2">Retroceder</p>
                            </button>
                        </div>
                        <div>
                            <button
                                class="bg-blue-100 shadow-abajo-1 p-2 text-slate-500 font-bold rounded-lg flex items-center hover:bg-blue-200 cursor-pointer"
                                @click="addVehicleOccupant">
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
                                                class="pl-4 py-2 text-left text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Tipo de usuario
                                            </th>
                                            <th scope="col"
                                                class="px-1 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Nombre Completo
                                            </th>
                                            <th scope="col"
                                                class="px-1 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                DNI
                                            </th>
                                            <th scope="col"
                                                class="px-1 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Licencia
                                            </th>
                                            <th scope="col"
                                                class="px-1 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Email
                                            </th>
                                            <th scope="col" class="border-l"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr v-for="vehicleoccupant in vehicleoccupants" :key="vehicleoccupant.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2">
                                            <td
                                                class="text-xs md:text-base text-slate-500 pl-4 py-3 whitespace-nowrap ">
                                                {{ vehicleoccupant.user.typeuser.name}}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-1 py-3 whitespace-nowrap flex items-center justify-center">
                                                {{ vehicleoccupant.user.name + ' ' + vehicleoccupant.user.last_name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-1 py-3 whitespace-nowrap text-center">
                                                {{ vehicleoccupant.user.dni }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-1 py-3 whitespace-nowrap text-center">
                                                {{ vehicleoccupant.user.license }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-1 py-3 whitespace-nowrap text-center">
                                                {{ vehicleoccupant.user.email }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-center gap-x-3">
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-red-300 text-slate-500 p-1 rounded-md hover:bg-red-400 shadow-abajo-1 cursor-pointer"
                                                            @click="
                                                                deleteVehicleOccupant(
                                                                    vehicleoccupant
                                                                )
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
                                                            Eliminar ocupante
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="vehicleoccupants.length <= 0">
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
                        <div class="block sm:hidden rounded-lg">
                            <div v-for="vehicleoccupant in vehicleoccupants" :key="vehicleoccupant.id"
                                class="p-4 mx-1 mt-4 bg-blue-50 hover:bg-blue-100 rounded-lg relative shadow-abajo-1">
                                <!-- Contenido de la tarjeta -->
                                <div class="flex items-center space-x-2 mb-4">
                                    <svg class="h-6 w-6 text-sky-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7h18M3 12h18m-9 5h9" />
                                    </svg>
                                    <h3 class="text-lg font-bold text-slate-700">
                                        Tipo:
                                        <span class="font-normal text-gray-600">{{ vehicleoccupant.user.typeuser.name }}</span>
                                    </h3>
                                </div>
                                <!-- Detalles de la tarjeta -->
                                <div class="text-md text-slate-700">
                                    <p>
                                        <strong>Nombre:</strong>
                                        <span class="text-gray-600 ml-1">{{
                                            vehicleoccupant.user.name + ' ' + vehicleoccupant.user.last_name
                                        }}</span>
                                    </p>
                                    <p>
                                        <strong>DNI:</strong>
                                        <span class="text-gray-600 ml-1">{{
                                            vehicleoccupant.user.dni
                                        }}</span>
                                    </p>
                                    <p>
                                        <strong>Licencia:</strong>
                                        <span class="text-gray-600 ml-1">{{
                                            vehicleoccupant.user.license
                                        }}</span>
                                    </p>
                                    <p>
                                        <strong>Email:</strong>
                                        <span class="text-gray-600 ml-1">{{
                                            vehicleoccupant.user.email
                                        }}</span>
                                    </p>
                                </div>
                                <!-- Menú de tres puntos -->
                                <div class="absolute top-0 right-0 p-2 z-10">
                                    <button @click="toggleOptions(vehicleoccupant.id)"
                                        class="text-gray-600 hover:text-gray-900 shadow-md shadow-sky-100">
                                        <v-icon name="oi-apps" />
                                    </button>
                                    <div v-if="openMenuId === vehicleoccupant.id"
                                        class="bg-white flex justify-between shadow-abajo-1 rounded-lg absolute right-0 mt-1 w-[43px] z-20 text-center">
                                        <a href="#" @click="deleteVehicleOccupant(vehicleoccupant)"
                                            class="block px-3 py-1 text-sm text-slate-500 bg-red-300 hover:bg-red-400 rounded-lg">
                                            <v-icon name="bi-trash" class="text-slate-500" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Modal :show="showModal">
                        <OccupantForm :vehicle="props.vehicle" :users="props.users" :types="props.types" @close-modal="closeModal" />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
