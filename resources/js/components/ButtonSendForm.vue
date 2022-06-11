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
        classButtonDenied: { type: String, default: "btn-danger" },
        classButtonAccepted: { type: String, default: "btn-success" },
    },
    computed: {
        StyleButton() {
            return !this.validationForm
                ? this.classButtonDenied
                : this.classButtonAccepted
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
