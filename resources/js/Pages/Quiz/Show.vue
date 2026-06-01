<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import confetti from 'canvas-confetti';

const props = defineProps({
    ecosystem: Object,
    questions: Array,
});

const form = useForm({
    answers: {},
    time_elapsed: 0,
});

let startTime = 0;

onMounted(() => {
    startTime = Date.now();
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
    form.time_elapsed = Math.floor((Date.now() - startTime) / 1000); // Segundos transcurridos
    
    // Disparar confetti antes de enviar (boom!)
    triggerConfetti();

    // Pequeño delay para disfrutar la animación
    setTimeout(() => {
        form.post(route('quiz.submit', props.ecosystem.id), {
            onSuccess: () => {
                // Redirige al dashboard automáticamente
            }
        });
    }, 1500);
};
</script>

<template>
    <Head :title="'Quiz: ' + ecosystem.title" />

    <AuthenticatedLayout>
        <div class="py-6 lg:py-12 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card mb-6 lg:mb-8 text-center bg-gradient-to-br from-emerald-600 to-teal-500 border-none shadow-xl text-white p-6 lg:p-8 transform hover:scale-105 transition-transform duration-500">
                <h1 class="text-2xl lg:text-3xl font-bold mb-2">Evaluación: {{ ecosystem.title }}</h1>
                <p class="text-sm lg:text-base text-emerald-100">Responde correctamente para sumar puntos a tu ranking. ¡El tiempo corre!</p>
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
                                class="flex items-center p-3 lg:p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-emerald-50 hover:border-emerald-300 transform transition-all duration-200 active:scale-95"
                                :class="{'bg-emerald-50 border-emerald-500 ring-2 ring-emerald-500 shadow-md scale-[1.02]': form.answers[question.id] === option.id}">
                                <input type="radio" :name="'question_'+question.id" :value="option.id" v-model="form.answers[question.id]" class="w-5 h-5 text-emerald-600 border-gray-300 focus:ring-emerald-500 mr-3 lg:mr-4 flex-shrink-0 transition-transform duration-200" :class="{'scale-125': form.answers[question.id] === option.id}">
                                <span class="text-sm lg:text-base text-gray-700 font-medium leading-tight">{{ option.text }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center pb-6">
                    <button type="submit" :disabled="form.processing" class="btn-primary w-full sm:w-auto text-base lg:text-lg px-8 lg:px-10 py-4 transform transition-transform duration-300 hover:scale-105 hover:-translate-y-1 shadow-lg hover:shadow-emerald-500/50" :class="{'opacity-50 cursor-not-allowed scale-100 translate-y-0': form.processing}">
                        <span v-if="form.processing" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Evaluando respuestas...
                        </span>
                        <span v-else class="flex items-center justify-center font-bold">
                            ¡Enviar y ver resultados! 🚀
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
