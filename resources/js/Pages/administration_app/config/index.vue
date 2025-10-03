<script setup>

    import dashboard from '../../layouts/dashboard.vue';
    import SubNavbar from '../../components/SubNavbar.vue';

    import { usePage, useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';

    
    const links = [
        { route: 'config.index', name: 'Configuraciones', active: true },
    ];

    const configs = ref(usePage().props.configurations);
    console.log(configs.value);

    function openUpdateModal(config) {
        updateConfigForm.id = config.id;
        updateConfigForm.name = config.name;
        updateConfigForm.type = config.type;
        updateConfigForm.value = config.typed_value;

        const updateModal = new bootstrap.Modal(document.getElementById('updateConfigModal'));
        updateModal.show();
    }

    const updateConfigForm = useForm({
        id: '',
        name: '',
        type: '',
        value: '',
    });

    const submitUpdateConfig = () => {
        updateConfigForm.put(route('config.update', updateConfigForm.id), {
            onSuccess: (response) => {
                configs.value = response.props.configurations;
            },
            onFinish: () => {
                const updateModalEl = document.getElementById('updateConfigModal');
                const updateModal = bootstrap.Modal.getInstance(updateModalEl);
                updateModal.hide();
            },
        });
    };
</script>

<template>
    <dashboard :appName="'administration_app'">
        <SubNavbar :links="links" />
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Configuraciones</h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Valor</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="config in configs" :key="config.id">
                                <td>{{ config.name }}</td>
                                <td>{{ config.type == 'boolean' ? (config.typed_value ? 'Sí' : 'No') : config.typed_value }}</td>
                                <td>
                                    <button class="btn btn-warning" @click="openUpdateModal(config)" title="Editar Configuración">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </dashboard>

    <!-- Modal -->
    <div class="modal fade" id="updateConfigModal" tabindex="-1" aria-labelledby="updateConfigLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="updateConfigLabel">Actualizar Configuración</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="updateConfigForm" @submit.prevent="submitUpdateConfig">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" v-model="updateConfigForm.name" disabled>
                        </div>
                        
                        <div class="mb-3" v-if="updateConfigForm.type === 'string'">
                            <label for="value" class="form-label">Valor</label>
                            <input type="text" class="form-control" id="value" v-model="updateConfigForm.value">
                        </div>

                        <div class="mb-3" v-if="updateConfigForm.type === 'integer'">
                            <label for="value" class="form-label">Valor</label>
                            <input type="number" class="form-control" id="value" v-model="updateConfigForm.value">
                        </div>

                        <div class="mb-3" v-if="updateConfigForm.type === 'boolean'">
                            <label for="value" class="form-label">Valor</label>
                            <select class="form-select" id="value" v-model="updateConfigForm.value">
                                <option :value="true">Sí</option>
                                <option :value="false">No</option>
                            </select>
                        </div>

                        <div class="mb-3" v-if="updateConfigForm.type === 'json'">
                            <label for="value" class="form-label">Valor (JSON)</label>
                            <textarea class="form-control" id="value" v-model="updateConfigForm.value" rows="5"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" form="updateConfigForm" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
</template>