<script setup>
    import { ref, watch } from 'vue';

    defineProps({
        userName: String,
    });

    let config = ref({
        theme: window.localStorage.getItem('theme') || 'light',
        highlight: window.localStorage.getItem('highlight') === 'true'
    });

    function updateConfig() {
        Object.entries(config.value).forEach(([key, value]) => {
            window.localStorage.setItem(key, value);
        });
    }

    function emitConfigUpdated(newConfig) {
        window.dispatchEvent(new CustomEvent('config-updated'));
    }

    watch(config, (newVal) => {
        updateConfig();
        emitConfigUpdated({ ...newVal });
    }, { deep: true });
</script>

<template>
    <div class="dropdown">
            <button type="button" class="btn btn-light" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ userName }}
            </button>
            <ul class="dropdown-menu" style="width: 250px;">
                <li>
                    <span class="dropdown-item-text">Tema</span>
                </li>
                <li>
                    <label class="d-flex justify-content-between align-items-center">
                        <span class="dropdown-item-text">Claro</span>
                        <input class="dropdown-item" type="radio" name="theme" id="light" value="light" style="width: 30px;" data-bs-theme-value="light" v-model="config.theme">
                    </label>
                </li>
                <li>
                    <label class="d-flex justify-content-between align-items-center">
                        <span class="dropdown-item-text">Claro Alto Contraste</span>
                        <input class="dropdown-item" type="radio" name="theme" id="light-hc" value="light-hc" style="width: 30px;" data-bs-theme-value="light-hc" v-model="config.theme">
                    </label>
                </li>
                <li>
                    <label class="d-flex justify-content-between align-items-center">
                        <span class="dropdown-item-text">Oscuro</span>
                        <input class="dropdown-item" type="radio" name="theme" id="dark" value="dark" style="width: 30px;" data-bs-theme-value="dark" v-model="config.theme">
                    </label>
                </li>
                <li>
                    <label class="d-flex justify-content-between align-items-center">
                        <span class="dropdown-item-text">Oscuro Alto Contraste</span>
                        <input class="dropdown-item" type="radio" name="theme" id="dark-hc" value="dark-hc" style="width: 30px;" data-bs-theme-value="dark-hc" v-model="config.theme">
                    </label>
                </li>
                <li>
                    <label class="d-flex justify-content-between align-items-center">
                        <span class="dropdown-item-text">Deuteranopia</span>
                        <input class="dropdown-item" type="radio" name="theme" id="deuteranopia" value="deuteranopia" style="width: 30px;" data-bs-theme-value="deuteranopia" v-model="config.theme">
                    </label>
                </li>
                <li>
                    <label class="d-flex justify-content-between align-items-center">
                        <span class="dropdown-item-text">Tritanopia</span>
                        <input class="dropdown-item" type="radio" name="theme" id="tritanopia" value="tritanopia" style="width: 30px;" data-bs-theme-value="tritanopia" v-model="config.theme">
                    </label>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <label class="d-flex justify-content-between align-items-center">
                        <span class="dropdown-item-text">Remarcado de Elementos</span>
                        <input class="dropdown-item" type="checkbox" name="highlight" id="highlight" style="width: 30px;" v-model="config.highlight">
                    </label>
                </li>
            </ul>
        </div>
</template>