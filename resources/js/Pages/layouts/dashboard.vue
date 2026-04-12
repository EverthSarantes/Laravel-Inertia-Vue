// This script sets up the dashboard layout.
// It includes navigation, theming options, and a slot for injecting page-specific content.
// The template provides a sidebar for navigation and a header for user actions.
<script setup>
    import { computed, defineProps, watch } from 'vue';
    import { usePage, Link  } from '@inertiajs/vue3';
    import { useHead } from '@vueuse/head';
    import Logo from '../components/Logo.vue';
    import PremonishHandler from '../components/accesibility/PremonishHandler.vue';
    import TemeHandler from '../components/accesibility/TemeHandler.vue';
    import UserConfig from '../components/UserConfig.vue';
    import PersistentTabs from '../components/PersistentTabs.vue';

    const props = defineProps({
        appName: String,
    });

    const page = usePage();
    const modules = computed(() => {
        return [...page.props.modules]
        .filter(module => module.app === props.appName)
        .sort((a, b) => a.order - b.order);
    });

    const userName = computed(() => page.props.userName);
    const message = computed(() => page.props.flash.message);
    const error = computed(() => {
        const errors = page.props?.errors ?? null;
        if (errors && typeof errors === 'object') {
            const firstKey = Object.keys(errors)[0];
            return errors[firstKey];
        }
        return null;
    });

    watch(message, (newMessage) => {
        if (newMessage) {
            showToast(newMessage.message);
        }
    });

    watch(error, (newError) => {
        if (newError) {
            showToast(newError);
        }
    });

    useHead({
        link: [
            { rel: 'stylesheet', href: '/css/menu.css' },
        ],
    });
</script>

<template>
    <PremonishHandler />
    <TemeHandler />
    <PersistentTabs />
    <header class="header d-flex justify-content-end" id="header">
        <UserConfig :userName="userName" />
    </header>

    <div class="l-navbar" id="nav-bar">
        <nav class="nav">

            <div>
                <div class="nav_list">
                    <Link href="/" class="nav_logo logo-toggle nav_module_name" aria-label="Ir a inicio"><Logo/></Link>
                </div>
                <div class="nav_list mt-5">
                    <template v-for="module in modules" :key="module.name">
                        <Link v-if="module.route" :href="module.route" class="nav_link" aria-label="Ir a {{ module.name }}">
                            <i :class="`bx ${module.icon} nav_icon`"></i>
                                <span
                                    class="nav_name nav_module_name"
                                    :style="module.name.length > 15 ? 'font-size: 0.85em;' : ''"
                                >
                                {{ module.name }}
                                </span>
                            </Link>
                    </template>
                </div>
            </div>

            <div class="post_user d-flex justify-content-between align-items-center">
                <span class="nav_module_name"> {{ userName }} </span>
                <Link class="d-flex justify-content-between align-items-center text-decoration-none" href="/logout" aria-label="Cerrar sesión"> <i class='bx bxs-x-circle icon_block'></i></Link>
            </div>
        </nav>
    </div>

    <main class="pt-3 w-100">
        <slot />
    </main>

</template>