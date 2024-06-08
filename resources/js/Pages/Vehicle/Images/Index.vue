<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted, reactive } from "vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import ImageForm from "./ImageForm.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    vehicleimages: Object,
});

const form = useForm({
    vehicleimage: Object,
});

let vehicleimage = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);

const toggleOptions = (brandId) => {
    if (openMenuId.value === brandId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = brandId;
    }
};

const addVehicleImage = () => {
    vehicleimage.value = props.vehicleimages;
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
    vehicleimage.value = null;
};

const deleteVehicleImage = (vehicleimage) => {
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
            form.delete(route("vehicles.images.destroy", vehicleimage), {
                preserveScroll: true,
            });
        }
    });
};

</script>

<template>
    <AppLayout title="Brands">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-bold text-xl text-slate-500 ">
                    Gestionar Imagenes de <p class="inline-flex text-blue-400">{{ props.vehicleimages.name }}</p>
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
                                @click="addVehicleImage">
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
                                                class="px-6 py-2 text-left text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Logo
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                                                Perfil
                                            </th>
                                            <th scope="col" class="border-l"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr v-for="vehicleimage in vehicleimages.vehicleimages" :key="vehicleimage.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2">
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap flex items-center justify-center">
                                                <img :src="vehicleimage.image
                                                        ? vehicleimage.image
                                                        : asset(
                                                            'default.png'
                                                        )
                                                    " alt="Logo de la marca"
                                                    class="w-12 h-12 object-cover rounded-md" />
                                            </td>
                                            <td
                                                class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center">
                                                {{ vehicleimage.profile }}
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-center gap-x-3">
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-red-300 text-slate-500 p-1 rounded-md hover:bg-red-400 shadow-abajo-1 cursor-pointer"
                                                            @click="
                                                                deleteVehicleImage(
                                                                    vehicleimage
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
                                                            Eliminar imagen
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="vehicleimages.vehicleimages.length <= 0">
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
                    </div>
                    <Modal :show="showModal">
                        <ImageForm :vehicleimage="vehicleimage" @close-modal="closeModal" />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
