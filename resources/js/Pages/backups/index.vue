<script setup>

    import dashboard from '../layouts/dashboard.vue';
    import DeleteModal from '../components/DeleteModal.vue';
    import Table from '../components/Table.vue';
    import ExcelExport from '../components/buttons/ExcelExport.vue';
    import Print from '../components/buttons/Print.vue';

    import { useForm, usePage } from '@inertiajs/vue3';

    const backupform = useForm();

    const sendBackupForm = () => {
        backupform.post(route('backups.store'), {
            onSuccess: () => {
                backupform.reset();
                window.atm_tables.forEach((table) => {
                    table.refresh();
                });
            },
        });
    };
</script>

<template>
    <dashboard>
        <div class="container">
            
            <div class="card p-3 mt-2 row">
                <div class="col-md-12 mt-2 justify-content-end d-flex gap-2">
                    <button class="btn btn-primary" @click="sendBackupForm" :disabled="backupform.processing">
                        <i class="fa-solid fa-plus"></i> Crear Backup
                    </button>

                    <ExcelExport :filename="''" :target="'backups'"/>
                    <Print :view-name="''" :title="''" :page-properties="{'pagedjs': true, 'pagecounter': true}" :params="{}"/>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 mt-4">
                    <h5>Backups</h5>
                    <hr>
                    <Table :model="usePage().props.model" :options="['delete']" :id="'backups'"/>
                </div>
            </div>
        </div>
        <DeleteModal />
    </dashboard>
</template>