<script setup>
    import { computed, reactive } from 'vue';
    import { useForm } from '@inertiajs/vue3';

    import INPUT from './inputs/input.vue';
    import SELECT from './inputs/select.vue';
    const componentMap = {
        INPUT,
        SELECT,
    };

    const props = defineProps({
        route: String,
        method: String,
        model: Object,
        form: Object,
    });

    const form = props.form ?? reactive({});
    Object.entries(props.model.form_fields).forEach(([key, value]) => {
        form[key] = null;
    });

    const formMethod = computed(() => {
        return ['POST', 'PUT', 'PATCH'].includes(props.method) ? 'POST' : props.method;
    });

    const submitForm = () => {
        const send_form = useForm(form);

        send_form.submit(formMethod.value.toLowerCase(), props.route, {
            preserveScroll: true,
            onSuccess: () => {
                window.atm_tables.forEach((table) => {
                    table.refresh();
                });
            },
        });
    };

</script>

<template>
    <form @submit.prevent="submitForm" class="p-3 mt-2">
        <div class="row">
            <input type="hidden" name="_method" :value="props.method">

            <template v-for="(field, index) in model.form_fields" :key="index">
                <component 
                    :is="componentMap[field.input_type.toUpperCase()]" 
                    v-model:value="form[field.name]"
                    :field="field">
                </component>
            </template>

            <slot name="extraContent"></slot>

            <div class="col-md-12 mt-2 justify-content-between d-flex">
                <button type="submit" class="btn btn-primary">Agregar</button>
                <div class="d-flex gap-2">
                    <slot name="secondaryButtons"></slot>
                </div>
            </div>
        </div>
    </form>
</template>