<script setup>
import { onBeforeUnmount, onMounted } from 'vue';

const STORAGE_PREFIX = 'persistent-tabs';
const listeners = [];

const getTabValue = (button) => button.id || button.getAttribute('data-bs-target') || '';

const getStorageKey = (navId) => {
    return `${STORAGE_PREFIX}:${window.location.pathname}:${navId}`;
};

const restoreTab = (nav, buttons) => {
    const navId = nav.id;
    if (!navId || !buttons.length) {
        return;
    }

    const savedTab = localStorage.getItem(getStorageKey(navId));
    let tabButton;

    if (savedTab) {
        tabButton = buttons.find((button) => getTabValue(button) === savedTab);
    }

    if (!tabButton && buttons.length) {
        tabButton = buttons[0];
    }

    if (!tabButton || !window.bootstrap?.Tab) {
        return;
    }

    window.bootstrap.Tab.getOrCreateInstance(tabButton).show();
};

const bindTabs = () => {
    const navs = document.querySelectorAll('.nav[id]');

    navs.forEach((nav) => {
        const buttons = Array.from(nav.querySelectorAll('[data-bs-toggle="tab"], [data-bs-toggle="pill"]'));
        if (!buttons.length) {
            return;
        }

        restoreTab(nav, buttons);

        buttons.forEach((button) => {
            const handler = (event) => {
                const currentNav = event.target.closest('.nav[id]');
                if (!currentNav) {
                    return;
                }

                const navId = currentNav.id;
                const tabValue = getTabValue(event.target);

                if (!navId || !tabValue) {
                    return;
                }

                localStorage.setItem(getStorageKey(navId), tabValue);
            };

            button.addEventListener('shown.bs.tab', handler);
            listeners.push({ button, handler });
        });
    });
};

onMounted(() => {
    bindTabs();
});

onBeforeUnmount(() => {
    listeners.forEach(({ button, handler }) => {
        button.removeEventListener('shown.bs.tab', handler);
    });
});
</script>

<template></template>