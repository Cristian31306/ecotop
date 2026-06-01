<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    users: Array,
});

const page = usePage();
const isAdmin = computed(() => page.props.auth?.user?.role === 'admin');

const searchQuery = ref('');
const expandedUserId = ref(null);

const toggleUserExpand = (userId) => {
    expandedUserId.value = expandedUserId.value === userId ? null : userId;
};

// Filter users reactively based on search query
const filteredUsers = computed(() => {
    if (!searchQuery.value) return props.users;
    return props.users.filter(u => 
        u.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        u.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

// Top 3 users (1st, 2nd, 3rd)
const topThree = computed(() => {
    if (props.users.length === 0) return [];
    return props.users.slice(0, 3);
});

// Users from 4th place onwards
const remainingUsers = computed(() => {
    if (props.users.length <= 3) return [];
    return props.users.slice(3);
});

// Stats: Total registered users
const totalUsersCount = computed(() => props.users.length);

// Stats: Average score of users who have played (score > 0)
const averageScore = computed(() => {
    const activeUsers = props.users.filter(u => Number(u.total_score) > 0);
    if (activeUsers.length === 0) return 0;
    const total = activeUsers.reduce((sum, u) => sum + Number(u.total_score), 0);
    return Math.round(total / activeUsers.length);
});

// Get initials for avatar placeholder
const getInitials = (name) => {
    if (!name) return 'EX';
    return name
        .split(' ')
        .map(n => n[0])
        .slice(0, 2)
        .join('')
        .toUpperCase();
};
</script>

<template>
    <Head title="Podio Global" />

    <AuthenticatedLayout>
        <div class="py-12 max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Podio Global</h2>
                    <p class="mt-1 text-sm text-gray-500">Mira el ranking general de exploradores y sus puntuaciones acumuladas.</p>
                </div>
                
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <!-- Export button for admins -->
                    <a v-if="isAdmin" href="/admin/export-podium" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-full font-bold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 whitespace-nowrap shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Descargar XLSX
                    </a>

                    <!-- Search input -->
                    <div class="relative w-full md:w-72">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            v-model="searchQuery"
                            placeholder="Buscar explorador..."
                            class="pl-10 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-full shadow-sm bg-white/70 backdrop-blur-sm transition-all"
                        />
                    </div>
                </div>
            </div>

            <!-- Stats Summary Cards -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="glass bg-white/60 p-4 rounded-2xl border border-white/50 shadow-sm flex items-center space-x-4">
                    <div class="p-3 bg-emerald-100 text-emerald-800 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase leading-none">Total Exploradores</p>
                        <p class="text-xl font-black text-gray-800 mt-1">{{ totalUsersCount }}</p>
                    </div>
                </div>

                <div class="glass bg-white/60 p-4 rounded-2xl border border-white/50 shadow-sm flex items-center space-x-4">
                    <div class="p-3 bg-teal-100 text-teal-800 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase leading-none">Promedio Activos</p>
                        <p class="text-xl font-black text-gray-800 mt-1">{{ averageScore }} pts</p>
                    </div>
                </div>
            </div>

            <!-- Visual Podium (Only shown if no search query is active) -->
            <div v-if="!searchQuery && topThree.length > 0" class="mb-12">
                <div class="glass bg-white/40 border border-white/40 p-6 rounded-3xl shadow-md">
                    <h3 class="text-center text-xs font-bold text-emerald-800 uppercase tracking-widest mb-8">Puestos de Honor</h3>
                    
                    <div class="flex items-end justify-center gap-4 lg:gap-8 min-h-[280px] pt-4 px-2">
                        
                        <!-- 2ND PLACE (Left) -->
                        <div v-if="topThree[1]" class="flex flex-col items-center w-28 sm:w-36 text-center transform hover:scale-105 transition-transform duration-300">
                            <!-- User Details -->
                            <div class="mb-2">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-slate-200 border-2 border-slate-300 shadow-md flex items-center justify-center font-bold text-sm sm:text-base text-slate-700 relative">
                                    {{ getInitials(topThree[1].name) }}
                                    <span class="absolute -top-2.5 -right-1 bg-slate-300 text-slate-800 text-[10px] font-black px-1.5 py-0.5 rounded-full border border-white shadow">
                                        2º
                                    </span>
                                </div>
                            </div>
                            <p class="font-bold text-xs sm:text-sm text-gray-800 truncate w-full px-1">{{ topThree[1].name }}</p>
                            <p class="text-xs font-black text-emerald-600 mb-2">{{ topThree[1].total_score }} pts</p>
                            
                            <!-- Pedestal -->
                            <div class="w-full bg-gradient-to-t from-slate-300 to-slate-200 rounded-t-2xl shadow-inner border border-slate-300/50 flex flex-col justify-end py-4 h-24 sm:h-28">
                                <span class="text-2xl sm:text-3xl font-black text-slate-400">🥈</span>
                            </div>
                        </div>

                        <!-- 1ST PLACE (Center - Elevated) -->
                        <div v-if="topThree[0]" class="flex flex-col items-center w-32 sm:w-44 text-center transform hover:scale-105 transition-transform duration-300 -translate-y-4">
                            <!-- Crown or Icon -->
                            <span class="text-2xl mb-1 animate-bounce duration-1000">👑</span>
                            
                            <!-- User Details -->
                            <div class="mb-2">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-amber-50 border-4 border-amber-400 shadow-lg flex items-center justify-center font-extrabold text-base sm:text-lg text-amber-800 relative">
                                    {{ getInitials(topThree[0].name) }}
                                    <span class="absolute -top-2.5 -right-1.5 bg-amber-400 text-white text-[10px] sm:text-xs font-black px-2 py-0.5 rounded-full border border-white shadow">
                                        1º
                                    </span>
                                </div>
                            </div>
                            <p class="font-black text-sm sm:text-base text-gray-900 truncate w-full px-1">{{ topThree[0].name }}</p>
                            <p class="text-xs sm:text-sm font-black text-emerald-700 mb-2">{{ topThree[0].total_score }} pts</p>
                            
                            <!-- Pedestal -->
                            <div class="w-full bg-gradient-to-t from-amber-400 to-amber-300 rounded-t-2xl shadow-md border border-amber-400/50 flex flex-col justify-end py-4 h-32 sm:h-36">
                                <span class="text-3xl sm:text-4xl font-black text-amber-700">🥇</span>
                            </div>
                        </div>

                        <!-- 3RD PLACE (Right) -->
                        <div v-if="topThree[2]" class="flex flex-col items-center w-28 sm:w-36 text-center transform hover:scale-105 transition-transform duration-300">
                            <!-- User Details -->
                            <div class="mb-2">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-orange-50 border-2 border-orange-300 shadow-md flex items-center justify-center font-bold text-sm sm:text-base text-orange-700 relative">
                                    {{ getInitials(topThree[2].name) }}
                                    <span class="absolute -top-2.5 -right-1 bg-orange-300 text-orange-950 text-[10px] font-black px-1.5 py-0.5 rounded-full border border-white shadow">
                                        3º
                                    </span>
                                </div>
                            </div>
                            <p class="font-bold text-xs sm:text-sm text-gray-800 truncate w-full px-1">{{ topThree[2].name }}</p>
                            <p class="text-xs font-black text-emerald-600 mb-2">{{ topThree[2].total_score }} pts</p>
                            
                            <!-- Pedestal -->
                            <div class="w-full bg-gradient-to-t from-orange-300 to-orange-200 rounded-t-2xl shadow-inner border border-orange-300/50 flex flex-col justify-end py-4 h-16 sm:h-20">
                                <span class="text-xl sm:text-2xl font-black text-orange-500">🥉</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List of Users (Filtered or 4th place+) -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider px-2">
                    {{ searchQuery ? 'Resultados de búsqueda' : 'Tabla de Clasificación' }}
                </h3>

                <!-- If searching, show all matched users. Else show remaining (4+) -->
                <div class="space-y-3">
                    <div 
                        v-for="(u, index) in (searchQuery ? filteredUsers : remainingUsers)" 
                        :key="u.id"
                        class="glass bg-white/70 border border-white/50 rounded-2xl shadow-sm hover:shadow-md transition-all overflow-hidden"
                    >
                        <!-- Row Header -->
                        <div 
                            @click="toggleUserExpand(u.id)"
                            class="p-4 flex items-center justify-between cursor-pointer select-none hover:bg-emerald-50/30 transition-colors"
                        >
                            <div class="flex items-center space-x-4">
                                <!-- Rank indicator -->
                                <span class="w-8 text-center font-black text-sm text-gray-400">
                                    #{{ searchQuery ? props.users.findIndex(user => user.id === u.id) + 1 : index + 4 }}
                                </span>
                                
                                <!-- User initials avatar -->
                                <div class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center font-bold text-xs text-emerald-700">
                                    {{ getInitials(u.name) }}
                                </div>
                                
                                <div>
                                    <p class="font-bold text-sm text-gray-800">{{ u.name }}</p>
                                    <p class="text-xs text-gray-400">{{ u.email }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <span class="bg-emerald-100/80 text-emerald-800 text-xs font-black px-3 py-1 rounded-full shadow-inner border border-emerald-200/50">
                                    {{ u.total_score }} pts
                                </span>
                                
                                <!-- Expand/Collapse Icon -->
                                <svg 
                                    class="w-5 h-5 text-gray-400 transform transition-transform duration-200" 
                                    :class="{'rotate-180 text-emerald-600': expandedUserId === u.id}"
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Expanded details (Accordion) -->
                        <div 
                            v-show="expandedUserId === u.id" 
                            class="bg-emerald-50/30 border-t border-emerald-100/50 p-4 transition-all duration-300"
                        >
                            <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-3">Detalle por Día/Ecosistema</h4>
                            
                            <div v-if="u.scores && u.scores.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <div 
                                    v-for="s in u.scores" 
                                    :key="s.id"
                                    class="bg-white/80 p-3 rounded-xl border border-emerald-100 flex justify-between items-center text-xs"
                                >
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-emerald-100 text-emerald-800 font-bold px-1.5 py-0.5 rounded text-[10px]">
                                            D{{ s.ecosystem?.day_number }}
                                        </span>
                                        <span class="font-medium text-gray-700 truncate max-w-[150px]">{{ s.ecosystem?.title }}</span>
                                    </div>
                                    <span class="font-black text-emerald-600">{{ s.score }} pts</span>
                                </div>
                            </div>
                            
                            <div v-else class="text-center py-4 text-xs text-gray-400 italic">
                                Este explorador aún no ha completado evaluaciones de ecosistemas.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Empty State (No users found/registered) -->
                    <div v-if="(searchQuery ? filteredUsers : remainingUsers).length === 0" class="glass bg-white/40 p-8 text-center rounded-2xl border border-white/50">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-gray-500 italic">No se encontraron exploradores.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
