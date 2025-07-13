// This script handles the user templates listing page.
// It imports components for displaying a table of users and a form for adding new users templates.
<script setup>

    import dashboard from '../layouts/dashboard.vue';
    import SubNavbar from '../components/SubNavbar.vue';
    import DeleteModal from '../components/DeleteModal.vue';
    import Table from '../components/Table.vue';
    import Form from '../components/Form.vue';
    import ExcelExport from '../components/buttons/ExcelExport.vue';

    import { usePage } from '@inertiajs/vue3';
    
    const links = [
        { route: 'users.index', name: 'Usuarios', active: false },
        { route: 'users.templates.index', name: 'Plantillas de Usuarios', active: true },
    ];
</script>

<template>
    <dashboard>
        <SubNavbar :links="links" />
        <div class="container">
            <div class="modal fade" tabindex="-1" id="AddUserModal">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Crear Plantilla Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <Form :route="route('users.templates.store')" :method="'POST'" :model="usePage().props.model" :isModal="true"></Form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Plantillas de Usuarios</h5>
                        <div class="d-flex gap-1">
                            <ExcelExport :filename="'Usuarios'" :target="'users_table'"/>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AddUserModal">
                                <i class='bx bx-plus'></i>
                            </button>
                        </div>
                    </div>
                    <Table :model="usePage().props.model" :options="['delete']" :id="'users'"/>
                </div>
            </div>
        </div>
        <DeleteModal />
    </dashboard>
</template>