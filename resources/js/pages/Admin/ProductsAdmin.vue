<template>
    <div class="d-grid gap-2 d-md-block text-center mb-3">
        <button @click="exportProducts()" class="btn btn-primary btn-xl mb-2">
            Выгрузить список продуктов
        </button>
        <button type="submit" class="btn btn-link btn-xl mb-2">
            Загрузить список продуктов
        </button>
        <div v-if="processing" class="alert alert-warning text-center">
            Продукты выгружаются
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div v-else-if="exportFinished" class="alert alert-success text-center">
            Продукты выгружены <a :href="downloadLink">(скачать)</a>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            processing: false,
            exportFinished: false,
            downloadLink: null,
        }
    },
    methods: {
        exportProducts() {
            this.processing = true
            axios
                .post("/api/admin/exportProducts")
                .then(() => {})
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
                .finally(() => {
                    this.exportFinished = false
                })
        },
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
        Echo.channel("general").listen(".products-export-finish", (e) => {
            this.processing = false
            this.exportFinished = true
            this.downloadLink = `/storage/${e.message}`
        })
    },
    destroyed() {
        Echo.channel("general").stopListening(".products-export-finish")
    },
}
</script>

<style scoped>
.avatar {
    height: 200px;
    width: 200px;
}
</style>
