<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import confetti from 'canvas-confetti';

const props = defineProps({
    ecosystem: Object,
    questions: Array,
});

const form = useForm({
    answers: {},
});

const preventDefault = (e) => {
    e.preventDefault();
};

const handleKeyDown = (e) => {
    // Bloquear Ctrl+C, Ctrl+V, Ctrl+U (Ver código fuente), Ctrl+Shift+I (Consola) y F12
    if (
        (e.ctrlKey && ['c', 'v', 'u'].includes(e.key.toLowerCase())) ||
        (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'i') ||
        e.key === 'F12'
    ) {
        e.preventDefault();
    }
};

onMounted(() => {
    // Bloquear clic derecho y teclado para evitar copiar
    document.addEventListener('contextmenu', preventDefault);
    document.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    document.removeEventListener('contextmenu', preventDefault);
    document.removeEventListener('keydown', handleKeyDown);
});

const triggerConfetti = () => {
    var duration = 3 * 1000;
    var animationEnd = Date.now() + duration;
    var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 100 };

    function randomInRange(min, max) {
        return Math.random() * (max - min) + min;
    }

    var interval = setInterval(function() {
        var timeLeft = animationEnd - Date.now();

        if (timeLeft <= 0) {
            return clearInterval(interval);
        }

        var particleCount = 50 * (timeLeft / duration);
        confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
        confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
    }, 250);
};

const submitQuiz = () => {
    // Si es el ecosistema del quinto día, reproducimos la alerta de inmediato
    if (props.ecosystem.day_number === 5 || props.ecosystem.day_number == '5') {
        window.alertaAudio = new Audio('/alerta.m4a');
        window.alertaAudio.play().catch(e => console.log('Audio play failed:', e));
    }

    // Disparar confetti antes de enviar (boom!)
    triggerConfetti();

    // Pequeño delay para disfrutar la animación
    setTimeout(() => {
        form.post(route('quiz.submit', props.ecosystem.id), {
            onSuccess: () => {
                // Redirige al dashboard o a post_credits automáticamente
            }
        });
    }, 1500);
};
</script>

<template>
    <Head :title="'Quiz: ' + ecosystem.title" />

    <AuthenticatedLayout>
        <div class="py-6 lg:py-12 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 select-none">
            <div class="glass-card mb-6 lg:mb-8 text-center bg-gradient-to-br from-emerald-600 to-teal-500 border-none shadow-xl text-white p-6 lg:p-8 transform hover:scale-105 transition-transform duration-500">
                <h1 class="text-2xl lg:text-3xl font-bold mb-2">Evaluación: {{ ecosystem.title }}</h1>
                <p class="text-sm lg:text-base text-emerald-100">Responde correctamente para sumar puntos a tu ranking. ¡El tiempo corre!</p>
            </div>

            <div v-if="$page.props.is_system_closed" class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg text-center font-bold shadow-md animate-pulse">
                ⏳ El tiempo para responder se ha agotado. Ya no puedes enviar respuestas.
            </div>

            <form @submit.prevent="submitQuiz">
                <div class="space-y-6 lg:space-y-8">
                    <div v-for="(question, qIndex) in questions" :key="question.id" class="glass-card p-5 lg:p-6 shadow-lg hover:shadow-2xl transition-shadow duration-300">
                        <div class="flex flex-col mb-4">
                            <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full w-max mb-3 uppercase tracking-wider">Pregunta {{ qIndex + 1 }}</span>
                            <h3 class="text-lg lg:text-xl font-bold text-gray-800">{{ question.question_text }}</h3>
                        </div>
                        
                        <!-- Imagen opcional con animación -->
                        <div v-if="question.image_url" class="mb-6 rounded-2xl overflow-hidden shadow-sm group">
                            <img :src="question.image_url" alt="Imagen de apoyo" class="w-full h-auto max-h-64 object-cover transform group-hover:scale-105 transition-transform duration-700">
                        </div>
                        
                        <div class="space-y-3">
                            <label v-for="option in question.options" :key="option.id" 
                                class="flex items-center p-3 lg:p-4 border border-gray-200 rounded-xl transition-all duration-200"
                                :class="{
                                    'cursor-pointer hover:bg-emerald-50 hover:border-emerald-300 active:scale-95': !$page.props.is_system_closed,
                                    'cursor-not-allowed opacity-60': $page.props.is_system_closed,
                                    'bg-emerald-50 border-emerald-500 ring-2 ring-emerald-500 shadow-md scale-[1.02]': form.answers[question.id] === option.id
                                }">
                                <input type="radio" :name="'question_'+question.id" :value="option.id" v-model="form.answers[question.id]" 
                                    :disabled="$page.props.is_system_closed"
                                    class="w-5 h-5 text-emerald-600 border-gray-300 focus:ring-emerald-500 mr-3 lg:mr-4 flex-shrink-0 transition-transform duration-200" 
                                    :class="{'scale-125': form.answers[question.id] === option.id}">
                                <span class="text-sm lg:text-base text-gray-700 font-medium leading-tight">{{ option.text }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center pb-6">
                    <button type="submit" :disabled="form.processing || $page.props.is_system_closed" 
                        class="btn-primary w-full sm:w-auto text-base lg:text-lg px-8 lg:px-10 py-4 transform transition-transform duration-300 shadow-lg" 
                        :class="{
                            'opacity-50 cursor-not-allowed scale-100 translate-y-0': form.processing || $page.props.is_system_closed,
                            'hover:scale-105 hover:-translate-y-1 hover:shadow-emerald-500/50': !form.processing && !$page.props.is_system_closed
                        }">
                        <span v-if="form.processing" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Evaluando respuestas...
                        </span>
                        <span v-else class="flex items-center justify-center font-bold">
                            {{ $page.props.is_system_closed ? 'Tiempo agotado' : '¡Enviar y ver resultados! 🚀' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
