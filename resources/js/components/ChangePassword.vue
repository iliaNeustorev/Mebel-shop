<template>
    <div class="mb-2 slide">
        <form @submit.prevent="sendForm()">
            <form-input-component
                label="Текущий пароль"
                name="current"
                type="password"
                :form="passwordChange"
                @validation-filed="fieldValid"
            />
            <form-input-component
                label="Новый пароль"
                name="password"
                type="password"
                :form="passwordChange"
                @validation-filed="fieldValid"
            />
            <form-input-component
                label="Повторите пароль"
                name="password_confirmation"
                type="password"
                :form="passwordChange"
                @validation-filed="fieldValid"
            />
            <button :disabled="!validationForm" class="btn btn-success">
                ОК
            </button>
        </form>
    </div>
</template>

<script>
import Form from "vform"
import addElementBd from "../mixins/add-element-Bd.js"
export default {
    mixins: [addElementBd],
    data() {
        return {
            passwordChange: Form.make({
                current: "",
                password: "",
                password_confirmation: "",
            }),
            validation: {
                current: false,
                password: false,
                password_confirmation: false,
            },
        }
    },
    methods: {
        sendForm() {
            if (this.validationForm) {
                axios
                this.passwordChange
                    .post("/api/home/profile/updatePassword")
                    .then(() => {
                        this.$swal({
                            icon: "success",
                            title: "Пароль успешно обновлен",
                        })
                        this.$emit("completed")
                    })
                    .catch(() => {})
            }
        },
    },
}
</script>
