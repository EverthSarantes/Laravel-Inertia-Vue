// This script handles the user listing page.
// It imports components for displaying a table of users and a form for adding new users.
// The template includes a form for creating users and a table for listing them.
// Additional features include exporting data to Excel and printing user lists.
<script setup>

    import dashboard from '../../layouts/dashboard.vue';
    import SubNavbar from '../../components/SubNavbar.vue';
    import DeleteModal from '../../components/DeleteModal.vue';
    import Table from '../../components/Table.vue';
    import ExcelExport from '../../components/buttons/ExcelExport.vue';

    import { usePage } from '@inertiajs/vue3';
    
    const links = [
        { route: 'config.index', name: 'Configuraciones', active: true },
        { route: 'logs.index', name: 'Logs', active: false },
    ];

    function showDeleteModal() {
        let form = document.getElementById('deleteForm');
        form.action = route('logs.cleanUserLogs');

        const modal = new bootstrap.Modal(document.getElementById('delete_modal'));
        modal.show();
    }
</script>

<template>
    <dashboard :appName="'administration_app'">
        <SubNavbar :links="links" />
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Logs</h5>
                        <div class="d-flex gap-1">
                            <button class="btn btn-danger" @click="showDeleteModal">
                                <i class="bi bi-trash"></i> Limpiar Logs
                            </button>
                            <ExcelExport :filename="'Logs'" :target="'logs_table'"/>
                        </div>
                    </div>
                    <Table :model="usePage().props.model" :options="['delete']" :id="'logs'"/>
                </div>
            </div>
        </div>
        <DeleteModal />
    </dashboard>
</template>