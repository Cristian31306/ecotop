<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    ecosystems: Array,
});
</script>

<template>
    <Head title="Administrar Ecosistemas" />

    <AuthenticatedLayout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Administrar Ecosistemas</h2>
                <Link :href="route('admin.ecosystems.create')" class="btn-primary">Crear Ecosistema</Link>
            </div>

            <div class="glass-card overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Día</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="eco in ecosystems" :key="eco.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ eco.day_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ eco.title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="eco.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                    {{ eco.is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <Link :href="route('admin.ecosystems.edit', eco.id)" class="text-emerald-600 hover:text-emerald-900">Editar</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-8 flex flex-col sm:flex-row sm:items-center gap-4">
                <Link :href="route('admin.questions.index')" class="text-emerald-600 hover:underline font-medium">
                    &rarr; Administrar Preguntas
                </Link>
                <span class="hidden sm:inline text-gray-300">|</span>
                <Link :href="route('admin.feedback.index')" class="text-emerald-600 hover:underline font-medium">
                    &rarr; Ver Comentarios y Calificaciones
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
