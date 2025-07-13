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
    import { computed, ref, watch } from 'vue';

    const user = computed(() => {
        return usePage().props.user;
    });

    const filters = usePage().props.available_user_filters;

    const links = [
        { route: 'users.index', name: 'Usuarios', active: true },
    ];

    const updateForm = useForm({
        name: user.value.name,
        password: null,
        can_login: user.value.can_login
    });

    const submitUpdateForm = () => {
        updateForm.put(route('users.update', user.value.id));
    };

    const addModuleForm = useForm({
        user_id: user.value.id,
        module_id: null,
        actions: [],
    });

    const submitAddModuleForm = () => {
        addModuleForm.post(route('users.addModule'), {
            onSuccess: () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modal_add_module'));
                modal.hide();
            },
        });
    };

    function showDeleteModal(url) {
        document.querySelector('#delete_modal form').action = url;
        let modalInstance = new bootstrap.Modal(document.getElementById('delete_modal'));
        modalInstance.show();
    }

    //Filtro según modelo
    const selected_filter = ref(null);
    // Almacena los filtros disponibles para el modelo seleccionado
    const available_filters = ref(null);
    // Almacena el tipo de filtro seleccionado (simple, relations, etc.)
    const selected_filter_type = ref(null);
    
    const searchFilters = () => {
        if (!selected_filter.value) {
            available_filters.value = null;
            return;
        }
        fetch(route('api.modelFilters.getAvailableFilterByModel', {model: selected_filter.value}), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
        })
        .then(async response => {
            if (!response.ok) throw new Error('No se encontraron filtros disponibles para el modelo seleccionado.');
            const data = await response.json();
            available_filters.value = data.data;
        })
        .catch(() => {
            showToast('No se encontraron filtros disponibles para el modelo seleccionado.');
        });
    };

    watch(selected_filter, (newFilter) => {
        searchFilters();
    });

    // Almacena los datos del campo seleccionado para el filtro simple
    const selected_filter_field_data = ref(null);

    function setSelectedFilterFieldData() {
        let selected_field = document.getElementById('filter_field').value;
        if (selected_filter_type.value === 'simple'){
            let data = available_filters.value.simple.fields[selected_field];
            if (data) {
                selected_filter_field_data.value = data;
            } else {
                selected_filter_field_data.value = null;
            }
        }
    }

    // Almacena el valor de la relación seleccionada para el filtro de relaciones
    const selected_relation = ref(null);
    // Almacena los datos del campo seleccionado para el filtro de relaciones
    const selected_relation_field_data = ref(null);
    // Almacena el valor de la relación seleccionada
    const selected_relation_value = ref(null);

    function setSelectedRelation() {
        let selected_relation_value = document.getElementById('filter_relation').value;
        if (available_filters.value && available_filters.value.relations) {
            let relation = available_filters.value.relations.relations[selected_relation_value];
            if (relation) {
                selected_relation.value = relation;
            } else {
                selected_relation.value = null;
            }
        }
    }

    function setSelectedRelationFieldData() {
        let selected_field = document.getElementById('filter_field').value;
        if (selected_relation.value && selected_relation.value.fields) {
            let data = selected_relation.value.fields[selected_field];
            if (data) {
                selected_relation_field_data.value = data;
            } else {
                selected_relation_field_data.value = null;
            }
        }
    }

    // Almacena el valor de la función seleccionada para el filtro de funciones
    const selected_function = ref(null);

    function setSelectedFunction() {
        let selected_function_value = document.getElementById('filter_relation').value;
        if (available_filters.value && available_filters.value.functions) {
            let functionData = available_filters.value.functions.functions[selected_function_value];
            if (functionData) {
                selected_function.value = functionData;
            } else {
                selected_function.value = null;
            }
            console.log(selected_function.value);
        }
    }

    const addUserModelFilterForm = useForm({
        model: null,
        comparison_type: null,
        field: null,
        operator: null,
        value: null,
        relation: null,
        extra: null,
    });

    const submitAddUserModelFilterForm = () => {
        if(selected_filter_type.value === 'simple') {
            addUserModelFilterForm.comparison_type = selected_filter_type.value;
            addUserModelFilterForm.model = selected_filter.value;
            addUserModelFilterForm.field = document.getElementById('filter_field').value || null;
            addUserModelFilterForm.operator = document.getElementById('filter_operator').value || null;
            addUserModelFilterForm.value = document.getElementById('filter_value').value || null;
        }
        if(selected_filter_type.value === 'relations') {
            addUserModelFilterForm.comparison_type = selected_filter_type.value;
            addUserModelFilterForm.model = selected_filter.value;
            addUserModelFilterForm.field = document.getElementById('filter_field').value || null;
            addUserModelFilterForm.operator = document.getElementById('filter_operator').value || null;
            addUserModelFilterForm.value = selected_relation_value.value || null;
            addUserModelFilterForm.relation = selected_relation.value.relation_name || null;
        }
        if(selected_filter_type.value === 'functions') {
            addUserModelFilterForm.comparison_type = selected_filter_type.value;
            addUserModelFilterForm.model = selected_filter.value;
            addUserModelFilterForm.field = selected_function.value.field.name || null;
            addUserModelFilterForm.operator = selected_function.value.operator || null;
            addUserModelFilterForm.value = document.getElementById('filter_value').value || null;
            addUserModelFilterForm.extra = JSON.stringify({
                method: selected_function.value.method,
            });
        }
        if(selected_filter_type.value === 'user_own') {
            addUserModelFilterForm.comparison_type = selected_filter_type.value;
            addUserModelFilterForm.model = selected_filter.value;
            addUserModelFilterForm.extra = JSON.stringify({
                relation_name: available_filters.value.user_own.relation_name,
                foreign_key: available_filters.value.user_own.foreign_key,
            });
        }

        addUserModelFilterForm.post(route('users.addUserModelFilter', {user: user.value.id}), {
            onSuccess: () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modal_add_filter'));
                modal.hide();
            },
        });
    };
