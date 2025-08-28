<script setup>

    import dashboard from '../../layouts/dashboard.vue';
    import DeleteModal from '../../components/DeleteModal.vue';
    import Table from '../../components/Table.vue';
    import ExcelExport from '../../components/buttons/ExcelExport.vue';

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

    const addHourField = () => {
        updateScheduleForm.hours.push('')
    }

    const removeHourField = (index) => {
        updateScheduleForm.hours.splice(index, 1)
    }

    const schedules = usePage().props.schedules;

    const updateScheduleForm = useForm({
        days: schedules.days,
        hours: schedules.times,
        active: schedules.active,
    });

    const updateSchedule = () => {
        updateScheduleForm.put(route('backups.schedules.update'), {
            onSuccess: () => {
            },
        });
        let modal = document.getElementById('configBackupsModal');
        let modalInstance = bootstrap.Modal.getInstance(modal);
        modalInstance.hide();
    };
</script>

<template>
    <dashboard>
        <div class="container">
            
            <div class="card p-3 mt-2 row">
                <div class="col-md-12 mt-2 justify-content-between d-flex gap-2">
                    <button class="btn btn-primary" @click="sendBackupForm" :disabled="backupform.processing">
                        Crear Respaldo
                    </button>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#configBackupsModal">
                            <i class="bx bx-cog"></i>
                        </button>
                        <ExcelExport :filename="'backups'" :target="'backups_table'"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 mt-4">
                    <h5>Respaldos</h5>
                    <Table :model="usePage().props.model" :options="['delete']" :id="'backups'"/>
                </div>
            </div>
        </div>
        <DeleteModal />

        <!--modal configurar backups-->
        <div class="modal fade" id="configBackupsModal" tabindex="-1" aria-labelledby="configBackupsModalabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="configBackupsModalLabel">Configurar Respaldos</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="modal-body row" @submit.prevent="updateSchedule" id="updateScheduleForm" :disabled="updateScheduleForm.processing">
                        <h5>Activar Respaldo Automático</h5>
                        <div class="col-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="true" id="active" name="active" v-model="updateScheduleForm.active">
                            </div>
                        </div>

                        <h5>Días</h5>
                        <div class="col-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="monday" name="days[]" v-model="updateScheduleForm.days">
                                <label class="form-check-label" for="monday">Lunes</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="2" id="tuesday" name="days[]" v-model="updateScheduleForm.days">
                                <label class="form-check-label" for="tuesday">Martes</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="3" id="wednesday" name="days[]" v-model="updateScheduleForm.days">
                                <label class="form-check-label" for="wednesday">Miercoles</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="4" id="thursday" name="days[]" v-model="updateScheduleForm.days">
                                <label class="form-check-label" for="thursday">Jueves</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="5" id="friday" name="days[]" v-model="updateScheduleForm.days">
                                <label class="form-check-label" for="friday">Viernes</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="6" id="saturday" name="days[]" v-model="updateScheduleForm.days">
                                <label class="form-check-label" for="saturday">Sabado</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="7" id="sunday" name="days[]" v-model="updateScheduleForm.days">
                                <label class="form-check-label" for="sunday">Domingo</label>
                            </div>
                        </div>
                        <hr class="mt-4 mb-4">
                        <div class="col-md-12 mt-2 justify-content-between d-flex gap-2 mb-2">
                            <h5>Hora</h5>
                            <button type="button" class="btn btn-primary" @click="addHourField"><i class='bx bx-plus'></i></button>
                        </div>
                        <div class="hours-container row gap-2">
                            <div class="col-5 mb-2" v-for="(hora, index) in updateScheduleForm.hours" :key="index">
                                <div class="row">
                                    <div class="col">
                                        <input type="time" class="form-control" placeholder="Hora"
                                            v-model="updateScheduleForm.hours[index]"
                                        />
                                    </div>
                                    <button v-if="index > 0" type="button"
                                        class="btn btn-danger btn-sm ms-2 col-2"
                                        @click="removeHourField(index)"
                                    >
                                        <i class="bx bx-x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" form="updateScheduleForm">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

    </dashboard>
</template>