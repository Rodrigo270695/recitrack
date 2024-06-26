<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    brand: Object,
});

const form = useForm({
    _method: props.brand ? 'PUT' : 'POST',
    id: props.brand ? props.brand.id : "",
    name: props.brand ? props.brand.name : "",
    description: props.brand ? props.brand.description : "",
    logo: null,
});

const submit = () => {
    if (props.brand) {
        form.post(route("brands.update", props.brand), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("brands.store"), {
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

const handleFileChange = (event) => {
    form.logo = event.target.files[0];
};

const emit = defineEmits(["close-modal"]);

</script>

<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-500 font-bold inline-flex items-center">
            {{ form.id == 0 ? "Registrar Marca" : "Actualizar Marca" }}
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
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Descripción" class="text-slate-500 font-bold"/>
                        <TextArea
                            class="w-full h-28"
                            v-model="form.description"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.description"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Logo" class="text-slate-500 font-bold"/>
                        <TextInput
                            type="file"
                            class="w-full file:bg-transparent file:border-none file:px-1 file:mt-2 file:p-0 file:shadow-abajo-1 file:rounded-md file:text-slate-500 file:font-bold file:hover:text-slate-500  file:cursor-pointer"
                            @change="handleFileChange"
                            accept="image/*"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.logo"
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