</script>

<template>
    <dashboard>
        <SubNavbar :links="links" />

        <div class="container">
            <div class="col-12">
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
                            <div class="col-md-6">
                                <label for="can_login">Puede Iniciar Sesión</label>
                                <select name="can_login" id="can_login" class="form-select" v-model="updateForm.can_login">
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="col-md-12 mt-3 justify-content-between d-flex">
                                <div class="d-flex gap-1">
                                    <button type="submit" class="btn btn-primary">Editar</button>
                                </div>
                                <div class="d-flex justify-content-end gap-1">
                                    <ExcelExport :filename="user.name" :target="'modules_table'"/>
                                    <Print :view-name="'users.print.show'" :title="user.name" :page-properties="{'pagedjs': true, 'pagecounter': true}" :params="{user_id: user.id}"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="container p-3">
            <ul class="nav nav-tabs flex-row justify-content-end" id="pills-tab" role="tablist" style="border: none;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-modules-tab" data-bs-toggle="pill" data-bs-target="#pills-modules" type="button" role="tab" aria-controls="pills-modules" aria-selected="true">Modulos</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-model-filters-tab" data-bs-toggle="pill" data-bs-target="#pills-model-filters" type="button" role="tab" aria-controls="pills-model-filters" aria-selected="false">Filtros de Información</button>
                </li>
            </ul>
        </div>

        <div class="tab-content container" id="pills-tabContent">
            <div class="tab-pane fade show active text-primary" id="pills-modules" role="tabpanel" aria-labelledby="pills-modules-tab">
                <div class="row">
                    <div class="col-lg-12 mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Módulos a los que posee acceso el Usuario</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_module">
                                <i class='bx bx-plus'></i>
                            </button>
                        </div>
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
                                                <button class="btn btn-danger btn-sm" type="button" @click="showDeleteModal(route('users.deleteModule', {userModule: userModule.id, user: userModule.user_id}))">
                                                    <i class='bx bxs-trash'></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade text-primary" id="pills-model-filters" role="tabpanel" aria-labelledby="pills-model-filters-tab">
                <div class="row">
                    <div class="col-lg-12 mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Filtros de Información del Usuario</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_filter">
                                <i class='bx bx-plus'></i>
                            </button>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover m-0" id="modules_table">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Información Filtrada</th>
                                        <th>Filtro</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="userModelFilter in user.user_model_filters">
                                        <td>{{ userModelFilter.model }}</td>
                                        <td>
                                            <span v-if="userModelFilter.comparison_type === 'simple'">
                                                {{ userModelFilter.field }} {{ userModelFilter.operator }} {{ userModelFilter.value }}
                                            </span>
                                            <span v-if="userModelFilter.comparison_type === 'relations'">
                                                {{ userModelFilter.relation }} {{ userModelFilter.field }} {{ userModelFilter.operator }} {{ userModelFilter.value }}
                                            </span>
                                            <span v-if="userModelFilter.comparison_type === 'functions'">
                                                {{ userModelFilter.field }} {{ userModelFilter.operator }} {{ userModelFilter.value }}
                                            </span>
                                            <span v-if="userModelFilter.comparison_type === 'user_own'">
                                                Pertenece al usuario
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <DeleteButton class="btn btn-danger btn-sm" type="button" :url="route('users.removeUserModelFilter', {userModelFilter: userModelFilter.id, user: user.id})">
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
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- modal add filter -->
        <div class="modal fade" id="modal_add_filter" tabindex="-1" aria-labelledby="modal_add_filter" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" @submit.prevent="submitAddUserModelFilterForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_add_filter">Agregar Filtro</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="model" class="form-label">Seleccione Información a Filtrar</label>
                                    <select name="model" id="model" class="form-select" v-model="selected_filter">
                                        <option :value="null" selected>Seleccione un filtro</option>
                                        <option :value="filter.model" v-for="filter in filters" v-html="filter.label"></option>
                                    </select>
                                </div>
                                <div class="col-md-6" v-if="available_filters">
                                    <label for="filter_type" class="form-label">Tipo de Filtro</label>
                                    <select name="filter_type" id="filter_type" class="form-select" v-model="selected_filter_type">
                                        <option :value="null" selected>Seleccione un filtro</option>
                                        <option :value="filter.type" v-for="filter in available_filters" v-html="filter.label"></option>
                                    </select>
                                </div>
                            </div>

                            <hr v-if="selected_filter_type" class="mt-3">


                            <div class="row" v-if="selected_filter_type == 'simple'">
                                <div class="col-md-4">
                                    <label for="filter_field" class="form-label">Campo del Filtro</label>
                                    <select name="filter_field" id="filter_field" class="form-select" @input="setSelectedFilterFieldData">
                                        <option :value="null" selected>Seleccione un campo</option>
                                        <option :value="fieldKey" v-for="(field, fieldKey) in available_filters.simple.fields" v-html="field.label"></option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="filter_operator" class="form-label">Operador</label>
                                    <select name="filter_operator" id="filter_operator" class="form-select">
                                        <option :value="null" selected>Seleccione un campo</option>
                                        <option :value="operator.value" v-for="operator in available_filters.simple.operators" v-html="operator.label"></option>
                                    </select>
                                </div>
                                <div class="col-md-4" v-if="selected_filter_field_data">
                                    <label for="filter_value" class="form-label">Valor</label>


                                    <select v-if="selected_filter_field_data && selected_filter_field_data.type == 'static_select'" class="form-select" id="filter_value" name="filter_value">
                                        <option :value="null" selected>Seleccione un valor</option>
                                        <option :value="valueKey" v-for="(label, valueKey) in selected_filter_field_data.values" v-html="label"></option>
                                    </select>


                                    <input v-else-if="selected_filter_field_data && selected_filter_field_data.type === 'open'"  type="text" class="form-control" id="filter_value" name="filter_value"/>
                                </div>
                            </div>

                            <div class="row" v-if="selected_filter_type == 'relations'">
                                <div class="col-md-12">
                                    <label for="filter_relation" class="form-label">Relación con</label>
                                    <select name="filter_relation" id="filter_relation" class="form-select" @input="setSelectedRelation">
                                        <option :value="null" selected>Seleccione un campo</option>
                                        <option :value="relationKey" v-for="(relation, relationKey) in available_filters.relations.relations" v-html="relation.label"></option>
                                    </select>
                                </div>
                                <div class="col-md-4" v-if="selected_relation">
                                    <label for="filter_field" class="form-label">Campo del Filtro</label>
                                    <select name="filter_field" id="filter_field" class="form-select" @input="setSelectedRelationFieldData">
                                        <option :value="null" selected>Seleccione un campo</option>
                                        <option :value="fieldKey" v-for="(field, fieldKey) in selected_relation.fields" v-html="field.label"></option>
                                    </select>
                                </div>
                                <div class="col-md-4" v-if="selected_relation">
                                    <label for="filter_operator" class="form-label">Operador</label>
                                    <select name="filter_operator" id="filter_operator" class="form-select">
                                        <option :value="null" selected>Seleccione un campo</option>
                                        <option :value="operator.value" v-for="operator in selected_relation.operators" v-html="operator.label"></option>
                                    </select>
                                </div>
                                <div class="col-md-12" v-if="selected_relation_field_data">
                                    <searchSelect name="Valor" input-name="filter_value" :model="selected_relation_field_data.model" :required="true" select_name="filter_value" v-model:select_value="selected_relation_value"/>
                                </div>
                            </div>

                            <div class="row" v-if="selected_filter_type == 'functions'">
                                <div class="col-md-12">
                                    <label for="filter_relation" class="form-label">Función</label>
                                    <select name="filter_relation" id="filter_relation" class="form-select" @input="setSelectedFunction">
                                        <option :value="null" selected>Seleccione un campo</option>
                                        <option :value="fncKey" v-for="(fnc, fncKey) in available_filters.functions.functions" v-html="fnc.label"></option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-3" v-if="selected_function">
                                    <label for="filter_field" class="form-label" v-html="selected_function.field.label"></label>
                                    <input class="form-control" id="filter_value" name="filter_value"  v-bind="selected_function.field.attrs"/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <DeleteModal />
    </dashboard>
</template>