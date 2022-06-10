<template>
    <button
        @click="sendForm()"
        :disabled="!validationForm"
        class="btn"
        :class="StyleButton"
    >
        {{ ButtonName }}
    </button>
</template>

<script>
export default {
    props: {
        validationForm: { type: Boolean, default: false },
        nameButtonAccepted: { type: String, required: true },
        nameButtonDenied: { type: String, required: true },
    },
    computed: {
        StyleButton() {
            return !this.validationForm ? "btn-danger" : "btn-success"
        },
        ButtonName() {
            return !this.validationForm
                ? "✘ " + this.nameButtonDenied
                : this.nameButtonAccepted
        },
    },
    methods: {
        sendForm() {
            this.$emit("acceptedForm")
        },
    },
}
</script>

<style></style>
