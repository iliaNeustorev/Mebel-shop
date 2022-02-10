<template>
    <div class="d-grid gap-2 d-md-block text-center mb-3">
        <button @click="exportCategories()" class="btn btn-primary btn-xl mb-2">
            Выгрузить список категорий
        </button>
        <button type="submit" class="btn btn-link btn-xl mb-2">
            Загрузить список категорий
        </button>
        <div v-if="processing" class="alert alert-warning text-center">
            Категории выгружаются
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div v-else-if="exportFinished" class="alert alert-success text-center">
            Категории выгружены <a :href="downloadLink">(скачать)</a>
        </div>
    </div>
</template>

<script>
export default {
    props: ["routeExportCategories"],
    data() {
        return {
            processing: false,
            exportFinished: false,
            downloadLink: null,
        }
    },
    methods: {
        exportCategories() {
            this.processing = true
            this.exportFinished = false
            axios.post(this.routeExportCategories)
        },
    },
    mounted() {
        Echo.channel("general").listen(".categories-export-finish", (e) => {
            this.processing = false
            this.exportFinished = true
            this.downloadLink = `/storage/${e.message}`
        })
    },
    destroyed() {
        Echo.channel("general").stopListening(".categories-export-finish")
    },
}
</script>

<style scoped>
.avatar {
    height: 200px;
    width: 200px;
}
</style>
