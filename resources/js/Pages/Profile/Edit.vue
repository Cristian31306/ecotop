<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Mi Perfil" />

    <AuthenticatedLayout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 px-4 sm:px-0">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Mi Perfil</h2>
                <p class="mt-1 text-sm text-gray-500">Gestiona la información de tu cuenta, seguridad y preferencias.</p>
            </div>

            <div class="space-y-8">
                <!-- Información del Perfil -->
                <div class="glass bg-white/75 p-6 sm:p-10 rounded-3xl border border-white/60 shadow-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-2xl"
                    />
                </div>

                <!-- Actualizar Contraseña -->
                <div class="glass bg-white/75 p-6 sm:p-10 rounded-3xl border border-white/60 shadow-lg">
                    <UpdatePasswordForm class="max-w-2xl" />
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
