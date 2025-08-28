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
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="mt-2">
                    <div class="text-center">
                        <h1 class="responsive-h1">Bienvenidos a {{ appName }}</h1>
                    </div>
                </div>
                <div class="mt-5">
                    <div class="row justify-content-center">
                        <template v-for="app in userApps" :key="app.id">
                            <div class="col-lg-4 mb-3 justify-content-center align-items-center d-flex">
                                <div class="card" style="max-width: 350px;">
                                    <img src="https://placehold.co/600x400" class="card-img-top" alt="placeholder">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ app.name }}</h5>
                                        <Link :href="route(app.access_route_name)" class="btn btn-primary">Entrar <i class='bx bx-right-arrow-circle'></i></Link>
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