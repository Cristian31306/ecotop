<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    feedbacks: Array,
    averageRating: Number,
    totalCount: Number,
    distribution: Object,
});

// Formatear fechas amigables
const formatDate = (dateStr) => {
    const d = new Date(dateStr);
    return d.toLocaleDateString('es-CO', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Comentarios y Calificaciones" />

    <AuthenticatedLayout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Comentarios y Calificaciones</h2>
                    <p class="mt-1 text-sm text-gray-500">Analiza las opiniones de los usuarios sobre la aplicación Ecotop.</p>
                </div>
                <Link :href="route('admin.ecosystems.index')" class="btn-secondary py-2.5 text-sm">
                    &larr; Volver a Ecosistemas
                </Link>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 mb-10">
                <!-- Average Rating Card -->
                <div class="glass-card flex flex-col items-center justify-center p-8 text-center bg-gradient-to-br from-emerald-500 to-teal-600 text-white border-none shadow-lg">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-100">Calificación Promedio</p>
                    <p class="text-6xl font-black my-2 leading-none">{{ averageRating }}</p>
                    
                    <!-- Stars Row -->
                    <div class="flex items-center space-x-1 mb-3">
                        <svg
                            v-for="star in 5"
                            :key="star"
                            class="w-6 h-6"
                            :class="star <= Math.round(averageRating) ? 'text-amber-300 fill-amber-300' : 'text-emerald-300'"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                        >
                            <path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192L12 .587z" />
                        </svg>
                    </div>
                    
                    <p class="text-xs text-emerald-100 font-medium">Basado en {{ totalCount }} calificaciones</p>
                </div>

                <!-- Star Distribution Chart (spanning 2 cols on md) -->
                <div class="glass-card p-6 md:col-span-2 flex flex-col justify-center space-y-3">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Desglose de calificaciones</h3>
                    
                    <div v-for="stars in [5, 4, 3, 2, 1]" :key="stars" class="flex items-center space-x-4">
                        <span class="text-xs font-bold text-gray-500 w-12 flex items-center justify-end">
                            {{ stars }} <span class="text-amber-400 ml-1">★</span>
                        </span>
                        
                        <!-- Progress Bar -->
                        <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden shadow-inner">
                            <div
                                class="h-full rounded-full transition-all duration-500 bg-gradient-to-r from-emerald-500 to-teal-400"
                                :style="{ width: `${distribution[stars]?.percentage || 0}%` }"
                            ></div>
                        </div>
                        
                        <span class="text-xs font-semibold text-gray-600 w-14 text-right">
                            {{ distribution[stars]?.count || 0 }} ({{ distribution[stars]?.percentage || 0 }}%)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Comments List -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-150 pb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Historial de Comentarios
                </h3>

                <div v-if="feedbacks.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        v-for="item in feedbacks"
                        :key="item.id"
                        class="glass-card p-6 flex flex-col justify-between hover:shadow-xl transition-all duration-300 relative overflow-hidden group"
                    >
                        <!-- Top details -->
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-extrabold text-gray-800 leading-tight">{{ item.user?.name }}</h4>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ item.user?.email }}</p>
                                </div>
                                
                                <!-- Rating Badge -->
                                <div class="flex items-center space-x-0.5 bg-amber-50 px-2.5 py-1 rounded-full border border-amber-200">
                                    <span class="text-xs font-black text-amber-700">{{ item.rating }}</span>
                                    <svg class="w-3 h-3 text-amber-500 fill-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192L12 .587z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Comment body -->
                            <p class="text-sm text-gray-600 bg-gray-50/50 p-4 rounded-2xl border border-gray-100 shadow-inner italic leading-relaxed whitespace-pre-line mb-4 min-h-[4rem]">
                                {{ item.comment || 'Sin comentarios adicionales.' }}
                            </p>
                        </div>

                        <!-- Date -->
                        <div class="text-[10px] text-gray-400 text-right mt-auto">
                            Calificado el {{ formatDate(item.created_at) }}
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="glass bg-white/40 border-dashed border-2 border-emerald-200 p-12 text-center rounded-3xl">
                    <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-700">Aún no hay calificaciones</h4>
                    <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">Cuando los usuarios califiquen la aplicación, podrás ver el promedio y leer los comentarios en esta sección.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
