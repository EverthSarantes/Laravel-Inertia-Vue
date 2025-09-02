<script setup>
    import { onMounted, onUnmounted } from 'vue';

    let mouseX = 0;
    let mouseY = 0;
    const selectors = ['a', 'button', '.btn', 'input', 'select', 'label'];
    let lastActive = null;

    function getDistanceToMouse(el) {
        const rect = el.getBoundingClientRect();
        const elX = rect.left + rect.width / 2;
        const elY = rect.top + rect.height / 2;
        return Math.sqrt((elX - mouseX) ** 2 + (elY - mouseY) ** 2);
    }

    function highlightClosest() {
        const candidates = Array.from(document.querySelectorAll(selectors.join(',')));
        if (candidates.length === 0) return;

        candidates.forEach(el => el.classList.remove('premonish-active'));

        let closest = candidates[0];
        let minDist = getDistanceToMouse(closest);
        for (let i = 1; i < candidates.length; i++) {
            const dist = getDistanceToMouse(candidates[i]);
            if (dist < minDist) {
                minDist = dist;
                closest = candidates[i];
            }
        }
        closest.classList.add('premonish-active');
        lastActive = closest;
    }

    function onMouseMove(e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
        highlightClosest();
    }

    onMounted(() => {
        document.addEventListener('mousemove', onMouseMove);
    });

    onUnmounted(() => {
        document.removeEventListener('mousemove', onMouseMove);
        if (lastActive) lastActive.classList.remove('premonish-active');
    });
</script>
<template></template>
<style>
    .premonish-active {
        outline: 4px solid blue;
    }
</style>