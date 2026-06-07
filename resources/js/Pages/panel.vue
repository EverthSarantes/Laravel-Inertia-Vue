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
        <div class="container mx-auto px-4">
            <div class="flex flex-col justify-center items-center min-h-full">
                
                <div class="mt-6">
                    <div class="text-center">
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white transition-colors">
                            Bienvenidos a {{ appName }}
                        </h1>
                    </div>
                </div>
                
                <div class="mt-12 w-full max-w-6xl">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-center">
                        
                        <template v-for="app in userApps" :key="app.id">
                            <Link 
                                :href="app.access_route_name && route().has(app.access_route_name) ? route(app.access_route_name) : '#'" 
                                class="block group focus:outline-none" 
                                :aria-label="'Ir a ' + app.name"
                            >
                                <div class="h-full bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-transparent hover:bg-white dark:hover:bg-gray-700 hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300 ease-in-out">
                                    <div class="p-10 flex flex-col items-center text-center">
                                        
                                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-600 text-white mb-6 shadow-sm group-hover:bg-green-500 transition-colors">
                                            <i :class="app.icon" class="text-4xl"></i>
                                        </div>
                                        
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 transition-colors">
                                            {{ app.name }}
                                        </h3>
                                        
                                        <span class="inline-flex items-center text-green-600 dark:text-green-400 font-medium group-hover:text-green-700 dark:group-hover:text-green-300 transition-colors">
                                            Entrar <i class='bx bx-right-arrow-alt ml-1 text-xl'></i>
                                        </span>
                                        
                                    </div>
                                </div>
                            </Link>
                        </template>

                    </div>
                </div>
                
            </div>
        </div>
    </mainDashboard>
</template>