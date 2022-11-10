<template>
    <div v-if="processing" class="alert alert-warning text-center">
        {{ name }} выгружаются
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <progress-bar-component
            v-if="processing"
            channel="counter"
            :event="event"
        />
    </div>
    <div v-else-if="exportFinished" class="alert alert-success text-center">
        {{ name }} выгружены <a :href="downloadLink">(скачать)</a
        ><button class="btn btn-danger btn-sm" @click="exportFinished = false">
            x
        </button>
    </div>
</template>

<script>
export default {
    props: {
        uri: { type: String, required: true },
        event: { type: String, required: true },
        channel: { type: String, required: true },
        name: { type: String, required: true },
        start: { type: Boolean, required: true, default: false },
    },
    data() {
        return { processing: false, exportFinished: false, downloadLink: null }
    },
    methods: {
        export() {
            this.processing = true
            axios
                .post(this.uri)
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
                .finally(() => {
                    this.exportFinished = false
                })
        },
    },
    mounted() {
        if (this.start) {
            this.export()
        }
        Echo.channel("general").listen(`.${this.channel}`, (e) => {
            this.processing = false
            this.exportFinished = true
            this.downloadLink = `/storage/${e.message}`
        })
    },
    destroyed() {
        Echo.channel("general").stopListening(`.${this.channel}`)
    },
}
</script>
