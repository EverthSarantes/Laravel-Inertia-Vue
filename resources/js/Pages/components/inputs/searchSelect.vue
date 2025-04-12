// This script defines a search and select input component.
// It allows users to search for options dynamically and select one from the results.
// The component fetches data from an API based on the search query and updates the options list.

<script setup>
    import { ref, watch, computed } from 'vue';

    const props = defineProps({
        name: { type: String, required: true },
        inputName: { type: String, required: true },
        model: { type: String, required: true },
        required: { type: Boolean, default: false },
        select_name: { type: String, default: null },
    });

    const searchQuery = ref('');
    const options = ref([]);
    const isLoading = ref(false);

    const selectId = computed(() => `select_${props.name}`);
    const searchId = computed(() => `search_${props.name}`);

    watch(searchQuery, async (newQuery) => {
        if (!newQuery) {
            options.value = [];
            return;
        }

        isLoading.value = true;
        const url = `${api_url}select/${props.model}/${newQuery}`;

        try {
            const response = await fetch(url);
            const data = await response.json();
            options.value = data.data;
        } catch (error) {
            options.value = [];
        } finally {
            isLoading.value = false;
        }
    });

    const select_value = defineModel('select_value', { default: null });
</script>

<template>
    <div class="col">
        <div class="row">
            <h5>Buscar {{ name }}</h5>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="form-label" :for="searchId">Buscar</label>
                <input
                    type="text"
                    class="form-control"
                    :id="searchId"
                    v-model="searchQuery"
                    placeholder="Escriba para buscar..."
                />
            </div>
            <div class="col-md-6">
                <label class="form-label" :for="selectId">Seleccione una Opción</label>
                <select
                    class="form-select"
                    :name="select_name ? select_name : inputName"
                    :id="selectId"
                    :required="required"
                    :disabled="isLoading"
                    v-model="select_value"
                >
                    <option value="null" selected>{{ isLoading ? 'Buscando...' : 'Seleccione una opción' }}</option>
                    <option v-for="option in options" :key="option.id" :value="option.id">
                        {{ option.name }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>