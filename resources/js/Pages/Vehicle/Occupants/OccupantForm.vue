<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits, computed, ref, watch } from "vue";

const props = defineProps({
    vehicle: Object,
    users: Array,
    types: Array,
});

const form = useForm({
    vehicle_id: props.vehicle.id,
    user_id: '',
});

const selectedTypeId = ref(props.types[0].id );


const filteredUsers = computed(() => {
    return props.users.filter(
        (user) => user.typeuser_id === selectedTypeId.value
    );
});

watch(selectedTypeId, (newTypeId) => {
    if (filteredUsers.value.length > 0) {
        form.user_id = filteredUsers.value[0].id;
    } else {
        form.user_id = "";
    }
});

const submit = () => {
    form.post(route("vehicle.occupants.store"), {
        preserveScroll: true,
        onSuccess: () => emit("close-modal"),
    });
};

const emit = defineEmits(["close-modal"]);

</script>

<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-500 font-bold inline-flex items-center">
            Registrar Ocupante
        </div>
        <button @click="emit('close-modal')" >
            <v-icon
                class="text-white rounded-md bg-red-400 hover:bg-red-500"
                name="io-close"
                scale="1.5"
            />
        </button>
    </div>
    <form @submit.prevent="submit" >
        <div class="bg-3D-50 shadow-md rounded-md p-4">
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Tipo de Ocupante" />
                        <select
                            id="select"
                            v-model="selectedTypeId"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="type in types"
                                :key="type.id"
                                :value="type.id"
                            >
                                {{  type.name  }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.vehicle_id"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Ocupante" />
                        <select
                            id="select"
                            v-model="form.user_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="user in filteredUsers"
                                :key="user.id"
                                :value="user.id"
                            >
                                {{  user.dni + ' - ' + user.name + ' ' + user.last_name  }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.user_id"
                        />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    class="bg-blue-100 hover:bg-blue-200 text-slate-500 font-bold px-4 py-2 rounded-md mr-2 shadow-abajo-1"
                    :disabled="form.processing"
                >
                    Registrar
                </button>
            </div>
        </div>
    </form>
</template>
