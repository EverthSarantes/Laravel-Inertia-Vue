// This script handles the user listing page.
// It imports components for displaying a table of users and a form for adding new users.
// The template includes a form for creating users and a table for listing them.
// Additional features include exporting data to Excel and printing user lists.
<script setup>

    import dashboard from '../layouts/dashboard.vue';
    import SubNavbar from '../components/SubNavbar.vue';
    import DeleteModal from '../components/DeleteModal.vue';
    import Table from '../components/Table.vue';
    import Form from '../components/Form.vue';
    import ExcelExport from '../components/buttons/ExcelExport.vue';
    import Print from '../components/buttons/Print.vue';

    import { usePage } from '@inertiajs/vue3';
    import { computed, onMounted, ref, reactive } from 'vue';
    
    const links = [
        { route: 'users.index', name: 'Usuarios', active: true },
    ];

    const form_modules = computed(() => {
        return usePage().props.form_modules;
    });

    const modules = reactive({
        selected_modules: [],
    });

    const form = reactive({
        modules: [],
    });


    function updateSelectedModules() {
        let data = [];
        const moduleCheckboxes = document.querySelectorAll('.checkbox-modules');
        moduleCheckboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                const moduleId = checkbox.value;
                const actions = [];
                const actionCheckboxes = document.querySelectorAll(`.checkbox-modules-actions[module-id="${moduleId}"]`);
                actionCheckboxes.forEach((actionCheckbox) => {
                    if (actionCheckbox.checked) {
                        actions.push(actionCheckbox.value);
                    }
                });
                data.push({ module_id: moduleId, actions: actions });
            }
        });
        form.modules = data;
    }

    const role = ref(1);
    onMounted(() => {
        const roleElement = document.querySelector('select[name="role"]');
        if (roleElement) {
            role.value = roleElement.value;
            roleElement.addEventListener('change', (event) => {
                role.value = event.target.value;
            });
        }

        const moduleCheckboxes = document.querySelectorAll('.checkbox-modules, .checkbox-modules-actions');
        moduleCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('input', () => {
                updateSelectedModules();
            });
        });
    });
</script>

<template>More actions
    <dashboard>
        <SubNavbar :links="links" />
        <div class="container">

            <div class="modal fade" tabindex="-1" id="AddUserModal">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Crear Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <Form :route="route('users.store')" :method="'POST'" :model="usePage().props.model" :form="form" :isModal="true">
                            <template #extraContent>
                                <div class="mt-3 col-md-12 mt-3" id="modules" v-show="role == 1">
                                    <h6>Módulos</h6>
                                    <div class="row user-select-none">
                                        <template v-for="(module, index) in form_modules" :key="index">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <label class="form-check-label d-flex gap-1">
                                                        <input type="checkbox" name="selected_modules[]" 
                                                            :id="'module_' + module.id"
                                                            :value="module.id"
                                                            v-model="modules.selected_modules"
                                                            class="form-check checkbox-modules">

                                                        <small>{{module.name}}</small>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12" v-show="modules.selected_modules.includes(module.id)">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <label class="form-check-label d-flex gap-1">
                                                                <input type="checkbox"
                                                                    value="create"
                                                                    :module-id="module.id"
                                                                    class="form-check checkbox-modules-actions">
                                                                <small>Crear</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <label class="form-check-label d-flex gap-1">
                                                                <input type="checkbox"
                                                                    value="read"
                                                                    :module-id="module.id"
                                                                    class="form-check checkbox-modules-actions">
                                                                <small>Leer</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <label class="form-check-label d-flex gap-1">
                                                                <input type="checkbox"
                                                                    value="update"
                                                                    :module-id="module.id"
                                                                    class="form-check checkbox-modules-actions">
                                                                <small>Actualizar</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <label class="form-check-label d-flex gap-1">
                                                                <input type="checkbox"
                                                                    value="delete"
                                                                    :module-id="module.id"
                                                                    class="form-check checkbox-modules-actions">
                                                                <small>Eliminar</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-1">
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </Form>
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
                        <h5>Usuarios</h5>
                        <div class="d-flex gap-1">
                            <ExcelExport :filename="'Usuarios'" :target="'users_table'"/>
                            <Print :view-name="'users.print.index'" :title="'Usuarios'" :page-properties="{'pagedjs': true, 'pagecounter': true}" :params="{}"/>
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