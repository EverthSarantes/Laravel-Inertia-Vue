// This script defines a generic input field component.
// It supports various input types and binds the value to a model.
<script setup>
    defineProps({
        field: Object
    });

    const value = defineModel('value');
</script>

<template>
    <div class="form-group col-md-6 mt-3">
        <label :for="field.id">{{field.label}} <span class="text-danger" v-if="field.required">*</span></label>
        <input :type="field.type" :name="field.name" :id="field.id" class="form-control" :required="field.required" :readonly="field.readonly"
        v-model="value">
    </div>
</template>