<script setup>
import { computed } from 'vue';

const props = defineProps({
    value: {
        type: [Number, null],
        required: true,
    },
    locale: {
        type: String,
        default: 'en-US',
    },
    currency: {
        type: String,
        default: 'USD',
    },
});

const formattedValue = computed(() => {
    return new Intl.NumberFormat(props.locale, {
        style: 'currency',
        currency: props.currency,
    }).format(props.value);
});
</script>

<template>
    <span>{{ formattedValue }}</span>
</template>