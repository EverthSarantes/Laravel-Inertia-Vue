<script setup>
    import { ref, watch } from 'vue';

    const props = defineProps({
        message: String,
        color: String
    });

    const reactiveMessage = ref(props.message);
    const reactiveColor = ref(props.color);

    watch(() => props.message, (newVal) => {
        reactiveMessage.value = newVal;
    });

    watch(() => props.color, (newVal) => {
        reactiveColor.value = newVal;
    });

    const remove = () => {
        reactiveMessage.value = null;
        reactiveColor.value = null;
    };
</script>

<template>
    <div style="position: fixed; top: 70px; z-index: 101; cursor: pointer;" id="message-alert" @click="remove()" v-if="reactiveMessage">
        <div class="alert fade show my-0 mx-2 p-2" :class="'alert-'+color || 'primary'" role="alert" style="min-width: 300px;">
            <strong>{{ message }}</strong>
        </div>
    </div>
</template>