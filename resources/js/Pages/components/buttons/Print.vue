<script setup>
    import { computed } from 'vue';

    const props =defineProps({
        viewName: String,
        title: String,
        params: Object,
        pageProperties: Object,
    });

    const printUrl = computed(() => {
        let url = new URL(route('exports.print'));
        url.searchParams.append('view_name', props.viewName);
        url.searchParams.append('title', props.title);
        url.searchParams.append('params', JSON.stringify(props.params));
        url.searchParams.append('page_properties', JSON.stringify(props.pageProperties));
        return url.toString();
    });
</script>

<template>
    <a
        class="btn btn-secondary"
        :href="printUrl"
    >
        <i class="bx bxs-printer"></i>
    </a>
</template>