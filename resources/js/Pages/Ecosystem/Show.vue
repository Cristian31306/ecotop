<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    ecosystem: Object,
    hasCompleted: Boolean,
    score: Number,
    canRetry: Boolean,
});

// El contenido ahora viene como Array JSON gracias al backend
const contentCards = computed(() => {
    if (!props.ecosystem.content || !Array.isArray(props.ecosystem.content)) return [];
    return props.ecosystem.content;
});

const currentSlide = ref(0);
const isImageZoomed = ref(false);

const nextSlide = () => {
    if (currentSlide.value < contentCards.value.length - 1) {
        currentSlide.value++;
        isImageZoomed.value = false;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};

const prevSlide = () => {
    if (currentSlide.value > 0) {
        currentSlide.value--;
        isImageZoomed.value = false;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};
</script>

<template>
    <Head :title="ecosystem.title" />

    <AuthenticatedLayout>
        <div class="py-6 lg:py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 lg:mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <Link :href="route('dashboard')" class="text-emerald-600 hover:text-emerald-800 font-medium inline-flex items-center transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Dashboard
                </Link>
                <div v-if="hasCompleted" class="bg-emerald-100 text-emerald-800 px-4 py-2 rounded-full font-bold shadow-sm text-center sm:text-left text-sm lg:text-base border border-emerald-200">
                    Puntaje obtenido: {{ score }} pts
                </div>
            </div>

            <!-- Cabecera del Día -->
            <div class="text-center mb-8 lg:mb-12">
                <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-800 text-sm lg:text-base font-bold rounded-full uppercase tracking-widest shadow-sm border border-emerald-200 mb-4">Día {{ ecosystem.day_number }}</span>
                <h1 class="text-4xl lg:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-700 to-teal-500 tracking-tight">{{ ecosystem.title }}</h1>
            </div>

            <!-- Carrusel de Diapositivas -->
            <div v-if="contentCards.length > 0" class="mb-12">
                <div class="glass-card overflow-hidden relative shadow-2xl border-white/50 border bg-white/70 backdrop-blur-xl rounded-3xl min-h-[400px] flex flex-col">
                    
                    <!-- Indicador de Progreso superior -->
                    <div class="bg-emerald-50/80 border-b border-emerald-100/50 px-6 py-3 flex justify-between items-center text-sm font-bold text-emerald-700">
                        <span>Lección Teórica</span>
                        <span class="bg-emerald-200/50 px-3 py-1 rounded-full">{{ currentSlide + 1 }} de {{ contentCards.length }}</span>
                    </div>

                    <!-- Contenido de la Diapositiva -->
                    <div class="p-8 lg:p-12 flex-1 relative flex items-center justify-center">
                        <transition name="fade" mode="out-in">
                            <div :key="currentSlide" class="w-full max-w-3xl mx-auto flex flex-col items-start">
                                
                                <!-- Renderizar Imagen si existe -->
                                <div 
                                    v-if="contentCards[currentSlide].image" 
                                    class="mb-6 lg:mb-8 w-full rounded-2xl overflow-hidden shadow-lg border border-gray-100/50 bg-white relative group cursor-zoom-in"
                                    @click="isImageZoomed = true"
                                >
                                    <div class="w-full aspect-[16/9] md:aspect-auto md:h-[400px] overflow-hidden">
                                        <img 
                                            :src="contentCards[currentSlide].image" 
                                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                                            alt="Ilustración del Ecosistema"
                                        >
                                    </div>
                                    
                                    <!-- Icono de Zoom al pasar el cursor (o pulsar) -->
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <div class="bg-white/20 backdrop-blur-md p-3 rounded-full text-white shadow-lg border border-white/30 transform scale-90 group-hover:scale-100 transition-transform duration-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="w-full prose prose-emerald md:prose-lg max-w-none text-gray-700 leading-relaxed text-base lg:text-lg prose-headings:text-emerald-800 prose-a:text-emerald-600">
                                    <div v-html="contentCards[currentSlide].text"></div>
                                </div>
                            </div>
                        </transition>
                    </div>
                    
                    <!-- Controles del Carrusel -->
                    <div class="p-4 sm:p-6 bg-white/40 border-t border-gray-100/50 flex justify-between items-center gap-2 sm:gap-4">
                        <button @click="prevSlide" 
                                :disabled="currentSlide === 0"
                                class="flex items-center justify-center px-4 py-3 sm:px-6 sm:py-3 rounded-xl font-bold transition-all"
                                :class="currentSlide === 0 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 hover:shadow-md'">
                            <svg class="w-5 h-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            <span class="hidden sm:inline">Anterior</span>
                        </button>

                        <div class="flex gap-1.5 sm:gap-2 flex-wrap justify-center">
                            <div v-for="(_, index) in contentCards" :key="index"
                                 class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full transition-all duration-300"
                                 :class="index === currentSlide ? 'bg-emerald-600 w-4 sm:w-6' : 'bg-emerald-200'">
                            </div>
                        </div>

                        <button @click="nextSlide" 
                                :disabled="currentSlide === contentCards.length - 1"
                                class="flex items-center justify-center px-4 py-3 sm:px-6 sm:py-3 rounded-xl font-bold transition-all"
                                :class="currentSlide === contentCards.length - 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-emerald-600 text-white hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-500/30'">
                            <span class="hidden sm:inline">Siguiente</span>
                            <svg class="w-5 h-5 sm:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Botón de Evaluación (Solo si llegó al final o ya completó) -->
            <transition name="fade">
                <div v-if="currentSlide === contentCards.length - 1 || hasCompleted" class="text-center bg-white/40 backdrop-blur-md rounded-3xl p-8 lg:p-12 border border-white shadow-xl max-w-2xl mx-auto mt-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">{{ hasCompleted ? 'Lección Completada' : '¡Has terminado la lectura!' }}</h3>
                    <Link v-if="!hasCompleted || canRetry" :href="route('quiz.show', ecosystem.id)" class="btn-primary text-xl px-10 py-5 w-full sm:w-auto shadow-emerald-500/30 hover:shadow-emerald-500/50 hover:scale-105 transform transition-all duration-300 inline-block animate-bounce">
                        {{ hasCompleted ? 'Reintentar Evaluación (Admin)' : 'Iniciar Evaluación Final' }}
                    </Link>
                    <p v-else class="text-emerald-700 font-semibold bg-emerald-50 py-3 px-6 rounded-full inline-block border border-emerald-100">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Ya has completado y aprobado la evaluación de este ecosistema.
                    </p>
                </div>
            </transition>
            
            <!-- Lightbox/Zoom Modal -->
            <transition name="fade-zoom">
                <div 
                    v-if="isImageZoomed && contentCards[currentSlide].image" 
                    @click="isImageZoomed = false"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-lg p-4 cursor-zoom-out animate-fade-in"
                >
                    <!-- Close button -->
                    <button 
                        @click="isImageZoomed = false"
                        class="absolute top-4 right-4 text-white/70 hover:text-white bg-white/10 hover:bg-white/25 p-3 rounded-full border border-white/10 transition-colors"
                        title="Cerrar vista completa"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    
                    <!-- Zoomed Image -->
                    <img 
                        :src="contentCards[currentSlide].image" 
                        class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl border border-white/10 transform transition-transform duration-300"
                        alt="Vista ampliada"
                    >
                </div>
            </transition>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Transición suave para las diapositivas */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-enter-from {
  opacity: 0;
  transform: translateX(20px);
}

.fade-leave-to {
  opacity: 0;
  transform: translateX(-20px);
}

/* Transición para el zoom del lightbox */
.fade-zoom-enter-active,
.fade-zoom-leave-active {
  transition: opacity 0.3s ease;
}
.fade-zoom-enter-active img,
.fade-zoom-leave-active img {
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.fade-zoom-enter-from {
  opacity: 0;
}
.fade-zoom-enter-from img {
  transform: scale(0.9);
}
.fade-zoom-leave-to {
  opacity: 0;
}
.fade-zoom-leave-to img {
  transform: scale(0.95);
}

/* ==========================================================================
   Estilos Premium para el HTML Inyectado (Magia automática para el editor)
   ========================================================================== */

/* H1 - Títulos Principales */
:deep(.prose h1) {
  @apply text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-700 to-teal-500 mb-6 pb-4 border-b-2 border-emerald-100 !important;
}

/* H2 - Subtítulos de sección con ícono automático */
:deep(.prose h2) {
  @apply text-2xl md:text-3xl font-bold text-emerald-800 mb-6 mt-8 flex items-center gap-3 !important;
}
:deep(.prose h2::before) {
  content: '🌿';
  @apply text-2xl;
}

/* Subtitle - Texto introductorio */
:deep(.prose .subtitle) {
  @apply text-lg md:text-xl text-emerald-700 font-medium italic border-l-4 border-emerald-400 pl-4 py-3 bg-emerald-50/80 rounded-r-xl shadow-sm mb-8 block !important;
}

/* Párrafos normales */
:deep(.prose p) {
  @apply text-gray-700 text-lg leading-relaxed mb-6 !important;
}

/* Negritas destacadas */
:deep(.prose strong) {
  @apply text-emerald-900 font-bold bg-emerald-100/50 px-1.5 py-0.5 rounded text-opacity-90 !important;
}

/* 
  Listas UL y OL transformadas en "Cuadros/Tarjetas" 
*/
:deep(.prose ul), 
:deep(.prose ol) {
  @apply grid grid-cols-1 sm:grid-cols-2 gap-4 pl-0 list-none mt-6 mb-8 !important;
  counter-reset: awesome-counter;
}

/* Cada Item (LI) ahora es una tarjeta Bento */
:deep(.prose li) {
  @apply bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm hover:shadow-md hover:-translate-y-1 hover:border-emerald-300 transition-all duration-300 relative pl-14 m-0 !important;
}

/* Viñeta para OL (Números) */
:deep(.prose ol li) {
  counter-increment: awesome-counter;
}
:deep(.prose ol li::before) {
  content: counter(awesome-counter);
  @apply absolute left-4 top-5 bg-emerald-100 text-emerald-700 font-bold w-7 h-7 rounded-full flex items-center justify-center text-sm shadow-inner !important;
}

/* Viñeta para UL (Hojas por defecto) */
:deep(.prose ul li::before) {
  content: '🍃';
  @apply absolute left-4 top-5 text-xl flex items-center justify-center w-7 h-7 !important;
}

/* curious-fact-title: El badge superior en la tarjeta */
:deep(.prose .curious-fact-title) {
  @apply block font-extrabold text-lg text-emerald-700 mb-3 border-b border-emerald-100 pb-2 uppercase tracking-wide text-xs md:text-sm !important;
}

/* fauna-item: Variante de color para tarjetas de fauna */
:deep(.prose .fauna-item) {
  @apply bg-orange-50/50 border-orange-200 hover:border-orange-400 !important;
}
:deep(.prose .fauna-item::before) {
  content: '🐾' !important;
}
:deep(.prose .fauna-item strong) {
  @apply text-orange-900 bg-orange-100/50 !important;
}
</style>
