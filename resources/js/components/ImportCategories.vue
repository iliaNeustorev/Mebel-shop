<template>
    <div>
        <form>
            <form-file
                label=" Выберите файл CSV или TXT"
                name="file"
                :form="form"
            />
        </form>
        <button
            @click="sendForm()"
            :disabled="!validationForm"
            class="btn btn-primary"
        >
            Загрузить
        </button>
        <button @click="cancel" class="btn btn-primary">Отмена</button>
    </div>
</template>

<script>
import Form from "vform"
export default {
    data() {
        return {
            form: Form.make({
                file: null,
            }),
        }
    },
    computed: {
        validationForm() {
            return (
                this.form.file != null && /\.csv|txt$/.test(this.form.file.name)
            )
        },
    },
    methods: {
        sendForm() {
            if (this.validationForm) {
                axios
                this.form
                    .post("/api/admin/importCategories")
                    .catch((error) => {
                        this.errors = error.response.data.errors
                    })
                    .finally(() => {})
            }
        },
        cancel() {
            console.log("aasdadas")
            this.$emit("cancelDownload")
        },
    },
}
</script>
