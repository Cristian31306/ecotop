<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    system_closure_time: {
        type: String,
        default: ''
    }
});

const form = useForm({
    system_closure_time: props.system_closure_time || ''
});

const submit = () => {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => alert('Configuración guardada.')
    });
};
</script>

<template>
    <Head title="Configuración del Sistema" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Configuración del Sistema</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="mt-6 space-y-6 max-w-xl">
                            <div>
                                <InputLabel for="system_closure_time" value="Fecha y Hora de Cierre del Sistema" />

                                <TextInput
                                    id="system_closure_time"
                                    type="datetime-local"
                                    class="mt-1 block w-full"
                                    v-model="form.system_closure_time"
                                />

                                <InputError class="mt-2" :message="form.errors.system_closure_time" />
                                <p class="mt-2 text-sm text-gray-500">
                                    Si estableces una fecha, los usuarios verán un temporizador 24h antes. Cuando llegue el momento, serán expulsados (excepto administradores). Para desactivarlo, deja este campo vacío.
                                </p>
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">Guardar Cambios</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
