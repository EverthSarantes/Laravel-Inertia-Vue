<script setup>

import dashboard from '../layouts/dashboard.vue';

</script>

<template>
    <dashboard :appName="'ai_assistant_app'">
        <div class="container p-3">
            <ul class="nav nav-tabs flex-row justify-content-end" id="pills-tab" role="tablist" style="border: none;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-queries-tab" data-bs-toggle="pill" data-bs-target="#pills-queries" type="button" role="tab" aria-controls="pills-queries" aria-selected="true">Consultas</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-reports-tab" data-bs-toggle="pill" data-bs-target="#pills-reports" type="button" role="tab" aria-controls="pills-reports" aria-selected="false">Reportes</button>
                </li>
            </ul>
        </div>

        <div class="tab-content container" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-queries" role="tabpanel" aria-labelledby="pills-queries-tab">
                <div class="row">
                    <div class="col mb-3">
                        <input type="text" name="query" id="query" class="form-control" placeholder="Escribe tu consulta...">
                    </div>
                    <div class="col-2">
                        <button class="btn btn-primary">Enviar</button>
                    </div>
                </div>

                <div class="m-3 card" style="height: 70lvh;">
                    {{ response }}
                </div>
            </div>

            <div class="tab-pane fade text-primary" id="pills-reports" role="tabpanel" aria-labelledby="pills-reports-tab">
                <div class="row">
                </div>
            </div>
        </div>
    </dashboard>
</template>