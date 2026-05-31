<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const user = usePage().props.auth.user;
</script>

<template>
    <div>
        <div class="min-h-screen">
            <nav class="glass sticky top-0 z-50 border-b-0 shadow-sm">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-20">
                        <div class="flex items-center">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo class="block h-10 w-auto fill-current text-emerald-600" />
                                </Link>
                                <span class="ml-3 text-xl font-bold text-emerald-800 tracking-tight">Ecotop</span>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Explorar Ecosistemas
                                </NavLink>
                                <NavLink :href="route('leaderboard')" :active="route().current('leaderboard')">
                                    Podio Global
                                </NavLink>
                                <NavLink v-if="user.role === 'admin'" :href="route('admin.ecosystems.index')" :active="route().current('admin.*')">
                                    Administración
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-emerald-700 bg-emerald-50 hover:bg-emerald-100 focus:outline-none transition ease-in-out duration-150 shadow-sm"
                                            >
                                                {{ $page.props.auth.user.name }}
                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="p-1">
                                            <DropdownLink :href="route('profile.edit')" class="rounded-md hover:bg-emerald-50 hover:text-emerald-700"> Profile </DropdownLink>
                                            <DropdownLink :href="route('logout')" method="post" as="button" class="rounded-md hover:bg-red-50 hover:text-red-700">
                                                Log Out
                                            </DropdownLink>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-emerald-500 hover:text-emerald-600 hover:bg-emerald-100 focus:outline-none focus:bg-emerald-100 focus:text-emerald-600 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden glass border-t border-emerald-100">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('leaderboard')" :active="route().current('leaderboard')">
                            Podio Global
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="user.role === 'admin'" :href="route('admin.ecosystems.index')" :active="route().current('admin.*')">
                            Administración
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-emerald-100">
                        <div class="px-4">
                            <div class="font-medium text-base text-emerald-800">{{ $page.props.auth.user.name }}</div>
                            <div class="font-medium text-sm text-emerald-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" v-if="$slots.header">
                <slot name="header" />
            </header>

            <!-- Page Content -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
                <slot />
            </main>
        </div>
    </div>
</template>
