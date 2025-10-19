<script setup>

    import dashboard from '../../layouts/dashboard.vue';
    import SubNavbar from '../../components/SubNavbar.vue';

    import { ref, watch } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    
    const links = [
        { route: 'reports.index', name: 'Reportes', active: true },
    ];

    const config = ref(usePage().props.config);
    const selectedConfig = ref({
        mainModel: Object.keys(config.value)[0] || null,
        mainModelFields: [],
        mainModelFilters: [],
        relations: [],
    });

    const selectedRelationNames = ref([]);

    // Observador para limpiar selecciones cuando cambia el modelo principal
    watch(() => selectedConfig.value.mainModel, (newModel, oldModel) => {
        if (newModel !== oldModel) {
            selectedConfig.value.mainModelFields = [];
            selectedConfig.value.mainModelFilters = [];
            selectedRelationNames.value = [];
            selectedConfig.value.relations = [];
        }
    });

    watch(selectedRelationNames, (newNames, oldNames) => {
        // Nombres de relaciones que se acaban de añadir
        const added = newNames.filter(name => !oldNames.includes(name));
        // Nombres de relaciones que se acaban de quitar
        const removed = oldNames.filter(name => !newNames.includes(name));

        // Añadir la estructura del objeto por cada nueva relación seleccionada
        added.forEach(relationName => {
            const relationInfo = config.value[selectedConfig.value.mainModel].relations[relationName];
            if (relationInfo) {
                selectedConfig.value.relations.push({
                    model: relationName, // El nombre de la relación
                    fields: [], // Array para los campos de esta relación
                    filters: [],
                    relations: [], // Array para futuras sub-relaciones
                });
            }
        });

        // Eliminar el objeto de la relación que se deseleccionó
        removed.forEach(relationName => {
            const index = selectedConfig.value.relations.findIndex(r => r.model === relationName);
            if (index > -1) {
                selectedConfig.value.relations.splice(index, 1);
            }
        });
    }, { deep: true });
</script>

