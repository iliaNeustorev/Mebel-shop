<template>
    <div>
        <form v-if="!processing">
            <form-file
                label=" Выберите файл CSV или TXT"
                name="file"
                :form="form"
            />

            <button
                @click.prevent="sendForm()"
                :disabled="!validationForm"
                class="btn btn-primary"
            >
                Загрузить
            </button>
            <button @click="cancel" class="btn btn-primary">Отмена</button>
        </form>
        <div v-if="processing" class="alert alert-warning text-center">
            {{ name }} загружаются
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <progress-bar-component
                v-if="processing"
                channel="counter"
                :event="event"
            />
        </div>
        <div
            v-else-if="importFinished"
            class="alert alert-success text-center mt-2"
        >
            {{ result }}
            <button class="btn btn-danger btn-sm" @click="hiddenAlert()">
                x
            </button>
        </div>
    </div>
</template>

<script>
import Form from "vform"
export default {
    props: {
        event: { type: String, required: true },
        channel: { type: String, required: true },
        name: { type: String, required: true },
        uri: { type: String, required: true },
    },
    data() {
        return {
            form: Form.make({
                file: null,
            }),
            processing: false,
            importFinished: false,
            result: null,
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
                this.processing = true
                axios
                this.form
                    .post(this.uri)
                    .catch((error) => {
                        this.errors = error.response.data.errors
                    })
                    .finally(() => {
                        this.importFinished = false
                        this.form.file = null
                    })
            }
        },
        cancel() {
            this.$emit("cancelDownload")
        },
        hiddenAlert() {
            this.result = null
            this.importFinished = false
        },
    },
    mounted() {
        Echo.channel("general").listen(`.${this.channel}`, (e) => {
            this.processing = false
            this.importFinished = true
            this.result = e.message
        })
    },
    destroyed() {
        Echo.channel("general").stopListening(`.${this.channel}`)
    },
}
</script>
