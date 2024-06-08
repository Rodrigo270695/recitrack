<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    vehicleimage: Object,
});

const form = useForm({
    vehicle_id: props.vehicleimage.id,
    image: null,
    profile: '',
});

const submit = () => {
    form.post(route("vehicles.images.store"), {
        preserveScroll: true,
        onSuccess: () => emit("close-modal"),
    });
};

const handleFileChange = (event) => {
    form.image = event.target.files[0];
};

const emit = defineEmits(["close-modal"]);

</script>

<template>
    <div class="flex justify-between bg-slate-300 h-12 px-4">
        <div class="text-lg sm:text-xl text-slate-500 font-bold inline-flex items-center">
            Registrar Imagen
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
                        <InputLabel value="Logo" class="text-slate-500 font-bold"/>
                        <TextInput
                            type="file"
                            class="w-full file:bg-transparent file:border-none file:px-1 file:mt-2 file:p-0 file:shadow-abajo-1 file:rounded-md file:text-slate-500 file:font-bold file:hover:text-slate-500  file:cursor-pointer"
                            @change="handleFileChange"
                            accept="image/*"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.image"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <input id="profile" type="checkbox" v-model="form.profile" class="w-4 h-4 text-blue-600 focus:ring-slate-500 border-gray-300 rounded checked:bg-slate-500">
                        <label for="profile" class="ml-2 text-sm text-slate-500">Marcar como foto de perfil</label>
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
