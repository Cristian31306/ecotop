<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    ecosystem: Object,
});

// Formatear fecha a YYYY-MM-DDTHH:mm para el input datetime-local si existe
const formatDateForInput = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    // Ajuste simple para timezone local
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
    return date.toISOString().slice(0,16);
};

// Preparar el contenido (parsear el JSON array o crear uno por defecto)
const initContent = Array.isArray(props.ecosystem.content) && props.ecosystem.content.length > 0
    ? props.ecosystem.content.map(c => ({ text: c.text, current_image: c.image, image: null }))
    : [{ text: '', current_image: null, image: null }];

const form = useForm({
    _method: 'put',
    day_number: props.ecosystem.day_number,
    title: props.ecosystem.title,
    content: initContent,
    is_active: props.ecosystem.is_active,
    available_from: formatDateForInput(props.ecosystem.available_from),
});

const addCard = () => {
    form.content.push({ text: '', current_image: null, image: null });
};

const removeCard = (index) => {
    if (form.content.length > 1) {
        form.content.splice(index, 1);
    }
};

// Compresión mágica de imágenes en Frontend
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
                
                if (width > MAX_WIDTH) {
                    height = Math.round((height * MAX_WIDTH) / width);
                    width = MAX_WIDTH;
                }
                
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                
                canvas.toBlob((blob) => {
                    const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + "_opt.jpg", {
                        type: 'image/jpeg',
                        lastModified: Date.now()
                    });
                    resolve(newFile);
                }, 'image/jpeg', 0.7);
            };
        };
    });
};

const handleImageUpload = async (e, index) => {
    const file = e.target.files[0];
    if (file) {
        const compressedFile = await compressImage(file);
        form.content[index].image = compressedFile;
    } else {
        form.content[index].image = null;
    }
};

const submit = () => {
    // Para subir archivos en Laravel/Inertia mediante PUT, se debe hacer un POST con _method: 'put'
    form.post(route('admin.ecosystems.update', props.ecosystem.id));
};
</script>

<template>
    <Head title="Editar Ecosistema" />

    <AuthenticatedLayout>
        <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Editar Ecosistema: {{ ecosystem.title }}</h2>
                <Link :href="route('admin.ecosystems.index')" class="text-emerald-600 hover:text-emerald-800 font-medium">
                    &larr; Volver
                </Link>
            </div>

            <div class="glass-card p-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Día Número</label>
                            <input type="number" v-model="form.day_number" required min="1"
                                class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                            <div v-if="form.errors.day_number" class="text-red-500 text-sm mt-1">{{ form.errors.day_number }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Título del Ecosistema</label>
                            <input type="text" v-model="form.title" required
                                class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                            <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                        </div>
                    </div>

                    <!-- Constructor Dinámico de Tarjetas -->
                    <!-- Constructor Dinámico de Tarjetas -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Tarjetas de Lección (Diapositivas)</h3>
                            <p class="text-sm text-gray-500">Agrega las diapositivas teóricas para tu ecosistema. Puedes incluir una foto en cada una.</p>
                        </div>
                        
                        <div class="space-y-6">
                            <div v-for="(card, index) in form.content" :key="index" class="p-6 border border-gray-200 rounded-xl bg-gray-50/50 relative shadow-sm">
                                <div class="absolute top-4 right-4">
                                    <button v-if="form.content.length > 1" type="button" @click="removeCard(index)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-full transition-colors" title="Eliminar Tarjeta">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                                
                                <h4 class="font-bold text-emerald-700 mb-4 flex items-center">
                                    <span class="bg-emerald-200 text-emerald-800 w-6 h-6 flex items-center justify-center rounded-full text-xs mr-2">{{ index + 1 }}</span>
                                    Diapositiva
                                </h4>
                                
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Imagen Superior (Opcional)</label>
                                        
                                        <!-- Preview de imagen actual -->
                                        <div v-if="card.current_image" class="mb-3 relative inline-block">
                                            <img :src="card.current_image" class="h-32 rounded-lg object-cover border border-gray-200 shadow-sm" alt="Preview">
                                        </div>

                                        <input type="file" @change="e => handleImageUpload(e, index)" accept="image/*"
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer border border-gray-300 rounded-md">
                                        <p class="text-xs text-gray-500 mt-1">Sube una nueva foto para reemplazar la actual.</p>
                                        <div v-if="form.errors[`content.${index}.image`]" class="text-red-500 text-sm mt-1">{{ form.errors[`content.${index}.image`] }}</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Contenido HTML / Texto</label>
                                        <textarea v-model="card.text" required rows="6" placeholder="Escribe aquí el contenido de esta diapositiva..."
                                            class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm font-mono text-sm"></textarea>
                                        <div v-if="form.errors[`content.${index}.text`]" class="text-red-500 text-sm mt-1">{{ form.errors[`content.${index}.text`] }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botón Grande de Agregar Tarjeta al final de la lista -->
                            <button type="button" @click="addCard" class="w-full py-4 border-2 border-dashed border-emerald-300 text-emerald-600 rounded-xl font-bold hover:bg-emerald-50 hover:border-emerald-500 transition-colors flex items-center justify-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Agregar Nueva Diapositiva
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha y Hora de Activación (Opcional)</label>
                        <input type="datetime-local" v-model="form.available_from"
                            class="w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">Si dejas esto en blanco, se desbloqueará en cuanto el usuario pase el día anterior.</p>
                        <div v-if="form.errors.available_from" class="text-red-500 text-sm mt-1">{{ form.errors.available_from }}</div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" v-model="form.is_active" id="is_active"
                            class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        <label for="is_active" class="ml-2 text-sm text-gray-600">Ecosistema Activo</label>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <Link :href="route('admin.ecosystems.destroy', ecosystem.id)" method="delete" as="button" class="text-red-600 hover:text-red-800 font-medium">
                            Eliminar Ecosistema
                        </Link>
                        <button type="submit" :disabled="form.processing" class="btn-primary" :class="{'opacity-50': form.processing}">
                            {{ form.processing ? 'Actualizando...' : 'Actualizar Ecosistema' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
