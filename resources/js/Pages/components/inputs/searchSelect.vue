// This script defines a search and select input component.
// It allows users to search for options dynamically and select one from the results.
// The component fetches data from an API based on the search query and updates the options list.

<script setup>
    import { ref, watch } from 'vue';

    const props = defineProps({
        name: { type: String, required: true },
        inputName: { type: String, required: true },
        model: { type: String, required: true },
        required: { type: Boolean, default: false },
        allowNew: { type: Boolean, default: false },
    });

    const searchQuery = ref('');
    const options = ref([]);
    const isLoading = ref(false);
    const highlightedIndex = ref(-1);
    const skipWatch = ref(false);
    const optionSelected = ref(false);

    const select_value = defineModel('select_value', { default: null });

    function debounce(fn, delay) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn(...args), delay);
        };
    }

    const debouncedSearch = debounce((query) => {
        searchSelect(query);
    }, 400);

    async function searchSelect(query) {
        if (!query) {
            options.value = [];
            return;
        }

        isLoading.value = true;
        const url = `${api_url}select/${props.model}/${query}`;

        try {
            const response = await fetch(url);
            const data = await response.json();
            options.value = data.data;
        } catch (error) {
            options.value = [];
        } finally {
            isLoading.value = false;
        }
    }

    watch(searchQuery, (newQuery) => {
        if (skipWatch.value) {
            optionSelected.value = false;
            skipWatch.value = false;
            return;
        }
        debouncedSearch(newQuery);
    });

    function selectOption(option) {
        skipWatch.value = true;
        searchQuery.value = option.name;
        select_value.value = option.id;
        options.value = [];
        optionSelected.value = true;
    }

    function handleEnter() {
        if (highlightedIndex.value >= 0 && highlightedIndex.value < options.value.length) {
            selectOption(options.value[highlightedIndex.value]);
        }
    }

    function clearOptions() {
        setTimeout(() => {
            options.value.forEach((option, index) => {
                if (option.name == searchQuery.value) {
                    selectOption(option);
                }
            });

            options.value = [];
            isLoading.value = false;

            if(!optionSelected.value && !props.allowNew) {
                searchQuery.value = '';
                select_value.value = null;
            }
        }, 500);
    }
</script>

<template>
    <div class="mb-3 position-relative">
        <label class="form-label">
            {{ name }} <span class="text-danger" v-if="required">*</span>
        </label>
        <input type="text" class="form-control" v-model="searchQuery" autocomplete="off"
            @keydown.enter.prevent="handleEnter"
            @keydown.arrow-down.prevent="highlightedIndex = (highlightedIndex + 1) % options.length"
            @keydown.arrow-up.prevent="highlightedIndex = (highlightedIndex - 1 + options.length) % options.length"
            :placeholder="`Escriba para buscar ${name}...`" :required="required" :name="inputName"
            @focus="searchSelect(searchQuery)"
            @blur="clearOptions"
        />

        <!-- Lista de opciones -->
        <ul class="dropdown-menu w-100" style="max-height: 150px; overflow-y: auto;" :class="{'show': options.length > 0}">
            <li
                v-for="option in options"
                :key="option.id"
                @mousedown.prevent="selectOption(option)"
            >
                <span class="dropdown-item">
                    {{ option.name }}
                </span>
            </li>   
        </ul>
    </div>
</template>