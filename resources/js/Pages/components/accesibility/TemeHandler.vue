<script setup>
    import { onMounted } from 'vue';

    onMounted(() => {
        window.showActiveTheme(window.getPreferredTheme());

        window.addEventListener('config-updated', (event) => {
            const theme = window.localStorage.getItem('theme') || 'light';
            window.setTheme(theme);
            window.showActiveTheme(theme, true);
        });
    });
</script>
<template></template>