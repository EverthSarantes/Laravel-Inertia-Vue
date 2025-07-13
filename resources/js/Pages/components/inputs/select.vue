// This script defines a select input component.
// It renders a dropdown menu with options and binds the selected value to a model.
<script setup>
    defineProps({
        field: Object,
    });

    const value = defineModel('value', { default: null });
</script>

<template>
    <div class="form-group col-md-6 mt-3">
        <label :for="field.id">{{field.label}} <span class="text-danger" v-if="field.required">*</span></label>
        <select :name="field.name" :id="field.id" class="form-select" :required="field.required" :readonly="field.readonly" v-model="value">
            <option value="null" disabled selected>Seleccione una opción</option>
            <option v-for="(value, key) in field.options" :key="key" :value="key">{{ value }}</option>
        </select>
    </div>
</template>