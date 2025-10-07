<script setup>
    import { onMounted, onUnmounted, ref, watch } from 'vue';
    import { Link } from '@inertiajs/vue3';

    defineProps({
        userName: String,
    });

    let config = ref({
        theme: window.localStorage.getItem('theme') || 'light',
        highlight: window.localStorage.getItem('highlight') === 'true',
        clickAsist: window.localStorage.getItem('clickAsist') === 'true',
        fontSize: window.localStorage.getItem('fontSize') || 'medium',
    });

    function globalUpdateConfig(key, value){
        config.value[key] = value;
        updateConfig();
        emitConfigUpdated({ ...config.value });
    }

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

    onMounted(() => {
        window.globalUpdateConfig = globalUpdateConfig;
    });

    onUnmounted(() => {
        delete window.globalUpdateConfig;
    });
</script>

<template>
    <div class="dropdown" style="z-index: 1;">
        <button type="button" class="btn btn-light" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
            {{ userName }}
        </button>
        <ul class="dropdown-menu" style="width: 250px;">
            <li>
                <Link :href="route('profile.index')" class="dropdown-item-text text-decoration-none"><strong>Perfil</strong></Link>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <span class="dropdown-item-text"><strong>Tema</strong></span>
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
            <li>
                <label class="d-flex justify-content-between align-items-center">
                    <span class="dropdown-item-text">Protanopia</span>
                    <input class="dropdown-item" type="radio" name="theme" id="protanopia" value="protanopia" style="width: 30px;" data-bs-theme-value="protanopia" v-model="config.theme">
                </label>
            </li>
            <li>
                <label class="d-flex justify-content-between align-items-center">
                    <span class="dropdown-item-text">Acromatopsia</span>
                    <input class="dropdown-item" type="radio" name="theme" id="acromatopsia" value="acromatopsia" style="width: 30px;" data-bs-theme-value="acromatopsia" v-model="config.theme">
                </label>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <span class="dropdown-item-text"><strong>Tamaño de Fuente</strong></span>
            </li>
            <li>
                <select class="dropdown-item" v-model="config.fontSize">
                    <option value="small">Pequeño</option>
                    <option value="medium">Mediano</option>
                    <option value="large">Grande</option>
                </select>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <label class="d-flex justify-content-between align-items-center">
                    <span class="dropdown-item-text"><strong>Remarcado de Elementos</strong></span>
                    <input class="dropdown-item" type="checkbox" name="highlight" id="highlight" style="width: 30px;" v-model="config.highlight">
                </label>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <label class="d-flex justify-content-between align-items-center">
                    <span class="dropdown-item-text"><strong>Asistencia de Clics</strong></span>
                    <input class="dropdown-item" type="checkbox" name="clickAsist" id="clickAsist" style="width: 30px;" v-model="config.clickAsist">
                </label>
            </li>
        </ul>
    </div>
</template>