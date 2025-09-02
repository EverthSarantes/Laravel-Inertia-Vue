<script setup>
    import { onMounted } from 'vue';

    onMounted(() => {
        window.showActiveTheme(window.getPreferredTheme())

        document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {

            if(toggle.dataset.bsThemeValue === window.getPreferredTheme()) {
                toggle.setAttribute('checked', true)
            }

            toggle.addEventListener('click', () => {
                const theme = toggle.getAttribute('data-bs-theme-value')
                window.setStoredTheme(theme)
                window.setTheme(theme)
                window.showActiveTheme(theme, true)
            })
        });
    });
</script>
<template></template>