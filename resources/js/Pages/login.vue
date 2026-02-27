// This script handles the login page.
// It sets up a form for user authentication and displays error messages if login fails.
// The template includes a form for entering username and password, styled with a centered layout.
<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { computed, ref } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import Logo from './components/Logo.vue';
    import PremonishHandler from './components/accesibility/PremonishHandler.vue';
    import TemeHandler from './components/accesibility/TemeHandler.vue';

    const form = useForm({
        name: '',
        password: '',
    });

    const errorMessage = computed(() => usePage().props.flash.error?.message ?? '');
    const global_use_social_login = ref(usePage().props.global_use_social_login);

    const submit = () => {
        form.post('/login');
    };
</script>

<template>
    <PremonishHandler />
    <TemeHandler />
    <div class="d-flex justify-content-center" style="max-width: 540px; max-height: 620px;">
            <div class="bg-white shadow bg-body rounded d-flex flex-column align-items-center p-3">
                <Logo :with="'300'" :class="'mt-5'"/>
                <h2 class="mt-5 p-3 ps-0" id="title"><strong>Iniciar Sesión</strong></h2>

                <h4 v-if="errorMessage" class="text-danger">{{ errorMessage }}</h4>

                <div class="w-100">
                    <form class="mt-3" @submit.prevent="submit">
                        <div class="row justify-content-center align-items-center">
                            <div class="form-group col-12">
                                <label for="name"><strong>Usuario</strong></label>
                                <input v-model="form.name" type="text" id="name" class="form-control mt-2" required :disabled="form.processing">
                            </div>
                            <div class="form-group col-12">
                                <label for="password"><strong>Contraseña</strong></label>
                                <input v-model="form.password" type="password" id="password" class="form-control mt-2" required :disabled="form.processing">
                            </div>
                        </div>
                        <div class="row justify-content-center align-items-center mt-5">
                            <div class="col-12">
                                <button class="btn btn-success w-100" type="submit" :disabled="form.processing">
                                    Entrar
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="mt-3" v-if="global_use_social_login">
                        <h5 class="text-center">O Iniciar Sesión Con</h5>
                        <div class="d-flex justify-content-center align-items-center gap-2 mt-2">
                            <a :href="route('socialAuth.redirect', {provider: 'google', state: 'login'})"
                                class="btn btn-danger"><i class='bx bxl-google'></i></a>
                            <a :href="route('socialAuth.redirect', {provider: 'facebook', state: 'login'})"
                                class="btn btn-info"><i class='bx bxl-facebook-square'></i></a>
                            <a :href="route('socialAuth.redirect', {provider: 'github', state: 'login'})"
                                class="btn btn-dark"><i class='bx bxl-github'></i></a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</template>

<style>
    #app{
        height: 100vh;
        display: flex;
        justify-content: center;
        padding-top: 50px;
    }

    [data-bs-theme=dark-hc] {
        .shadow {
            border-color: #fff !important;
        }
    }
</style>