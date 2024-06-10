<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    user: Object,
    typeUsers: Array,
});

const form = useForm({
    id: props.user ? props.user.id : "",
    name: props.user ? props.user.name : "",
    last_name: props.user ? props.user.last_name : "",
    dni: props.user ? props.user.dni : "",
    birthdate: props.user ? props.user.birthdate : "",
    license: props.user ? props.user.license : "",
    address: props.user ? props.user.address : "",
    email: props.user ? props.user.email : "",
    phone: props.user ? props.user.phone : "",
    typeuser_id: props.user ? props.user.typeuser_id : "",
});

const submit = () => {
    if (props.user) {
        form.put(route("users.update", props.user.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("users.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

const emit = defineEmits(["close-modal"]);

</script>

<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-500 font-bold inline-flex items-center">
            {{ form.id == 0 ? "Registrar Usuario" : "Actualizar Usuario" }}
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
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Nombre" class="text-slate-500 font-bold"/>
                        <TextInput
                            class="w-full"
                            v-model="form.name"
                            @input="form.name = toTitleCase(form.name)"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.name"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Apellido" class="text-slate-500 font-bold"/>
                        <TextInput
                            class="w-full"
                            v-model="form.last_name"
                            @input="form.last_name = toTitleCase(form.last_name)"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.last_name"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="DNI" class="text-slate-500 font-bold"/>
                        <TextInput
                            class="w-full"
                            v-model="form.dni"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.dni"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Fecha de Nacimiento" class="text-slate-500 font-bold"/>
                        <TextInput
                            type="date"
                            class="w-full"
                            v-model="form.birthdate"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.birthdate"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Licencia" class="text-slate-500 font-bold"/>
                        <TextInput
                            class="w-full"
                            v-model="form.license"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.license"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Dirección" class="text-slate-500 font-bold"/>
                        <TextInput
                            class="w-full"
                            v-model="form.address"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.address"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Email" class="text-slate-500 font-bold"/>
                        <TextInput
                            type="email"
                            class="w-full"
                            v-model="form.email"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.email"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Teléfono" class="text-slate-500 font-bold"/>
                        <TextInput
                            class="w-full"
                            v-model="form.phone"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.phone"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Tipo de Usuario" />
                        <select
                            id="select"
                            v-model="form.typeuser_id"
                            class="bg-3D-50 border border-blue-300 font-bold text-sm rounded-lg shadow-abajo-2 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500 focus:ring-slate-500 text-slate-500"
                        >
                            <option disabled selected value="">
                                Elija una opción
                            </option>
                            <option
                                v-for="typeUser in typeUsers"
                                :key="typeUser.id"
                                :value="typeUser.id"
                            >
                                {{ typeUser.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.typeuser_id"
            />
        </div>
        </div>
    </div>
    <div class="flex justify-end">
        <button
            class="bg-blue-100 hover:bg-blue-200 text-slate-500 font-bold px-4 py-2 rounded-md mr-2 shadow-abajo-1"
            :disabled="form.processing"
        >
            {{ form.id == 0 ? "Registrar" : "Actualizar" }}
        </button>
    </div>
</div>
</form>
</template>
