<script setup>
import { ref, watch, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import confetti from 'canvas-confetti';

const props = defineProps({
    isOpen: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(['close']);

const page = usePage();
const existingFeedback = computed(() => page.props.auth.feedback);

const form = useForm({
    rating: 0,
    comment: '',
});

const hoverRating = ref(0);
const showSuccessMessage = ref(false);

// Sincronizar formulario con datos existentes si el modal se abre para editar
watch(() => props.isOpen, (newVal) => {
    if (newVal) {
        if (existingFeedback.value) {
            form.rating = existingFeedback.value.rating;
            form.comment = existingFeedback.value.comment || '';
        } else {
            form.rating = 0;
            form.comment = '';
        }
        showSuccessMessage.value = false;
    }
});

// Mensajes y emojis dinámicos según la calificación
const feedbackMetadata = {
    1: { emoji: '😢', text: '¡Oh, no! Cuéntanos qué falló o cómo mejorar.' },
    2: { emoji: '🙁', text: 'Lamentamos oír eso. ¿Qué podemos hacer mejor?' },
    3: { emoji: '😐', text: '¡Gracias! ¿Cómo podríamos ser excelentes?' },
    4: { emoji: '🙂', text: '¡Genial! ¿Qué fue lo que más te gustó?' },
    5: { emoji: '🤩', text: '¡Increíble! Nos alegra mucho tu apoyo.' },
};

const currentMetadata = computed(() => {
    const rating = hoverRating.value || form.rating;
    return feedbackMetadata[rating] || { emoji: '⭐', text: 'Califica tu experiencia con Ecotop' };
});

const setRating = (r) => {
    form.rating = r;
};

const handleHover = (r) => {
    hoverRating.value = r;
};

const clearHover = () => {
    hoverRating.value = 0;
};

const triggerConfetti = () => {
    const duration = 2 * 1000;
    const end = Date.now() + duration;

    (function frame() {
        confetti({
            particleCount: 3,
            angle: 60,
            spread: 55,
            origin: { x: 0 },
            colors: ['#059669', '#10b981', '#34d399', '#6ee7b7']
        });
        confetti({
            particleCount: 3,
            angle: 120,
            spread: 55,
            origin: { x: 1 },
            colors: ['#059669', '#10b981', '#34d399', '#6ee7b7']
        });

        if (Date.now() < end) {
            requestAnimationFrame(frame);
        }
    }());
};

const submitFeedback = () => {
    if (form.rating === 0) return;

    form.post(route('feedback.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showSuccessMessage.value = true;
            triggerConfetti();
            
            // Cerrar el modal después de 2 segundos para apreciar el éxito
            setTimeout(() => {
                emit('close');
            }, 2200);
        },
    });
};

const dismissModal = () => {
    // Almacenar el timestamp de descarte en localStorage
    localStorage.setItem('feedback_dismissed_at', Date.now().toString());
    emit('close');
};
</script>

<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-all duration-300"
    >
        <!-- Card Modal con Glassmorphism -->
        <div
            class="relative w-full max-w-md p-6 lg:p-8 text-center bg-white/90 border border-white/50 shadow-2xl rounded-3xl overflow-hidden transform transition-all scale-100 flex flex-col items-center justify-center"
        >
            <!-- Decorative colored blurred spot -->
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-300/40 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-teal-300/30 rounded-full blur-2xl pointer-events-none"></div>

            <!-- Botón Cerrar en la parte superior derecha -->
            <button
                @click="dismissModal"
                class="absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors"
                aria-label="Cerrar"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Estado de Éxito -->
            <div v-if="showSuccessMessage" class="py-8 space-y-4 animate-bounce-short">
                <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-4xl shadow-md">
                    🎉
                </div>
                <h3 class="text-2xl font-bold text-gray-800">¡Muchas Gracias!</h3>
                <p class="text-emerald-800 text-sm font-medium px-4">
                    Tu calificación y comentarios han sido recibidos. Nos ayudan muchísimo a mejorar la Expedición Ecotop.
                </p>
            </div>

            <!-- Formulario de Calificación -->
            <div v-else class="w-full space-y-6">
                <!-- Encabezado -->
                <div>
                    <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full uppercase tracking-wider mb-2">
                        Tu Opinión Importa
                    </span>
                    <h3 class="text-2xl font-extrabold text-gray-800 tracking-tight">
                        {{ existingFeedback ? 'Editar Opinión' : '¿Qué tal tu experiencia?' }}
                    </h3>
                </div>

                <!-- Display del Emoji y Texto Dinámico -->
                <div class="h-20 flex flex-col items-center justify-center p-3 rounded-2xl bg-emerald-50/50 border border-emerald-100/50 backdrop-blur-sm">
                    <span class="text-4xl mb-1 transition-transform duration-300 transform scale-110">
                        {{ currentMetadata.emoji }}
                    </span>
                    <span class="text-xs font-semibold text-emerald-800 leading-tight">
                        {{ currentMetadata.text }}
                    </span>
                </div>

                <!-- Estrellas Interactivas -->
                <div class="flex items-center justify-center space-x-2">
                    <button
                        v-for="star in 5"
                        :key="star"
                        type="button"
                        @click="setRating(star)"
                        @mouseenter="handleHover(star)"
                        @mouseleave="clearHover"
                        class="p-1 focus:outline-none transition-transform duration-150 active:scale-95"
                        :class="{ 'scale-110': hoverRating >= star || (!hoverRating && form.rating >= star) }"
                    >
                        <svg
                            class="w-10 h-10 transition-colors duration-200"
                            :class="(hoverRating >= star || (!hoverRating && form.rating >= star)) ? 'text-amber-400 fill-amber-400 drop-shadow-md' : 'text-gray-300'"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M11.48 3.499c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Comentario Opcional -->
                <form @submit.prevent="submitFeedback" class="space-y-4">
                    <div class="text-left">
                        <label for="comment" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 px-1">
                            Comentarios o sugerencias (Opcional):
                        </label>
                        <textarea
                            id="comment"
                            v-model="form.comment"
                            rows="4"
                            maxlength="500"
                            placeholder="Escribe aquí tu opinión sobre Ecotop..."
                            class="w-full p-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm resize-none bg-white/70 shadow-inner"
                        ></textarea>
                        <div class="flex justify-between items-center text-[10px] text-gray-400 mt-1 px-1">
                            <span>Mínimo 1 estrella requerida para calificar</span>
                            <span>{{ form.comment.length }}/500 caracteres</span>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button
                            type="button"
                            @click="dismissModal"
                            class="w-full sm:order-1 inline-flex items-center justify-center px-5 py-3 border border-gray-200 text-sm font-semibold rounded-full text-gray-700 bg-gray-50 hover:bg-gray-100 transition-colors"
                        >
                            Recordar más tarde
                        </button>
                        <button
                            type="submit"
                            :disabled="form.rating === 0 || form.processing"
                            class="w-full sm:order-2 btn-primary py-3 text-sm flex items-center justify-center font-bold"
                            :class="{ 'opacity-50 cursor-not-allowed': form.rating === 0 || form.processing }"
                        >
                            <span v-if="form.processing">Enviando...</span>
                            <span v-else>{{ existingFeedback ? 'Actualizar Calificación' : 'Enviar Calificación' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animate-bounce-short {
    animation: bounce 1s ease-in-out 1;
}
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-8px);
    }
}
</style>
