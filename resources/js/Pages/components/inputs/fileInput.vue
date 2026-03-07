<script setup>
    import { ref, onUnmounted } from 'vue';

    const props = defineProps({
        field: {
            type: Object,
            required: true,
            accept: 'image/*,application/pdf,text/plain,.doc,.docx,.xls,.xlsx'
        }
    });

    const file = defineModel('file');
    const previewUrl = ref('');
    const fileType = ref('');
    let previousUrl = null;

    function handleFileChange(event) {
        const selected = event.target.files && event.target.files[0];
        file.value = selected;

        if (previousUrl) {
            URL.revokeObjectURL(previousUrl);
            previousUrl = null;
        }
        previewUrl.value = '';
        fileType.value = '';

        if (!selected) return;

        const type = selected.type;

        if (type.startsWith('image/') || type === 'application/pdf' || type.startsWith('text/')) {
            previewUrl.value = URL.createObjectURL(selected);
            previousUrl = previewUrl.value;
            fileType.value = type;
        }
    }

    onUnmounted(() => {
        if (previousUrl) URL.revokeObjectURL(previousUrl);
    });
</script>

<template>
    <div class="row">
        <div class="form-group col-md-6 mt-3">
            <label :for="field.id">{{ field.label }}</label>
            <input
                type="file"
                :name="field.name"
                :id="field.id"
                class="form-control"
                :required="field.required"
                :readonly="field.readonly"
                @change="handleFileChange"
                :multiple="false"
                :accept="field.accept"
                
            />
        </div>

        <div v-if="previewUrl" class="mt-2 col-md-6 preview-container">

            <img v-if="fileType.startsWith('image/')" :src="previewUrl" alt="Preview" class="img-fluid rounded preview-img" />

            <iframe v-else-if="fileType === 'application/pdf'" :src="previewUrl" class="preview-pdf"></iframe>

            <div v-else-if="fileType.startsWith('text/')" class="preview-text">
                <iframe :src="previewUrl" class="preview-text" frameborder="0"></iframe>
            </div>

            <div v-else class="text-center">
                <p>Archivo cargado: {{ file?.name }}</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .preview-container {
        width: 350px;
        height: 350px;
        overflow: hidden;
        border-radius: 4px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .preview-img {
        width: 100%;
        height: auto;
        object-fit: contain;
    }

    .preview-pdf, .preview-text {
        width: 100%;
        height: 100%;
        border: none;
    }
</style>