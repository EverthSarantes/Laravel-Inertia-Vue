// This script handles the user editing page.
// It imports necessary components and sets up forms for updating user details and adding modules.
// The template includes a form for editing user details and a table for displaying user modules.
// A modal is used for adding new modules.
<script setup>
    import dashboard from '../layouts/dashboard.vue';
    import SubNavbar from '../components/SubNavbar.vue';
    import DeleteModal from '../components/DeleteModal.vue';
    import Table from '../components/Table.vue';
    import ExcelExport from '../components/buttons/ExcelExport.vue';
    import Print from '../components/buttons/Print.vue';
    import searchSelect from '../components/inputs/searchSelect.vue';
    import DeleteButton from '../components/buttons/DeleteButton.vue';

    import { usePage, useForm, Link } from '@inertiajs/vue3';
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
        actions: [],
    });

    const submitAddModuleForm = () => {
        addModuleForm.post(route('users.addModule'), {
            onSuccess: () => {
                addModuleForm.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('modal_add_module'));
                modal.hide();
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
                                    <button type="submit" class="btn btn-primary">Editar</button>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal_add_module">Agregar Módulo</button>
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
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0" id="modules_table">
                            <thead>
                                <tr class="table-primary">
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="userModule in user.user_module" :key="userModule.id">
                                    <td>{{ userModule.module.name }}</td>
                                    <td>
                                        {{ userModule.actions.map(action => action.action_name).join(', ') }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <Link class="btn btn-primary btn-sm" :href="route(userModule.module.access_route_name)">
                                                <i class='bx bxs-show'></i>
                                            </Link>
                                            <DeleteButton class="btn btn-danger btn-sm" type="button" :url="route('users.deleteModule', {userModule: userModule.id, user: userModule.user_id})">
                                                <i class='bx bxs-trash'></i>
                                            </DeleteButton>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                     </div>
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
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <label class="form-check-label d-flex gap-1">
                                            <input type="checkbox"
                                                name="actions[]"
                                                v-model="addModuleForm.actions"
                                                value="create"
                                                class="form-check checkbox-modules-actions">
                                            <small>Crear</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <label class="form-check-label d-flex gap-1">
                                            <input type="checkbox"
                                                name="actions[]"
                                                v-model="addModuleForm.actions"
                                                value="read"
                                                class="form-check checkbox-modules-actions">
                                            <small>Leer</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <label class="form-check-label d-flex gap-1">
                                            <input type="checkbox"
                                                name="actions[]"
                                                v-model="addModuleForm.actions"
                                                value="update"
                                                class="form-check checkbox-modules-actions">
                                            <small>Actualizar</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <label class="form-check-label d-flex gap-1">
                                            <input type="checkbox"
                                                name="actions[]"
                                                v-model="addModuleForm.actions"
                                                value="delete"
                                                class="form-check checkbox-modules-actions">
                                            <small>Eliminar</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
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