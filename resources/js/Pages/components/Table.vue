// This script defines a dynamic table component.
// It supports search, pagination, and delete functionality.
// The table data is fetched from an API, and the component allows adding or removing search options dynamically.
// It also integrates with global table instances for refreshing and managing data.
<script setup>
    import { ref, onMounted, watch } from 'vue';
    import { Link } from '@inertiajs/vue3';

    const props = defineProps({
        model: Object,
        options: Array,
        id: [String, Number],
        extraQueryParameter: [String, Number, Boolean],
    });

    // Estado reactivo para la tabla
    const tableData = ref([]);
    const pagination = ref(20);
    // Se inicializa con un objeto (puedes inicializar el primer campo según convenga)
    const searchOptions = ref([
        { field: props.model.table_fields_searchable?.[0] || '', search_type: 'like', search: '' }
    ]);

    const prevUrl = ref(null);
    const nextUrl = ref(null);

    // Función para construir la URL de búsqueda a partir de los parámetros actuales
    function getUrl(page = null) {
        const base_url = `${api_url}search/${props.model.model}/`;
        const url = new URL(base_url);

        searchOptions.value.forEach((option, index) => {
            url.searchParams.append(`params[${index}][field]`, option.field);
            url.searchParams.append(`params[${index}][search_type]`, option.search_type);
            url.searchParams.append(`params[${index}][search]`, option.search);
        });
        url.searchParams.append('pagination', pagination.value);
        url.searchParams.append('extraQueryParameter', props.extraQueryParameter);
        if (page) {
            url.searchParams.append('page', page);
        }
        return url;
    }

    // Función para obtener los datos (se asume que makeRequest es global)
    function searchData(url) {
        if (!url) return;
        closeToast();
        // Opcional: podrías establecer un estado de "cargando" si lo requieres
        tableData.value = [];

        makeRequest(url, 'GET', (response) => {
            const links = response.data.links;
            const prev_page = links[0] && links[0].url ? new URL(links[0].url).searchParams.get('page') : null;
            const next_page = links[links.length - 1] && links[links.length - 1].url ? new URL(links[links.length - 1].url).searchParams.get('page') : null;
            prevUrl.value = prev_page ? getUrl(prev_page) : null;
            nextUrl.value = next_page ? getUrl(next_page) : null;
            tableData.value = response.data.data;
        }, () => {
            tableData.value = [props.model.table_fields.map(() => '')];
            showToast('No se encontraron datos');
        });
    }

    onMounted(() => {
        // Llamada inicial a la búsqueda
        searchData(getUrl());
        
        // Exponemos un objeto con los métodos o propiedades que deseamos manipular globalmente.
        const tableInstance = {
            refresh: () => searchData(getUrl()),
            getData: () => tableData.value,
            setData: (data) => { tableData.value = data },
            getPagination: () => pagination.value,
            setPagination: (value) => { pagination.value = value },
            // Puedes agregar otros métodos según lo necesites.
        };

        if (!window.atm_tables) {
            window.atm_tables = [];
        }
        window.atm_tables.push(tableInstance);
    });

    // Cada vez que cambie la paginación o algún campo de búsqueda, se dispara la consulta
    watch([pagination, searchOptions], () => {
        searchData(getUrl());
    }, { deep: true });

    // Funciones para agregar o quitar opciones de búsqueda
    function addNewSearchOption() {
        searchOptions.value.push({ field: '', search_type: 'like', search: '' });
    }

    function removeSearchOption(index) {
        // Permite quitar solo si hay más de una opción (para evitar tener cero)
        if (searchOptions.value.length > 1) {
            searchOptions.value.splice(index, 1);
        }
        searchData(getUrl());
    }

    // Función para manejar la paginación
    function goToPage(url) {
        if (url) {
            searchData(url);
        }
    }

    // Función para mostrar el modal de eliminar
    function showDeleteModal(url) {
        // Asumiendo que existe el modal en el HTML y las funciones globales
        document.querySelector('#delete_modal form').action = url;
        let modalInstance = new bootstrap.Modal(document.getElementById('delete_modal'));
        modalInstance.show();
    }
