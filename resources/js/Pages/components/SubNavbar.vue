// This script defines a sub-navigation bar component.
// It displays a list of links, highlighting the active link.
// The links are passed as props and rendered dynamically.
<script setup>
    import { Link } from '@inertiajs/vue3';

    defineProps({
        links: Array
    });
</script>

<template>
    <div class="container p-0">
        <ul class="nav nav-tabs flex-row justify-content-end mb-4">
            <li v-for="(link, index) in links" :key="index" class="nav-item">
                <Link class="nav-link" :class="{ active: link.active }" :href="route(link.route)">
                    {{ link.name }}
                </Link>
            </li>
        </ul>
    </div>
</template>