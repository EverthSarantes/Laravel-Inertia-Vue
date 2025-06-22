<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    field: Object,
    locale: {
        type: String,
        default: 'en-US',
    },
    currency: {
        type: String,
        default: 'USD',
    },
});

const value = defineModel('value');

const formattedValue = ref('');

const formatValue = (val) => {
    if (val === null || val === undefined || val === '') return '';
    return new Intl.NumberFormat(props.locale, {
        style: 'currency',
        currency: props.currency
    }).format(val);
};

const parseValue = (val) => {
    if (val === null || val === undefined || val === '') return '';
    return parseFloat(val.replace(/[^0-9.-]+/g, '')) || 0;
};

watch(
    () => value,
    (newValue) => {
        formattedValue.value = formatValue(newValue.value);
    },
    { immediate: true }
);

const handleInput = (event) => {
    const rawValue = parseValue(event.target.value);
    value.value = rawValue;
    formattedValue.value = formatValue(rawValue);
}
</script>

<template>
    <div class="form-group col-md-6 mt-3">
        <label :for="field.id + 'formatted'">
            {{ field.label }}
            <span v-if="field.required" class="text-danger">*</span>
        </label>

        <input type="text" :id="field.id + 'formatted'" class="form-control" :required="field.required"
            :readonly="field.readonly" :placeholder="field.placeholder" :disabled="field.disabled"
            :value="formattedValue" @input="handleInput" />

        <input type="hidden" :name="field.name" :value="value" />
    </div>
</template>