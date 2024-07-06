<script setup>
import { defineProps, ref, defineEmits, watch } from "vue";

const props = defineProps({
    route: Object,
    formZones: Array
});

const selectedZones = ref([]);

// Inicializar las zonas seleccionadas basadas en formZones
watch(() => props.formZones, (newZones) => {
    selectedZones.value = newZones.filter(zone => zone.selected);
}, { immediate: true });

const toggleZoneSelection = (zone) => {
    const index = selectedZones.value.findIndex(selectedZone => selectedZone.id === zone.id);
    if (index > -1) {
        selectedZones.value.splice(index, 1);
    } else {
        selectedZones.value.push(zone);
    }
};

const emit = defineEmits(["close-modal", "register-zones"]);
const registerZones = () => {
    console.log('Zonas seleccionadas:', selectedZones.value);
    emit('register-zones', selectedZones.value);
    emit('close-modal');
};

</script>

<template>
    <div>
        <div class="flex justify-between bg-slate-300 h-12 px-4">
            <div class="text-lg sm:text-xl text-slate-500 font-bold inline-flex items-center">
                Asignar Zonas
            </div>
            <button @click="emit('close-modal')">
                <v-icon class="text-white rounded-md bg-red-400 hover:bg-red-500" name="io-close" scale="1.5" />
            </button>
        </div>
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-200 shadow-abajo-2">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                            Nombre
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                            Área
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs sm:text-base font-bold text-slate-500 uppercase tracking-wider border-l">
                            Seleccionar
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-3D-50 divide-gray-200">
                    <tr v-for="zone in formZones" :key="zone.id"
                        class="bg-3D-50 hover:bg-blue-50 border-2 shadow-abajo-2">
                        <td class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center">{{
                            zone.name }}</td>
                        <td class="text-xs md:text-base text-slate-500 px-6 py-3 whitespace-nowrap text-center">{{
                            zone.area }}</td>
                        <td class="px-6 py-3 whitespace-nowrap text-center">
                            <input type="checkbox" :checked="zone.selected" @change="toggleZoneSelection(zone)" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-end">
                <button @click="registerZones"
                    class="bg-blue-100 hover:bg-blue-200 text-slate-500 font-bold px-4 py-2 rounded-md mr-2 shadow-abajo-1">
                    Registrar
                </button>
            </div>
        </div>
    </div>
</template>
