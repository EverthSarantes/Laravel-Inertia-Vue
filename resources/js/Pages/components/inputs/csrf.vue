<script setup>
    const csrf = window.csrf_token;
</script>

<template>
    <input type="hidden" name="_token" :value="csrf">
</template>