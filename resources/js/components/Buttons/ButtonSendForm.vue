<template>
    <div>
        <button
            data-bs-toggle="modal"
            :data-bs-target="`#exampleModal` + numberModalWindow"
            :disabled="!validationForm"
            class="btn"
            :class="StyleButton"
        >
            {{ ButtonName }} <slot name="textModalBtn"></slot>
        </button>

        <div
            class="modal fade"
            :id="`exampleModal` + numberModalWindow"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Подтвердите изменения
                        </h5>
                    </div>
                    <div class="modal-body">
                        <slot class="row" name="mainModal"></slot>
                    </div>
                    <div class="modal-footer" name="footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            @click="cancelSend()"
                        >
                            Отмена
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                            @click="sendForm()"
                        >
                            Ок
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    data() {
        return {
            numberModalWindow: Math.floor(Math.random() * 10),
        }
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
        cancelSend() {
            this.$emit("cancelSend")
        },
    },
}
</script>

<style></style>
