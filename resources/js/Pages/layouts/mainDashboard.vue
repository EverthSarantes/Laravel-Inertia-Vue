// This script sets up the dashboard layout.
// It includes navigation, theming options, and a slot for injecting page-specific content.
// The template provides a sidebar for navigation and a header for user actions.
<script setup>
    import { computed, onMounted } from 'vue';
    import { usePage, Link  } from '@inertiajs/vue3';
    import Message from '../components/Message.vue';
    import Logo from '../components/Logo.vue';
    import { useHead } from '@vueuse/head';
    import PremonishHandler from '../components/accesibility/PremonishHandler.vue';

    const page = usePage();
    const userName = computed(() => page.props.userName);
    const message = computed(() => page.props.flash.message);

    /* Theme Switcher */
    onMounted(() => {
        window.showActiveTheme(window.getPreferredTheme())

        document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {

            if(toggle.dataset.bsThemeValue === window.getPreferredTheme()) {
                toggle.setAttribute('checked', true)
            }

            toggle.addEventListener('click', () => {
                const theme = toggle.getAttribute('data-bs-theme-value')
                window.setStoredTheme(theme)
                window.setTheme(theme)
                window.showActiveTheme(theme, true)
            })
        });
    });

    useHead({
        link: [
            { rel: 'stylesheet', href: '/css/menu.css' },
        ],
    });
</script>

<template>
    <PremonishHandler />
    <header class="header d-flex justify-content-end" id="header">
        <div class="dropdown">
            <button type="button" class="btn btn-light" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ userName }}
            </button>
            <ul class="dropdown-menu" style="width: 250px;">
                <li>
                    <span class="dropdown-item-text">Tema</span>
                </li>
                <li>
                    <label class="d-flex justify-content-between align-items-center">
                        <span class="dropdown-item-text">Claro</span>
                        <input class="dropdown-item" type="radio" name="theme" id="light" value="light" style="width: 30px;" data-bs-theme-value="light">
                    </label>
                </li>
                <li>
                    <label class="d-flex justify-content-between align-items-center">
                        <span class="dropdown-item-text">Claro Alto Contraste</span>
                        <input class="dropdown-item" type="radio" name="theme" id="light-hc" value="light-hc" style="width: 30px;" data-bs-theme-value="light-hc">
                    </label>
                </li>
                <li>
                    <label class="d-flex justify-content-between align-items-center">
                        <span class="dropdown-item-text">Oscuro</span>
                        <input class="dropdown-item" type="radio" name="theme" id="dark" value="dark" style="width: 30px;" data-bs-theme-value="dark">
                    </label>
                </li>
                <li>
                    <label class="d-flex justify-content-between align-items-center">
                        <span class="dropdown-item-text">Oscuro Alto Contraste</span>
                        <input class="dropdown-item" type="radio" name="theme" id="dark-hc" value="dark-hc" style="width: 30px;" data-bs-theme-value="dark-hc">
                    </label>
                </li>

                <li><hr class="dropdown-divider"></li>
                <li><Link class="dropdown-item" href="/logout">Cerrar Sesión</Link></li>
            </ul>
        </div>
    </header>

    <div class="l-navbar" id="nav-bar">
        <nav class="nav">

            <div>
                <div class="nav_list">
                    <Link href="/" class="nav_logo logo-toggle nav_module_name"><Logo/></Link>
                </div>
            </div>

            <div class="post_user d-flex justify-content-between align-items-center">
                <span class="nav_module_name"> {{ userName }} </span>
                <Link class="d-flex justify-content-between align-items-center text-decoration-none" href="/logout"> <i class='bx bxs-x-circle icon_block'></i></Link>
            </div>
        </nav>
    </div>

    <Message v-if="message" :message="message.message" :color="message.type"/>

    <main class="pt-3 w-100">
        <slot />
    </main>

</template>