<script setup>
    defineProps({
        url: String,
        sm: {
            type: Boolean,
            default: false
        }
    });

    function showDeleteModal(url) {
        document.querySelector('#delete_modal form').action = url;
        let modalInstance = new bootstrap.Modal(document.getElementById('delete_modal'));
        modalInstance.show();
    }
</script>

<template>
    <button aria-label="eliminar"
        class="btn btn-danger"
        @click="showDeleteModal(url)" 
        :class="{'btn-sm': sm}">
        <i class='bx bx-trash'></i>
    </button>
</template>