</script>

<template>
    <div class="card p-3" :id="id">
        <div class="row d-flex justify-content-end">
            <div class="col-md" style="max-width: 460px">
                <div class="input-group mb-3">
                    <label class="input-group-text" :for="'pagination_' + id">Paginación</label>
                    <input type="number" class="form-control" placeholder="Paginación" v-model.number="pagination"
                        :id="'pagination_' + id">
                </div>
            </div>
            <div class="col-md-1">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-secondary" data-bs-toggle="collapse"
                        :data-bs-target="'#search_options_' + id">
                        <i class='bx bx-cog'></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sección de opciones de búsqueda -->
        <div class="collapse" :id="'search_options_' + id">
            <!-- Botón para agregar nueva opción de búsqueda -->
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" @click="addNewSearchOption">
                            <i class='bx bx-plus'></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row justify-content-end mb-2 mt-2">
                <div class="col-md-12" v-for="(option, index) in searchOptions" :key="index">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-lg">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class='bx bx-search'></i></span>
                                        <select class="form-control" v-model="option.field">
                                            <template v-for="(field, i) in props.model.table_fields_searchable" :key="i">
                                                <option :value="field">{{ props.model.table_fields_names[field] }}</option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class='bx bx-search'></i></span>
                                        <select class="form-control" v-model="option.search_type">
                                            <option value="like">Similar</option>
                                            <option value="=">Igual</option>
                                            <option value="!=">Diferente</option>
                                            <option value=">">Mayor</option>
                                            <option value=">=">Mayor o Igual</option>
                                            <option value="<">Menor</option>
                                            <option value="<=">Menor o Igual</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-lg">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class='bx bx-search'></i></span>
                                        <input type="search" class="form-control" placeholder="Buscar" v-model="option.search" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="d-flex justify-content-end button-container">
                                        <button class="btn btn-danger" @click="removeSearchOption(index)">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de datos -->
        <div class="table-responsive">
            <table class="table table-striped table-hover m-0" :id="id + '_table'">
                <thead>
                    <tr>
                        <template v-for="(field, index) in props.model.table_fields" :key="index">
                            <th class="table-primary" data-excel-class="table-primary fw-bold">
                                {{ props.model.table_fields_names[field] }}
                            </th>
                        </template>
                        <th class="table-primary" data-excel-class="table-primary fw-bold">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, rowIndex) in tableData" :key="rowIndex">
                        <td v-for="(field, fieldIndex) in props.model.table_fields" :key="fieldIndex">
                            {{ row[field] }}
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <template v-for="(option, key) in row.options" :key="key">
                                    <Link v-if="option.type === 'link'" v-bind="option.attr" v-html="option.inner"></Link>
                                    <button v-else-if="option.type === 'button'" v-bind="option.attr"
                                        @click="key === 'delete' && showDeleteModal(option.attr['data-url'])"
                                        v-html="option.inner">
                                    </button>
                                    <a v-else-if="option.type === 'normal-link'" v-bind="option.attr"
                                        v-html="option.inner">
                                    </a>
                                </template>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="tableData.length === 0">
                        <td :colspan="props.model.table_fields.length + 1" class="text-center">Cargando...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Botones de paginación -->
        <div class="mt-3">
            <nav aria-label="Page navigation">
                <ul class="pagination user-select-none">
                    <li class="page-item" :class="{ disabled: !prevUrl }">
                        <a class="page-link" href="#" @click.prevent="goToPage(prevUrl)">Anterior</a>
                    </li>
                    <li class="page-item" :class="{ disabled: !nextUrl }">
                        <a class="page-link" href="#" @click.prevent="goToPage(nextUrl)">Siguiente</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
