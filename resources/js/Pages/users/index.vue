<script setup>
    import dashboard from '../layouts/dashboard.vue';
    import SubNavbar from '../components/SubNavbar.vue';
    import DeleteModal from '../components/DeleteModal.vue';
    import Table from '../components/Table.vue';
    import Form from '../components/Form.vue';
    import ExcelExport from '../components/buttons/ExcelExport.vue';
    import Print from '../components/buttons/Print.vue';

    import { usePage } from '@inertiajs/vue3';
    import { computed, onMounted, ref, reactive } from 'vue';
    
    const links = [
        { route: 'users.index', name: 'Usuarios', active: true },
    ];

    const form_modules = computed(() => {
        return usePage().props.form_modules;
    });

    const form = reactive({
        modules: [],
    });

    const role = ref(1);
    onMounted(() => {
        const roleElement = document.querySelector('select[name="role"]');
        if (roleElement) {
            role.value = roleElement.value;
            roleElement.addEventListener('change', (event) => {
                role.value = event.target.value;
            });
        }
    });
</script>

<template>
    <dashboard>
        <SubNavbar :links="links" />
        <div class="container">
            
            <div class="card">
                <Form :route="route('users.store')" :method="'POST'" :model="usePage().props.model" :form="form">
                    <template #extraContent>
                        <div class="mt-3 col-md-12 mt-3" id="modules" v-if="role == 1">
                            <h6>Módulos</h6>
                            <div class="row user-select-none">
                                <template v-for="(module, index) in form_modules" :key="index">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <label :for="'module-'+module.id" class="form-check-label d-flex">
                                                <input type="checkbox" name="modules[]" 
                                                    :id="'module-'+module.id" 
                                                    :value="module.id"
                                                    v-model="form.modules"
                                                    class="form-check checkbox-modules">

                                                <small>{{module.name}}</small>
                                            </label>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template #secondaryButtons>
                        <ExcelExport :filename="'Usuarios'" :target="'users_table'"/>
                        <Print :view-name="'users.print.index'" :title="'Usuarios'" :page-properties="{'pagedjs': true, 'pagecounter': true}" :params="{}"/>
                    </template>
                </Form>
            </div>

            <div class="row">
                <div class="col-lg-12 mt-4">
                    <h5>Usuarios</h5>
                    <hr>
                    <Table :model="usePage().props.model" :options="['delete']" :id="'users'"/>
                </div>
            </div>
        </div>
        <DeleteModal />
    </dashboard>
</template>