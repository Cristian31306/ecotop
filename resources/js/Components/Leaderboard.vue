<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const topUsers = ref([]);
const loading = ref(true);

const fetchLeaderboard = async () => {
    try {
        const response = await axios.get(route('api.leaderboard'));
        topUsers.value = response.data;
    } catch (error) {
        console.error("Error fetching leaderboard", error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchLeaderboard();
});
</script>

<template>
    <div class="glass-card">
        <h3 class="text-2xl font-bold text-emerald-800 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            Top 10 Exploradores
        </h3>

        <div v-if="loading" class="text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600 mx-auto"></div>
        </div>

        <div v-else-if="topUsers.length === 0" class="text-center py-8 text-gray-500">
            Aún no hay puntajes registrados. ¡Sé el primero!
        </div>

        <ul v-else class="space-y-4">
            <li v-for="(user, index) in topUsers" :key="user.user_id" 
                class="flex items-center justify-between p-4 rounded-2xl"
                :class="index < 3 ? 'bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100' : 'hover:bg-gray-50'">
                <div class="flex items-center">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full font-bold mr-4"
                         :class="index === 0 ? 'bg-yellow-400 text-yellow-900' : (index === 1 ? 'bg-gray-300 text-gray-800' : (index === 2 ? 'bg-amber-600 text-amber-50' : 'bg-emerald-100 text-emerald-800'))">
                        {{ index + 1 }}
                    </div>
                    <span class="font-medium text-gray-800">{{ user.user.name }}</span>
                </div>
                <div class="font-bold text-emerald-600">
                    {{ user.total_score }} pts
                </div>
            </li>
        </ul>
    </div>
</template>
