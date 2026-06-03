// This script sets up the welcome panel page.
// It displays the application name and a welcome message using data from the Inertia page props.
// The template includes a centered layout with a welcome message and application logo.
<script setup>

    import mainDashboard from './layouts/mainDashboard.vue';
    import { usePage, Link } from '@inertiajs/vue3';
    import { computed } from 'vue';

    const page = usePage();
    const appName = computed(() => page.props.appName);
    const userApps = computed(() => page.props.userApps);

</script>

<template>
    <mainDashboard>
        <div class="container mx-auto px-4 flex flex-col items-center justify-center min-h-[70vh]">
            <div class="w-full">
                <div class="mt-2 text-center w-full">
                    <h1 class="text-3xl md:text-5xl font-bold text-gray-900 dark:text-gray-100">Bienvenidos a {{ appName }}</h1>
                </div>
                
                <div class="mt-12 w-full">
                    <div class="flex flex-wrap justify-center gap-6">
                        <template v-for="app in userApps" :key="app.id">
                            <div class="w-full sm:w-[350px] flex justify-center">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden w-full border border-gray-200 dark:border-gray-700 flex flex-col hover:shadow-lg transition-shadow">
                                    <img :src="app.icon" class="w-full h-48 object-cover border-b border-gray-200 dark:border-gray-700" alt="placeholder">
                                    <div class="p-6 flex flex-col items-center flex-grow justify-between">
                                        <span class="text-xl font-semibold mb-4 text-center text-gray-800 dark:text-white">{{ app.name }}</span>
                                        <Link :href="route().has(app.access_route_name) ? route(app.access_route_name) : '#'" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded text-center transition flex justify-center items-center gap-2" :aria-label="'Ir a ' + app.name">
                                            Entrar <i class='bx bx-right-arrow-circle text-lg'></i>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </mainDashboard>
</template>