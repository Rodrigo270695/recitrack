<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps, onMounted, onUnmounted, reactive } from "vue";
import Pagination from "@/Components/Pagination.vue";
import ModelForm from "./ModelForm.vue";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    brandModels: Object,
    brands: Object,
    texto: String,
});

const form = useForm({
    brandModel: Object,
});

let brandModelObj = ref(null);
let showModal = ref(false);
let openMenuId = ref(null);
let query = ref(props.texto);

const toggleOptions = (modelId) => {
    if (openMenuId.value === modelId) {
        openMenuId.value = null;
    } else {
        openMenuId.value = modelId;
    }
};

const addBrandModel = () => {
    brandModelObj.value = null;
    showModal.value = true;
};

const editBrandModel = (model) => {
    openMenuId.value = null;
    brandModelObj.value = model;
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
    brandModelObj.value = null;
};

const deleteBrandModel = (model) => {
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
            form.delete(route("brandmodels.destroy", model), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("brandmodels.search", { texto: query.value }));
};
</script>

<template>
    <AppLayout title="Brand Models">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-bold text-xl text-slate-500">Gestionar Modelos de Marca</h2>
                <button
                    class="bg-green-100 hover:bg-green-200 w-12 rounded-md shadow-abajo-1"
                    @click="() => form.get(route('brandmodels.index'))"
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
                        <div class="relative">
                            <input
                                type="text"
                                v-model="query"
                                class="w-64 md:w-72 lg:w-96 hover:border-slate-200 focus:border-blue-50 bg-3D-50 h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none shadow-abajo-2 text-slate-500 border-slate-200 font-bold"
                                placeholder="Buscar Modelo"
                                @keyup.enter="search"
                            />
                            <button
                                @click.prevent="search"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-500 bg-blue-100 rounded-e-md hover:bg-blue-200 shadow-abajo-1"
                            >
                                <v-icon name="fa-search" scale="1.5" />
                            </button>
                        </div>
                        <div>
                            <button
                                class="bg-blue-100 shadow-abajo-1 p-2 text-slate-500 font-bold rounded-lg flex items-center hover:bg-blue-200 cursor-pointer"
                                @click="addBrandModel"
                            >
                                <v-icon
                                    name="io-add-circle-sharp"
                                    scale="1.1"
                                />
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
                                                marca
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-left text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                nombre
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-2 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l"
                                            >
                                                Código
                                            </th>
                                            <th class="border-l"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-3D-50 divide-gray-200">
                                        <tr
                                            v-for="brandModel in brandModels.data"
                                            :key="brandModel.id"
                                            class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2"
                                        >
                                            <td
                                                class="text-xs md:text-base font-semibold text-slate-500 px-6 py-3 whitespace-nowrap"
                                            >
                                                {{ brandModel.brand.name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base font-semibold text-slate-500 px-6 py-3 whitespace-nowrap"
                                            >
                                                {{ brandModel.name }}
                                            </td>
                                            <td
                                                class="text-xs md:text-base font-semibold text-slate-500 px-6 py-3 whitespace-nowrap flex items-center justify-center"
                                            >
                                                {{ brandModel.code }}
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
                                                                editBrandModel(brandModel)
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
                                                                Editar Modelo
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="relative group">
                                                        <button
                                                            class="bg-red-300 text-slate-500 p-1 rounded-md hover:bg-red-400 shadow-abajo-1 cursor-pointer"
                                                            @click="
                                                                deleteBrandModel(
                                                                    brandModel
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
                                                            Eliminar Modelo
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="brandModels.data.length <= 0">
                                            <td
                                                class="text-center font-bold text-slate-500 text-md sm:text-lg bg-3D-500 shadow-abajo-2"
                                                colspan="5"
                                            >
                                                No hay registros
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <Pagination class="mt-2" :pagination="brandModels" />
                    </div>
                    <Modal :show="showModal">
                        <ModelForm
                            :brandmodel="brandModelObj"
                            :brands="brands"
                            @close-modal="closeModal"
                        />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
