<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import confetti from 'canvas-confetti';

const props = defineProps({
    ecosystem: Object,
});

const step = ref('glitch'); // 'glitch', 'question', 'success'
const glitchText = ref('');
const glitchMessages = [
    'INICIANDO PROTOCOLO DE ANÁLISIS...',
    'DETECTANDO PATRONES INUSUALES...',
    'ALERTA: TIEMPO DE RESPUESTA SOSPECHOSAMENTE RÁPIDO.',
    'ANALIZANDO NIVELES DE CONTRABANDO DE RESPUESTAS...',
    'ERROR CRÍTICO: USUARIO DEMASIADO PRO.',
    'ACTIVANDO PREGUNTA DE SEGURIDAD MÁXIMA...'
];

const selectedAnswer = ref(null);

onMounted(() => {
    // Secuencia de Glitch
    let currentMsg = 0;
    const glitchInterval = setInterval(() => {
        if (currentMsg < glitchMessages.length) {
            glitchText.value += glitchMessages[currentMsg] + '\n';
            currentMsg++;
        } else {
            clearInterval(glitchInterval);
            setTimeout(() => {
                step.value = 'question';
            }, 1000);
        }
    }, 800); // 800ms por línea
});

const submitJokeAnswer = (index) => {
    selectedAnswer.value = index;
    step.value = 'success';
    
    // Lanzar confeti épico
    const duration = 3000;
    const animationEnd = Date.now() + duration;
    const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

    const randomInRange = (min, max) => Math.random() * (max - min) + min;

    const interval = setInterval(function() {
        const timeLeft = animationEnd - Date.now();

        if (timeLeft <= 0) {
            return clearInterval(interval);
        }

        const particleCount = 50 * (timeLeft / duration);
        confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
        confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
    }, 250);
};

const goToDashboard = () => {
    router.visit(route('dashboard'));
};
</script>

<template>
    <Head title="¡ERROR DEL SISTEMA!" />

    <!-- STEP 1: GLITCH TERMINAL -->
    <div v-if="step === 'glitch'" class="min-h-screen bg-black text-green-500 font-mono p-8 flex flex-col justify-center items-start overflow-hidden relative">
        <!-- Efecto Scanline -->
        <div class="absolute inset-0 pointer-events-none opacity-20 bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.06),rgba(0,255,0,0.02),rgba(0,0,255,0.06))] bg-[length:100%_4px,3px_100%] z-50"></div>
        
        <div class="max-w-3xl mx-auto w-full z-10">
            <h1 class="text-4xl md:text-6xl font-bold mb-8 text-red-600 animate-pulse">! ALERTA DE SISTEMA !</h1>
            <pre class="whitespace-pre-wrap text-lg md:text-2xl leading-relaxed">{{ glitchText }}<span class="animate-ping">_</span></pre>
        </div>
    </div>

    <!-- STEP 2: THE JOKE QUESTION -->
    <div v-if="step === 'question'" class="min-h-screen bg-slate-900 text-white flex flex-col items-center justify-center p-4 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-red-900/20 to-purple-900/20 z-0"></div>
        
        <div class="glass-card max-w-2xl w-full p-8 md:p-12 relative z-10 border-2 border-red-500/50 shadow-[0_0_50px_rgba(220,38,38,0.3)] text-center transform transition-all animate-bounce-in">
            <h2 class="text-3xl md:text-5xl font-black mb-2 text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-yellow-400">
                EL JEFE FINAL
            </h2>
            <p class="text-slate-300 mb-8 font-mono text-sm uppercase tracking-widest">Protocolo de máxima seguridad</p>
            
            <div class="text-2xl md:text-3xl font-bold mb-10 leading-tight">
                ¿De qué color era el caballo blanco de Simón Bolívar?
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button @click="submitJokeAnswer(0)" class="px-6 py-4 bg-slate-800 hover:bg-slate-700 border border-slate-600 rounded-xl font-bold text-lg transition-colors">
                    Blanco, duh.
                </button>
                <button @click="submitJokeAnswer(1)" class="px-6 py-4 bg-slate-800 hover:bg-slate-700 border border-slate-600 rounded-xl font-bold text-lg transition-colors">
                    Color café con leche
                </button>
                <button @click="submitJokeAnswer(2)" class="px-6 py-4 bg-slate-800 hover:bg-slate-700 border border-slate-600 rounded-xl font-bold text-lg transition-colors">
                    Era un unicornio 🦄
                </button>
                <button @click="submitJokeAnswer(3)" class="px-6 py-4 bg-slate-800 hover:bg-slate-700 border border-slate-600 rounded-xl font-bold text-lg transition-colors">
                    No me acuerdo, yo estaba madrugando para el reto.
                </button>
            </div>
        </div>
    </div>

    <!-- STEP 3: SUCCESS & CONFETTI -->
    <div v-if="step === 'success'" class="min-h-screen bg-slate-900 text-white flex flex-col items-center justify-center p-4 relative overflow-hidden">
        <div class="glass-card max-w-2xl w-full p-10 md:p-16 relative z-10 text-center flex flex-col items-center shadow-[0_0_100px_rgba(16,185,129,0.2)]">
            <div class="w-24 h-24 bg-emerald-500 rounded-full flex items-center justify-center mb-8 animate-bounce">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            
            <h2 class="text-4xl md:text-6xl font-black mb-4 text-emerald-400">
                ¡CAYERON! 😂
            </h2>
            <p class="text-xl md:text-2xl text-slate-300 mb-8 max-w-lg">
                No importa el color del caballo. ¡Felicidades por sobrevivir a la Expedición Ecotop!
            </p>
            <p class="text-md text-slate-400 mb-10 italic">
                (Y gracias por madrugar, trasnochar y armar sus redes de respuestas, nos dimos cuenta de todo 👀)
            </p>
            
            <button @click="goToDashboard" class="btn-primary px-10 py-4 text-xl font-bold rounded-full w-full sm:w-auto shadow-lg shadow-emerald-600/30">
                Reclamar mi Título Honorífico
            </button>
        </div>
    </div>
</template>

<style scoped>
@keyframes bounce-in {
    0% { transform: scale(0.8); opacity: 0; }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); opacity: 1; }
}
.animate-bounce-in {
    animation: bounce-in 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}
</style>
