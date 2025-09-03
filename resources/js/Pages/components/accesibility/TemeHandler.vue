<script setup>
    import { onMounted } from 'vue';

    onMounted(() => {
        window.showActiveTheme(window.getPreferredTheme());
        document.documentElement.setAttribute('data-bs-font-size', window.localStorage.getItem('fontSize') || 'medium');
        
        window.addEventListener('config-updated', (event) => {
            const theme = window.localStorage.getItem('theme') || 'light';
            window.setTheme(theme);
            window.showActiveTheme(theme, true);

            document.documentElement.setAttribute('data-bs-font-size', window.localStorage.getItem('fontSize') || 'medium');
        });
    });
</script>
<template></template>