<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    questions: Array,
});
</script>

<template>
    <Head title="Administrar Preguntas" />

    <AuthenticatedLayout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Administrar Preguntas</h2>
                <Link :href="route('admin.questions.create')" class="btn-primary">Añadir Pregunta</Link>
            </div>

            <div class="glass-card overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ecosistema</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pregunta</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="q in questions" :key="q.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ q.ecosystem?.title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 truncate max-w-md">{{ q.question_text }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <Link :href="route('admin.questions.edit', q.id)" class="text-emerald-600 hover:text-emerald-900">Editar</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                <Link :href="route('admin.ecosystems.index')" class="text-emerald-600 hover:underline font-medium">
                    &larr; Volver a Ecosistemas
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
