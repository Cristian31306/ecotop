<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    questions: Array,
    ecosystems: Array,
});

// Default to the first ecosystem if available
const activeEcosystemId = ref(props.ecosystems && props.ecosystems.length > 0 ? props.ecosystems[0].id : null);

const activeEcosystem = computed(() => {
    return props.ecosystems ? props.ecosystems.find(e => e.id === activeEcosystemId.value) : null;
});

const activeQuestions = computed(() => {
    if (!activeEcosystemId.value || !props.questions) return [];
    return props.questions.filter(q => q.ecosystem_id === activeEcosystemId.value);
});
</script>

<template>
    <Head title="Administrar Preguntas" />

    <AuthenticatedLayout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Administrar Preguntas</h2>
                    <p class="mt-1 text-sm text-gray-500">Organiza, revisa y redacta las preguntas correspondientes a cada ecosistema.</p>
                </div>
                <div class="flex items-center gap-3">
                    <Link :href="route('admin.ecosystems.index')" class="btn-secondary py-2.5 text-sm">
                        &larr; Volver a Ecosistemas
                    </Link>
                    <Link 
                        v-if="activeEcosystemId"
                        :href="route('admin.questions.create', { ecosystem_id: activeEcosystemId })" 
                        class="btn-primary py-2.5 text-sm"
                    >
                        Añadir Pregunta
                    </Link>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <!-- Sidebar (Ecosystem Selector) -->
                <div class="md:col-span-4 lg:col-span-3 space-y-3">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider px-2 mb-2">Ecosistemas (Días)</h3>
                    
                    <div class="space-y-2">
                        <button
                            v-for="eco in ecosystems"
                            :key="eco.id"
                            @click="activeEcosystemId = eco.id"
                            class="w-full text-left p-4 rounded-2xl transition-all duration-300 flex items-center justify-between group border"
                            :class="activeEcosystemId === eco.id
                                ? 'bg-gradient-to-r from-emerald-600 to-teal-500 text-white border-transparent shadow-lg shadow-emerald-200 transform scale-[1.02]'
                                : 'bg-white/60 hover:bg-white/90 text-gray-800 border-white/50 backdrop-blur-sm hover:translate-x-1'"
                        >
                            <div class="flex items-center space-x-3">
                                <div 
                                    class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs"
                                    :class="activeEcosystemId === eco.id ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-700'"
                                >
                                    D{{ eco.day_number }}
                                </div>
                                <div class="truncate max-w-[130px] lg:max-w-[160px]">
                                    <p class="font-bold text-sm leading-tight">{{ eco.title }}</p>
                                    <p class="text-xs leading-none mt-1" :class="activeEcosystemId === eco.id ? 'text-emerald-100' : 'text-gray-400'">
                                        Día {{ eco.day_number }}
                                    </p>
                                </div>
                            </div>
                            
                            <span 
                                class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold"
                                :class="activeEcosystemId === eco.id ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600'"
                            >
                                {{ eco.questions_count }}
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="md:col-span-8 lg:col-span-9">
                    <!-- If an ecosystem is selected -->
                    <div v-if="activeEcosystem" class="space-y-6">
                        <!-- Active Ecosystem Header Card -->
                        <div class="glass bg-white/80 p-6 rounded-3xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-white/60">
                            <div>
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full uppercase tracking-wider">
                                    Día {{ activeEcosystem.day_number }}
                                </span>
                                <h3 class="text-xl font-bold text-gray-800 mt-2">{{ activeEcosystem.title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Preguntas asignadas a este ecosistema</p>
                            </div>
                            
                            <Link 
                                :href="route('admin.questions.create', { ecosystem_id: activeEcosystem.id })" 
                                class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-full text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-colors duration-200"
                            >
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                </svg>
                                Nueva Pregunta
                            </Link>
                        </div>

                        <!-- Questions List -->
                        <div v-if="activeQuestions.length > 0" class="space-y-6">
                            <div 
                                v-for="(q, index) in activeQuestions" 
                                :key="q.id" 
                                class="glass bg-white/70 p-6 rounded-3xl hover:shadow-xl transition-all duration-300 border-white/60 relative overflow-hidden group"
                            >
                                <div class="flex flex-col lg:flex-row gap-6 justify-between items-start">
                                    <!-- Question Details -->
                                    <div class="flex-1 space-y-4">
                                        <div class="flex items-center space-x-2.5">
                                            <span class="text-sm font-bold text-emerald-600 bg-emerald-50 w-6 h-6 rounded-full flex items-center justify-center">
                                                {{ index + 1 }}
                                            </span>
                                            <h4 class="text-lg font-bold text-gray-800 leading-snug">{{ q.question_text }}</h4>
                                        </div>

                                        <!-- Options Display -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                            <div 
                                                v-for="(option, oIdx) in q.options" 
                                                :key="oIdx"
                                                class="p-3 rounded-2xl border text-sm flex items-start space-x-2.5 transition-colors"
                                                :class="oIdx === q.correct_option_index
                                                    ? 'bg-emerald-50/80 border-emerald-300 text-emerald-900 font-medium'
                                                    : 'bg-gray-50/50 border-gray-100 text-gray-600'"
                                            >
                                                <!-- Correct/Incorrect indicator -->
                                                <span class="mt-0.5 flex-shrink-0">
                                                    <svg v-if="oIdx === q.correct_option_index" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span v-else class="w-4 h-4 rounded-full border border-gray-300 flex items-center justify-center text-[10px] font-bold text-gray-400">
                                                        {{ String.fromCharCode(65 + oIdx) }}
                                                    </span>
                                                </span>
                                                <span class="break-words">{{ option }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Question Image Preview (if available) -->
                                    <div v-if="q.image_url" class="flex-shrink-0">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Imagen:</p>
                                        <img :src="q.image_url" alt="Imagen de pregunta" class="w-32 h-24 object-cover rounded-2xl border border-white/80 shadow-sm" />
                                    </div>
                                </div>

                                <!-- Card Actions -->
                                <div class="mt-6 pt-4 border-t border-gray-100/50 flex justify-end items-center">
                                    <Link 
                                        :href="route('admin.questions.edit', q.id)" 
                                        class="inline-flex items-center text-sm font-semibold text-emerald-600 hover:text-emerald-800 transition-colors"
                                    >
                                        Editar Pregunta &rarr;
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State for Questions -->
                        <div v-else class="glass bg-white/40 border-dashed border-2 border-emerald-200 p-12 text-center rounded-3xl">
                            <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-700">No hay preguntas registradas</h4>
                            <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">Este ecosistema aún no cuenta con preguntas de trivia para los usuarios. ¡Agrega una para comenzar!</p>
                            <Link 
                                :href="route('admin.questions.create', { ecosystem_id: activeEcosystem.id })" 
                                class="btn-primary mt-6 text-sm"
                            >
                                Añadir Primera Pregunta
                            </Link>
                        </div>
                    </div>

                    <!-- If no ecosystems exist in DB -->
                    <div v-else class="glass bg-white/40 p-12 text-center rounded-3xl">
                        <p class="text-gray-500">Primero debes crear ecosistemas en la administración para poder gestionar preguntas.</p>
                        <Link :href="route('admin.ecosystems.create')" class="btn-primary mt-6 text-sm">
                            Crear Ecosistema
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
