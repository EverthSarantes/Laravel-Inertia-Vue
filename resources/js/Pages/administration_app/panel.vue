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
    </dashboard>
</template>