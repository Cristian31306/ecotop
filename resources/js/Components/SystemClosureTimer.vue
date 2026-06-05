<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();

const closureTimeStr = computed(() => page.props.system_closure_time);
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value?.role === 'admin');

const timeLeft = ref('');
const isVisible = ref(false);
const isExpired = ref(false);
let timerInterval = null;
let hasRedirected = false;

const calculateTimeLeft = () => {
    if (!closureTimeStr.value) {
        isVisible.value = false;
        return;
    }

    // Convertir la fecha de MySQL (UTC/Local) asumiendo que el navegador puede parsearla. 
    // Es mejor reemplazar los guiones o manejar el formato para Safari, pero Date.parse() usualmente funciona con "YYYY-MM-DD HH:mm:ss" si se le pone una T o dependiendo del timezone.
    // Reemplazamos espacio por T para mayor compatibilidad
    const safeDateStr = closureTimeStr.value.replace(' ', 'T');
    const closureTime = new Date(safeDateStr).getTime();
    const now = new Date().getTime();
    const distance = closureTime - now;

    // Si faltan más de 24 horas (24 * 60 * 60 * 1000 = 86400000 ms)
    if (distance > 86400000) {
        isVisible.value = false;
        return;
    }

    if (distance <= 0) {
        timeLeft.value = '00:00:00';
        isVisible.value = true;
        isExpired.value = true;
        return;
    }

    isVisible.value = true;
    isExpired.value = false;

    // Calcular horas (incluyendo días como horas, por lo que puede ser > 24)
    const hours = Math.floor(distance / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    timeLeft.value = 
        String(hours).padStart(2, '0') + ':' + 
        String(minutes).padStart(2, '0') + ':' + 
        String(seconds).padStart(2, '0');
};

onMounted(() => {
    calculateTimeLeft();
    timerInterval = setInterval(calculateTimeLeft, 1000);
});

onUnmounted(() => {
    if (timerInterval) clearInterval(timerInterval);
});
</script>

<template>
    <div v-if="isVisible" class="bg-red-600 text-white text-center py-2 px-4 font-bold sticky top-0 z-50">
        <span v-if="!isExpired">⚠️ El sistema se cerrará en: {{ timeLeft }}</span>
        <span v-else>🔴 El sistema está cerrado. <span v-if="isAdmin">(Regla no aplica para administradores)</span></span>
    </div>
</template>
