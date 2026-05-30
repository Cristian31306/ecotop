<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    question: Object,
    ecosystems: Array,
});

const form = useForm({
    _method: 'PUT',
    ecosystem_id: props.question.ecosystem_id,
    question_text: props.question.question_text,
    options: props.question.options || ['', '', '', ''],
    correct_option_index: props.question.correct_option_index,
    image_file: null,
});

const imagePreview = ref(props.question.image_url || null);

// Compresión mágica de imágenes
const compressImage = (file) => {
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (event) => {
            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                const MAX_WIDTH = 1200;
                if (width > MAX_WIDTH) { height = Math.round((height * MAX_WIDTH) / width); width = MAX_WIDTH; }
                canvas.width = width; canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                canvas.toBlob((blob) => {
                    const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + "_opt.jpg", { type: 'image/jpeg', lastModified: Date.now() });
                    resolve(newFile);
                }, 'image/jpeg', 0.7);
            };
        };
    });
};

const handleFileUpload = async (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image_file = await compressImage(file);
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    // Cuando hay archivos de por medio en un PUT, Inertia recomienda mandar como POST simulando PUT
    form.post(route('admin.questions.update', props.question.id));
};
</script>

<template>
    <Head title="Editar Pregunta" />

    <AuthenticatedLayout>
        <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Editar Pregunta</h2>
                <Link :href="route('admin.questions.index')" class="text-emerald-600 hover:text-emerald-800 font-medium">
                    &larr; Volver
                </Link>
            </div>

            <div class="glass-card p-8">
                <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ecosistema Asociado</label>
                        <select v-model="form.ecosystem_id" required class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                            <option disabled value="">Selecciona un ecosistema...</option>
                            <option v-for="eco in ecosystems" :key="eco.id" :value="eco.id">{{ eco.title }} (Día {{ eco.day_number }})</option>
                        </select>
                        <div v-if="form.errors.ecosystem_id" class="text-red-500 text-sm mt-1">{{ form.errors.ecosystem_id }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pregunta</label>
                        <input type="text" v-model="form.question_text" required
                            class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                        <div v-if="form.errors.question_text" class="text-red-500 text-sm mt-1">{{ form.errors.question_text }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subir o Reemplazar Imagen</label>
                        <input type="file" @change="handleFileUpload" accept="image/*"
                            class="w-full text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-300 rounded-md p-2">
                        <div v-if="form.errors.image_file" class="text-red-500 text-sm mt-1">{{ form.errors.image_file }}</div>
                        <p class="text-xs text-gray-500 mt-1">Sube una imagen desde tu computadora (.jpg, .png). Máx: 2MB.</p>
                        
                        <div v-if="imagePreview" class="mt-4">
                            <p class="text-xs font-bold text-gray-500 mb-2 uppercase">Vista Previa:</p>
                            <img :src="imagePreview" alt="Preview" class="h-32 object-contain rounded-lg border border-gray-200 shadow-sm" @error="imagePreview = null">
                        </div>
                    </div>

                    <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-100">
                        <h4 class="font-bold text-emerald-800 mb-4">Opciones de Respuesta</h4>
                        <div class="space-y-4">
                            <div v-for="(option, index) in form.options" :key="index" class="flex items-center">
                                <input type="radio" :value="index" v-model="form.correct_option_index" name="correct_option"
                                    class="mr-4 text-emerald-600 focus:ring-emerald-500 w-5 h-5" title="Marcar como correcta">
                                <input type="text" v-model="form.options[index]" required :placeholder="'Opción ' + (index + 1)"
                                    class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm"
                                    :class="{'border-emerald-500 ring-1 ring-emerald-500': form.correct_option_index === index}">
                            </div>
                        </div>
                        <p class="text-sm text-emerald-600 mt-3 font-medium">Selecciona el círculo izquierdo de la respuesta que es correcta.</p>
                        <div v-if="form.errors.options" class="text-red-500 text-sm mt-1">{{ form.errors.options }}</div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <Link :href="route('admin.questions.destroy', question.id)" method="delete" as="button" class="text-red-600 hover:text-red-800 font-medium">
                            Eliminar Pregunta
                        </Link>
                        <button type="submit" :disabled="form.processing" class="btn-primary" :class="{'opacity-50': form.processing}">
                            {{ form.processing ? 'Actualizando...' : 'Actualizar Pregunta' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
