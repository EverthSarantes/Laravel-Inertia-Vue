<script setup>
    defineProps({
        field: Object
    });

    const value = defineModel('value');
</script>

<template>
    <div class="form-group col-md-6 mt-3">
        <label :for="field.id">{{field.label}}</label>
        <input :type="field.type" :name="field.name" :id="field.id" class="form-control" :required="field.required" :readonly="field.readonly"
        v-model="value">
    </div>
</template>