<template>
    <dashboard :appName="'administration_app'">
        <SubNavbar :links="links" />
        <div class="container">

            <div class="modal fade" tabindex="-1" id="ReportConfigModal">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Obtener datos del reporte</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">

                                <!-- modelo principal -->
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label for="reportModel" class="form-label"><h4>Modelo de Reporte</h4></label>
                                        <select id="reportModel" class="form-select" v-model="selectedConfig.mainModel">
                                            <option v-for="(modelConfig, modelName) in config" :key="modelName" :value="modelName">
                                                {{ modelConfig.label }}
                                            </option>
                                        </select>
                                    </div>
                                    <hr>
                                </div>

                                <!-- campos del modelo principal -->
                                <div class="col-lg-12">
                                    <div class="mb-3" v-if="selectedConfig.mainModel">
                                        <label for="reportFields" class="form-label"><h4>Campos Disponibles para {{ config[selectedConfig.mainModel].label }}</h4></label>
                                        <div class="row">
                                            <div class="col-lg-4" v-for="(fieldLabel, fieldName) in config[selectedConfig.mainModel].fields" :key="fieldName">
                                                <div class="form-check user-select-none">
                                                    <label :for="`field-${fieldName}`" class="form-check-label">
                                                        {{ fieldLabel }}
                                                    </label>
                                                    <input type="checkbox" :id="`field-${fieldName}`" class="form-check-input" v-model="selectedConfig.mainModelFields" :value="fieldName">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <!-- filtros del modelo principal -->
                                <div class="col-lg-12">
                                    <div class="mb-3" v-if="selectedConfig.mainModel">
                                        <div class="d-flex justify-content-between mb-2">
                                            <label for="mainModelFilters" class="form-label"><h4>Filtros para {{ config[selectedConfig.mainModel].label }}</h4></label>
                                            <button aria-label="añadir filtro" type="button" class="btn btn-sm btn-primary mb-2" 
                                                @click="selectedConfig.mainModelFilters.push({ field: '', operator: 'like', value: '' })">
                                                <i class='bx bx-plus'></i>
                                            </button>
                                        </div>
                                        <div class="row" v-for="(filter, index) in selectedConfig.mainModelFilters" :key="index">
                                            <div class="col-lg-4">
                                                <label class="w-100">
                                                    <span>Campo</span>
                                                    <select class="form-select mb-2" v-model="selectedConfig.mainModelFilters[index].field">
                                                        <option v-for="(fieldLabel, fieldName) in config[selectedConfig.mainModel].fields" :key="fieldName" :value="fieldName">
                                                            {{ fieldLabel }}
                                                        </option>
                                                    </select>
                                                </label>
                                            </div>
                                            <div class="col-lg-3">
                                                <label class="w-100">
                                                    <span>Tipo de Comparación</span>
                                                    <select class="form-select mb-2" v-model="selectedConfig.mainModelFilters[index].operator">
                                                        <option value="like">Similar</option>
                                                        <option value="=">Igual</option>
                                                        <option value="!=">Diferente</option>
                                                        <option value=">">Mayor</option>
                                                        <option value=">=">Mayor o Igual</option>
                                                        <option value="<">Menor</option>
                                                        <option value="<=">Menor o Igual</option>
                                                    </select>
                                                </label>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="w-100">
                                                    <span>Valor</span>
                                                    <input type="text" class="form-control mb-2" v-model="selectedConfig.mainModelFilters[index].value" />
                                                </label>
                                            </div>
                                            <div class="col-lg-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-sm btn-danger mb-2" @click="selectedConfig.mainModelFilters.splice(index, 1)">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <!-- relaciones disponibles del modelo principal -->
                                <div class="col-lg-12">
                                    <h4>Relaciones Disponibles</h4>
                                    <div class="row">
                                        <div class="col-lg-4" v-for="(relationData, relationName) in config[selectedConfig.mainModel].relations" :key="relationName">
                                            <div class="form-check user-select-none">
                                                <label :for="`relation-${relationName}`" class="form-check-label">
                                                    {{ config[relationName.split('.').pop()]?.label ?? relationName.split('.').pop() }}
                                                </label>

                                                <input type="checkbox" :id="`relation-${relationName}`" class="form-check-input" v-model="selectedRelationNames" :value="relationName">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <!-- campos de las relaciones seleccionadas -->
                                <div class="col-lg-12" v-if="selectedConfig.relations.length > 0">
                                    <h4>Relaciones Seleccionadas</h4>
                                    <div class="row">
                                        
                                        <!--campos de la relacción seleccionada-->
                                        <div class="col-lg-12 mb-3" v-for="(relation, index) in selectedConfig.relations" :key="relation.model">
                                            <strong>{{ config[relation.model.split('.').pop()].label }}</strong>
                                            <div class="mt-2">
                                                <label class="form-label">Campos Disponibles para {{ config[relation.model.split('.').pop()].label }}</label>
                                                <div class="row">
                                                    <div class="col-lg-4" v-for="(fieldLabel, fieldName) in config[relation.model.split('.').pop()]?.fields" :key="fieldName">
                                                        <div class="form-check user-select-none">
                                                            <label :for="`relation-field-${relation.model}-${fieldName}`" class="form-check-label">
                                                                {{ fieldLabel }}
                                                            </label>
                                                            <input type="checkbox" :id="`relation-field-${relation.model}-${fieldName}`" class="form-check-input" 
                                                                v-model="selectedConfig.relations[index].fields" 
                                                                :value="fieldName"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- filtros para relación seleccionada -->
                                        <div class="col-lg-12 mb-3" v-for="(relation, index) in selectedConfig.relations" :key="`filters-${relation.model}`">
                                            <div class="d-flex justify-content-between mb-2">
                                                <label class="form-label">Filtros para {{ config[relation.model.split('.').pop()].label }}</label>
                                                <button aria-label="añadir filtro" type="button" class="btn btn-sm btn-primary mb-2" 
                                                    @click="selectedConfig.relations[index].filters.push({ field: '', operator: 'like', value: '' })">
                                                    <i class='bx bx-plus'></i>
                                                </button>
                                            </div>
                                            <div class="row" v-for="(filter, fIndex) in selectedConfig.relations[index].filters" :key="`filter-${relation.model}-${fIndex}`">
                                                <div class="col-lg-4">
                                                    <label class="w-100">
                                                        <span>Campo</span>
                                                        <select class="form-select mb-2" v-model="selectedConfig.relations[index].filters[fIndex].field">
                                                            <option v-for="(fieldLabel, fieldName) in config[relation.model.split('.').pop()]?.fields" :key="fieldName" :value="fieldName">
                                                                {{ fieldLabel }}
                                                            </option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label class="w-100">
                                                        <span>Tipo de Comparación</span>
                                                        <select class="form-select mb-2" v-model="selectedConfig.relations[index].filters[fIndex].operator">
                                                            <option value="like">Similar</option>
                                                            <option value="=">Igual</option>
                                                            <option value="!=">Diferente</option>
                                                            <option value=">">Mayor</option>
                                                            <option value=">=">Mayor o Igual</option>
                                                            <option value="<">Menor</option>
                                                            <option value="<=">Menor o Igual</option>
                                                        </select>
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="w-100">
                                                        <span>Valor</span>
                                                        <input type="text" class="form-control mb-2" v-model="selectedConfig.relations[index].filters[fIndex].value" />
                                                    </label>
                                                </div>
                                                <div class="col-lg-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-sm btn-danger mb-2" @click="selectedConfig.relations[index].filters.splice(fIndex, 1)">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <!-- vista previa de la configuración seleccionada -->
                                <details class="col-lg-12 mt-5">
                                    <summary>Vista Previa de Configuración:</summary>
                                    <pre>{{ selectedConfig }}</pre>
                                </details>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Resultados</h5>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ReportConfigModal" aria-label="Configurar Reporte">
                                <i class='bx bx-cog'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </dashboard>
</template>