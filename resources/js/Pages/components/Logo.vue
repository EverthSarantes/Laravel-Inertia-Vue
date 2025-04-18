// This script defines a logo component.
// It dynamically updates the logo based on the current theme (light or dark).
// The logo's width and additional classes can be customized via props.
<script setup>

    import { ref, watchEffect } from 'vue';

    const logoLightUrl = '/img/logo_light.svg';
    const logoDarkUrl = '/img/logo_dark.svg';

    const props = defineProps({
        with: {
            type: String,
            default: '120',
        },
        class: {
            type: String,
            default: '',
        },
    });

    const logoWidth = ref(props.with);
    const logoClass = ref(props.class);

    const getPreferredTheme = () => {
        return document.documentElement.getAttribute('data-bs-theme');
    };

    const logoUrl = ref(getPreferredTheme() === 'dark' ? logoDarkUrl : logoLightUrl);

    const updateLogoUrl = () => {
        const theme = getPreferredTheme();
        logoUrl.value = theme === 'dark' ? logoDarkUrl : logoLightUrl;
    };

    const observer = new MutationObserver(() => {
        updateLogoUrl();
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-bs-theme'],
    });

    watchEffect(() => {
        updateLogoUrl();
    });
</script>

<template>
    <img id="logo" alt="Logo" :width="logoWidth" :src="logoUrl" :class="logoClass" class="img-fluid"/>
</template>