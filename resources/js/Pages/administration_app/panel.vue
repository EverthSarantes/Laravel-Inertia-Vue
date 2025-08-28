// This script sets up the welcome panel page.
// It displays the application name and a welcome message using data from the Inertia page props.
// The template includes a centered layout with a welcome message and application logo.
<script setup>

    import dashboard from '../layouts/dashboard.vue';
    import { usePage, Link } from '@inertiajs/vue3';
    import { computed } from 'vue';

    const page = usePage();
    const appName = computed(() => page.props.appName);

</script>

<template>
    <dashboard :appName="'administration_app'">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6">
                    <img class="img-fluid mt-5 mx-auto d-block" width="200" id="responsive-image">
                </div>
            </div>
        </div>
    </dashboard>
</template>