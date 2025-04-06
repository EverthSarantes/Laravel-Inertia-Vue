<script setup>
    import dashboard from '../layouts/dashboard.vue';
    import SubNavbar from '../components/SubNavbar.vue';
    import DeleteModal from '../components/DeleteModal.vue';
    import Table from '../components/Table.vue';
    import ExcelExport from '../components/buttons/ExcelExport.vue';
    import Print from '../components/buttons/Print.vue';
    import searchSelect from '../components/inputs/searchSelect.vue';

    import { usePage, useForm } from '@inertiajs/vue3';
    import { computed } from 'vue';

    const user = computed(() => {
        return usePage().props.user;
    });

    const links = [
        { route: 'users.index', name: 'Usuarios', active: true },
    ];

    const updateForm = useForm({
        name: user.value.name,
        password: null,
    });

    const submitUpdateForm = () => {
        updateForm.put(route('users.update', user.value.id), {
            onSuccess: () => {
                updateForm.reset();
            },
            onError: (errors) => {
                showToast('Error al actualizar el usuario');
            },
        });
    };

    const addModuleForm = useForm({
        user_id: user.value.id,
        module_id: null,
    });

    const submitAddModuleForm = () => {
        addModuleForm.post(route('users.addModule'), {
            onSuccess: () => {
                addModuleForm.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('modal_add_module'));
                modal.hide();

                window.atm_tables.forEach((table) => {
                    table.refresh();
                });
            },
            onError: (errors) => {
                showToast('Error al agregar el módulo');
            },
        });
    };

</script>

<template>
    <dashboard>
        <SubNavbar :links="links" />

        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h4>Editar Usuario</h4>
                    <hr class="dorado">
                    <form class="card p-3 mt-2" method="POST" autocomplete="off" @submit.prevent="submitUpdateForm">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label for="name">Nombre</label>
                                <input type="text" name="name" id="name" class="form-control" v-model="updateForm.name" required>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="password">Contraseña</label>
                                <input type="password" name="password" id="password" class="form-control" v-model="updateForm.password">
                            </div>
                            <div class="col-md-12 mt-3 justify-content-between d-flex">
                                <div class="d-flex gap-1">
                                    <button type="submit" class="btn btn-outline-secondary">Editar</button>
                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal_add_module">Agregar Módulo</button>
                                </div>
                                <div class="d-flex justify-content-end gap-1">
                                    <ExcelExport :filename="user.name" :target="'modules_table'"/>
                                    <Print :view-name="'users.print.show'" :title="user.name" :page-properties="{'pagedjs': true, 'pagecounter': true}" :params="{user_id: user.id}"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-12 mt-4">
                    <h5>Módulos a los que posee acceso el Usuario</h5>
                    <hr>
                    <Table :model="usePage().props.model" :options="['delete']" :id="'modules'" :extraQueryParameter="user.id"/>
                </div>
            </div>
        </div>

        <!-- modal add module -->
        <div class="modal fade" id="modal_add_module" tabindex="-1" aria-labelledby="modal_add_module" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" @submit.prevent="submitAddModuleForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_add_module">Agregar Módulo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                             <searchSelect name="Módulos" input-name="module_id" :model="'Module'" :required="true" select_name="module_id" v-model:select_value="addModuleForm.module_id"/>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <DeleteModal />
    </dashboard>
</template>