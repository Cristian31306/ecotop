<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Leaderboard from '@/Components/Leaderboard.vue';

defineProps({
    ecosystems: Array,
    userScoresCount: Number,
    isAdmin: Boolean,
    graduationData: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-6 lg:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 lg:space-y-8">
                
                <!-- Hero Section -->
                <div class="glass rounded-3xl p-6 md:p-8 lg:p-12 text-center md:text-left flex flex-col md:flex-row items-center justify-between">
                    <div>
                        <h1 class="text-3xl lg:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500 mb-3 lg:mb-4">
                            Expedición Ecotop
                        </h1>
                        <p class="text-base lg:text-lg text-emerald-800 max-w-2xl">
                            Explora los biomas únicos de Colombia. Lee sobre cada ecosistema y pon a prueba tus conocimientos.
                        </p>
                    </div>
                    <div class="mt-6 md:mt-0 flex flex-col items-center">
                        <div class="text-4xl lg:text-5xl font-bold text-emerald-600 mb-1 lg:mb-2">{{ userScoresCount }}/5</div>
                        <div class="text-xs lg:text-sm text-emerald-800 font-semibold uppercase tracking-wider text-center">Ecosistemas completados</div>
                        <Link v-if="userScoresCount === 5" :href="route('diploma.download')" class="mt-4 btn-primary text-sm lg:text-base w-full sm:w-auto text-center">
                            Ver Mi Tarjeta de Logro
                        </Link>
                    </div>
                </div>

                <!-- Final Graduation Card -->
                <div v-if="graduationData" class="bg-gradient-to-br from-emerald-900 to-teal-900 rounded-3xl p-1 relative overflow-hidden shadow-2xl mb-8 transform hover:scale-[1.01] transition-transform duration-300">
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-20"></div>
                    <div class="bg-slate-900/80 backdrop-blur-md rounded-[22px] p-6 md:p-10 text-white flex flex-col items-center justify-center text-center relative z-10 border border-emerald-500/30">
                        <div class="inline-flex items-center justify-center p-4 bg-emerald-500/20 rounded-full mb-6 ring-2 ring-emerald-400/50">
                            <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h2 class="text-xs uppercase tracking-[0.3em] text-emerald-400 font-bold mb-2">Evaluación Finalizada</h2>
                        <h3 class="text-4xl md:text-5xl font-black mb-4 text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-emerald-400 leading-tight">
                            {{ graduationData.title }}
                        </h3>
                        <p class="text-lg md:text-xl text-slate-300 max-w-2xl mb-8">
                            {{ graduationData.message }}
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full max-w-3xl mb-8">
                            <div v-for="(stat, idx) in graduationData.fakeStats" :key="idx" class="bg-black/40 rounded-xl p-4 border border-emerald-900/50">
                                <div class="text-2xl font-bold text-emerald-300 mb-1">{{ stat.value }}</div>
                                <div class="text-xs text-emerald-100/70 uppercase tracking-wider">{{ stat.label }}</div>
                            </div>
                        </div>

                        <Link :href="route('diploma.download')" class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-bold rounded-xl shadow-[0_0_20px_rgba(16,185,129,0.4)] transition-all flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Ver Mi Tarjeta de Logro
                        </Link>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Ecosystems Bento Grid -->
                    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                        <div v-for="(eco, index) in ecosystems" :key="eco.id" 
                            class="glass-card overflow-hidden flex flex-col group relative"
                            :class="{'opacity-80 grayscale hover:grayscale-0 transition-all duration-300': eco.is_locked, 'md:col-span-2': index === 0 || index === 3}">
                            
                            <div class="p-6 lg:p-8 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs lg:text-sm font-bold rounded-full uppercase tracking-wider">
                                        Día {{ eco.day_number }}
                                    </span>
                                    <div v-if="eco.is_locked" class="text-gray-500 bg-gray-100 border border-gray-200 p-2 rounded-full backdrop-blur-sm" title="Bloqueado">
                                        <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                    <div v-else-if="eco.score !== null" class="text-emerald-500 bg-emerald-50 p-2 rounded-full" title="Completado">
                                        <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                </div>
                                
                                <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2 group-hover:text-emerald-600 transition-colors">{{ eco.title }}</h2>
                                
                                <div class="mt-auto pt-6 flex flex-col gap-3">
                                    <div v-if="eco.score !== null" class="font-bold text-emerald-600 text-base lg:text-lg">
                                        Puntaje: {{ eco.score }}
                                    </div>
                                    
                                    <div v-if="eco.is_locked && eco.available_date" class="text-sm font-bold text-amber-600 bg-amber-50 px-3 py-2 rounded-lg border border-amber-200 shadow-sm flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Disponible el {{ eco.available_date }}
                                    </div>
                                    <div v-else-if="eco.is_locked" class="text-sm text-gray-500 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Completa el día anterior
                                    </div>
                                    
                                    <Link v-if="!eco.is_locked" :href="route('ecosystem.show', eco.id)" class="btn-primary w-full text-center text-sm lg:text-base">
                                        {{ eco.score !== null ? 'Repasar Lección' : 'Iniciar Expedición' }}
                                    </Link>
                                    <Link v-else-if="isAdmin" :href="route('ecosystem.show', eco.id)" class="bg-gray-800 text-white hover:bg-gray-700 py-2 px-4 rounded-lg font-bold w-full text-center text-sm lg:text-base flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                        Probar Nivel (Admin)
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Col -->
                    <div class="lg:col-span-1">
                        <Leaderboard />
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
