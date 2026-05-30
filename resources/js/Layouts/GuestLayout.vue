<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center justify-center p-4 sm:p-6 bg-gradient-to-br from-teal-50 via-emerald-50 to-cyan-100">
        <div class="mb-6">
            <Link href="/">
                <ApplicationLogo class="h-24 w-24 object-contain shadow-sm rounded-full bg-white p-2" />
            </Link>
        </div>

        <div class="w-full sm:max-w-md glass-card bg-white/70 backdrop-blur-md shadow-2xl p-6 sm:p-10 rounded-3xl">
            <slot />
        </div>
    </div>
</template>
