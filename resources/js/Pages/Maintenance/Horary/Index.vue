<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted, reactive } from "vue";
import Pagination from "@/Components/Pagination.vue";
import HoraryForm from "./HoraryForm.vue";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    horaries: Object,
    maintenance: Object,
    vehicleoccupants: Array,
    texto: String,
});

const form = useForm({
    horary: Object,
});

let horaryObj = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);

const toggleOptions = (horaryId) => {
    if (openMenuId.value === horaryId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = horaryId;
    }
};

const addHorary = () => {
    horaryObj.value = null;
    showModal.value = true;
};

const editHorary = (horary) => {
    openMenuId.value = null;
    horaryObj.value = horary;
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
    horaryObj.value = null;
};

const deleteHorary = (horary) => {
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
            form.delete(route("maintenances.horaries.destroy", horary), {
                preserveScroll: true,
            });
        }
    });
};

const gotToActivities = (horary) => {
    form.get(route("maintenances.horaries.activities.index", horary.id));
};
</script>

<template>
    <AppLayout title="Horarios">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-bold text-xl text-slate-500">Gestionar Horarios para {{ maintenance.name }}</h2>
                <button
                    class="bg-green-100 hover:bg-green-200 w-12 rounded-md shadow-abajo-1"
                    @click="() => form.get(route('horaries.index'))"
                >
                    <v-icon
                        class="text-slate-500"
                        name="io-reload-circle-sharp"
                        scale="1.7"
                    />
                </button>
            </div>
        </template>
        <div class="pt-5">
            <div class="">
                <div
                    class="bg-3D-50 overflow-hidden shadow-abajo-2 rounded-lg"
                >
                <div class="flex justify-between py-2 px-3 my-3">
                        <div>
                            <button
                                class="bg-red-100 shadow-abajo-1 p-2 text-slate-500 font-bold rounded-lg flex items-center hover:bg-red-200 cursor-pointer"
                                @click="form.get(route('maintenances.index'))">
                                <v-icon name="fa-arrow-left" scale="1.1" />
                                <p class="sm:block hidden ml-2">Retroceder</p>
                            </button>
                        </div>
                        <div>
                            <button
                                class="bg-blue-100 shadow-abajo-1 p-2 text-slate-500 font-bold rounded-lg flex items-center hover:bg-blue-200 cursor-pointer"
                                @click="addHorary">
                                <v-icon name="io-add-circle-sharp" scale="1.1" />
                                <p class="sm:block hidden ml-2">Agregar</p>
                            </button>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="hidden sm:block">
                            <div class="overflow-x-auto rounded-lg">
                                <table
                                    class="min-w-full divide-y divide-gray-200"
                                >
                                    <thead class="bg-blue-200 shadow-abajo-2">
                                        <tr class="">
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-left text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Día
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-left text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Vehículo
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-left text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Conductor
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Tipo
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Hora de Inicio
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Hora de Fin
                                            </th>
                                            <th class="border-l"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr
                                            v-for="horary in horaries.data"
                                            :key="horary.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2"
                                        >
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap"
                                            >
                                                {{ horary.day }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap"
                                            >
                                                {{ horary.vehicleoccupant.vehicle.name }}
                                            </td>

                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ horary.vehicleoccupant.user.name+' '+horary.vehicleoccupant.user.last_name }}
                                            </td>
                                                                                        <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ horary.type }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ horary.start_time }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center"
                                            >
                                                {{ horary.end_time }}
                                            </td>
                                            <td
                                                class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium"
                                            >
                                                <div
                                                    class="flex items-center justify-center gap-x-3"
                                                >
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-yellow-200 text-slate-500 p-1 rounded-md hover:bg-yellow-300 cursor-pointer shadow-abajo-1"
                                                            @click="
                                                                editHorary(horary)
                                                            "
                                                        >
                                                            <v-icon
                                                                name="md-modeedit-round"
                                                            />
                                                            <span
                                                                class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                                style="
                                                                    left: 50%;
                                                                    transform: translateX(
                                                                        -50%
                                                                    );
                                                                    transition: opacity
                                                                        0.3s;
                                                                "
                                                            >
                                                                Editar Horario
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-violet-200 text-slate-500 p-1 rounded-md hover:bg-violet-300 cursor-pointer shadow-abajo-1"
                                                            @click="
                                                                gotToActivities(horary)
                                                            "
                                                        >
                                                            <v-icon
                                                                name="md-localactivity"
                                                            />
                                                            <span
                                                                class="absolute bottom-full mb-2 hidden group-hover:block w-auto p-2 text-xs text-white bg-sky-950 rounded-md"
                                                                style="
                                                                    left: 50%;
                                                                    transform: translateX(
                                                                        -50%
                                                                    );
                                                                    transition: opacity
                                                                        0.3s;
                                                                "
                                                            >
                                                                Registrar Actividad
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-red-300 text-slate-500 p-1 rounded-md hover:bg-red-400 shadow-abajo-1 cursor-pointer"
                                                            @click="
                                                                deleteHorary(
                                                                    horary
                                                                )
                                                            "
                                                        >
                                                            <v-icon
                                                                name="bi-trash"
                                                            />
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
                                                            "
                                                        >
                                                            Eliminar Horario
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="horaries.data.length <= 0">
                                            <td
                                                class="text-center font-bold text-slate-500 text-md sm:text-lg bg-3D-500 shadow-abajo-2"
                                                colspan="7"
                                            >
                                                No hay registros
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <Pagination class="mt-2" :pagination="horaries" />
                    </div>
                    <Modal :show="showModal">
                        <HoraryForm
                            :horary="horaryObj"
                            :maintenance="maintenance"
                            :vehicleoccupants="vehicleoccupants"
                            @close-modal="closeModal"
                        />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
