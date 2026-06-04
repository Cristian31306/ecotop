<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    user: Object,
    totalScore: Number,
    title: String,
});

const today = new Date().toLocaleDateString('es-CO', { 
    year: 'numeric', month: 'long', day: 'numeric' 
});
</script>

<template>
    <Head title="Mi Tarjeta de Logro" />

    <AuthenticatedLayout>
        <div class="print-container min-h-[80vh] flex flex-col items-center justify-center py-10 px-4">
            
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500 mb-2">
                    ¡Felicidades, lograste llegar al final!
                </h1>
                <p class="text-gray-500">Aquí está tu tarjeta de logro oficial de Expedición Ecotop</p>
            </div>

            <!-- TARJETA PREMIUM -->
            <div class="relative w-full max-w-md mx-auto aspect-[1.58/1] rounded-2xl overflow-hidden shadow-2xl transform hover:scale-[1.02] transition-transform duration-500 bg-gradient-to-br from-[#0b170e] to-[#1a2d1d]">
                
                <!-- Texturas / Patron de fondo -->
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-emerald-300 via-transparent to-transparent"></div>
                
                <!-- Bordes Dorados Internos -->
                <div class="absolute inset-2 border border-dashed border-[#c5a059]/40 rounded-xl"></div>
                <div class="absolute inset-3 border border-[#c5a059]/20 rounded-lg"></div>

                <div class="relative h-full flex flex-col p-6 lg:p-8 text-[#efe6d3] z-10">
                    
                    <!-- Header Tarjeta -->
                    <div class="flex justify-between items-start w-full">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-[#c5a059] flex items-center justify-center">
                                <div class="w-3 h-3 bg-[#0b170e] rotate-45"></div>
                            </div>
                            <span class="font-serif font-bold tracking-widest text-[#c5a059] text-sm uppercase">Ecotop</span>
                        </div>
                        <div class="text-right">
                            <div class="text-[9px] text-[#c5a059]/60 uppercase tracking-widest mb-0.5">ID Alumno</div>
                            <div class="text-xs font-mono font-bold text-[#efe6d3]/80">#{{ String(user.id).padStart(5, '0') }}</div>
                        </div>
                    </div>

                    <!-- Contenido Central -->
                    <div class="flex-1 flex flex-col justify-center items-center text-center mt-2">
                        <div class="text-[10px] text-[#c5a059] uppercase tracking-[0.2em] mb-2 font-semibold">Certificado de Finalización</div>
                        
                        <h2 class="font-serif italic text-3xl font-bold text-white mb-4 drop-shadow-md">
                            {{ user.name }}
                        </h2>
                        
                        <div class="w-32 h-[1px] bg-gradient-to-r from-transparent via-[#c5a059] to-transparent mb-4"></div>
                        
                        <div class="text-xs text-[#efe6d3]/70 max-w-[80%] leading-relaxed mb-4">
                            Ha completado exitosamente la Expedición Ecotop, demostrando conocimiento y compromiso ambiental.
                        </div>

                        <!-- Stats -->
                        <div class="flex items-center justify-center gap-4 w-full">
                            <div class="bg-black/30 border border-[#c5a059]/30 rounded-lg px-3 py-1.5 flex flex-col items-center">
                                <span class="text-[9px] uppercase tracking-wider text-[#c5a059]/80">Rango Obtenido</span>
                                <span class="text-xs font-bold text-[#c5a059]">{{ title }}</span>
                            </div>
                            <div class="bg-black/30 border border-[#c5a059]/30 rounded-lg px-3 py-1.5 flex flex-col items-center">
                                <span class="text-[9px] uppercase tracking-wider text-[#c5a059]/80">Puntaje</span>
                                <span class="text-xs font-bold text-white">{{ totalScore }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Tarjeta -->
                    <div class="w-full flex justify-between items-end mt-auto">
                        <div class="text-[9px] text-[#efe6d3]/50 tracking-wider">
                            EMITIDO: <br> <span class="text-[#c5a059]">{{ today }}</span>
                        </div>
                        <div class="w-10 h-10 border border-[#c5a059]/50 rounded-full flex items-center justify-center opacity-80 shadow-[0_0_10px_rgba(197,160,89,0.2)]">
                            <span class="text-[8px] font-bold text-[#c5a059]">SELLO</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-10 flex flex-col items-center gap-3 text-center">
                <p class="text-emerald-700 font-medium text-sm lg:text-base">📸 ¡Toma una captura de pantalla para guardar tu logro!</p>
                <Link :href="route('dashboard')" class="btn-primary px-8 py-3 mt-2 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Volver al Dashboard
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Ocultar elementos innecesarios al imprimir para que solo salga la tarjeta */
@media print {
    body * {
        visibility: hidden;
    }
    .print-container > div:nth-child(2), .print-container > div:nth-child(2) * {
        visibility: visible;
    }
    .print-container > div:nth-child(2) {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        max-width: 100%;
        margin: 0;
        box-shadow: none;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
