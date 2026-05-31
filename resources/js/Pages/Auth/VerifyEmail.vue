<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Verificación de Correo" />

        <div class="mb-4 text-sm text-gray-600 leading-relaxed">
            ¡Gracias por registrarte! Antes de comenzar, ¿podrías verificar tu dirección de correo haciendo clic en el enlace que te acabamos de enviar? Si no lo recibiste, con gusto te enviaremos otro.
        </div>

        <div
            class="mb-4 text-sm font-medium text-emerald-600"
            v-if="verificationLinkSent"
        >
            Se ha enviado un nuevo enlace de verificación a la dirección de correo proporcionada durante el registro.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Reenviar Correo de Verificación
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="rounded-md text-sm text-gray-600 underline hover:text-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors"
                >
                    Cerrar Sesión
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
