// This script sets up a modal for deleting records.
// It uses Inertia.js for form handling and Bootstrap for modal functionality.
<script setup>
    import { useForm } from '@inertiajs/vue3';

    const props = defineProps({
        callback: {
            type: Function,
            required: false,
            default: null,
        },
    });

    const form_request = useForm({
        _method: 'DELETE',
    });

    const submit = () => {
        let form = document.getElementById('deleteForm');
        let delete_url = form.action;
        
        form_request.delete(delete_url, {
            onSuccess: () => {
                form.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('delete_modal'));
                modal.hide();

                window.atm_tables.forEach((table) => {
                    table.refresh();
                });

                if (props.callback) {
                    props.callback(response);
                }
            },
            onError: () => {
                showToast('Error al eliminar el registro');
                const modal = bootstrap.Modal.getInstance(document.getElementById('delete_modal'));
                modal.hide();
            },
        });
    }

</script>

<template>
    <div>
        <div class="modal fade" id="delete_modal" tabindex="-1" aria-labelledby="delete_modal_label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="delete_modal_label">¿Está Seguro de que desea eliminar este registro?</h1>
                    </div>
                    <form id="deleteForm" action="" method="POST" class="d-none">
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" @click="submit">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>