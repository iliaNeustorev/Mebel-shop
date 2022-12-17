<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Регистрация</div>
                <div class="card-body">
                    <form-input-component
                        label="Имя"
                        name="name"
                        placeholder="Введите имя"
                        :form="registerData"
                        @validation-filed="fieldValid"
                    />
                    <form-input-component
                        label="Почта"
                        name="email"
                        type="email"
                        placeholder="Введите почту"
                        :form="registerData"
                        @validation-filed="fieldValid"
                    />
                    <form-input-component
                        label="Пароль"
                        type="password"
                        name="password"
                        placeholder="Введите пароль"
                        :form="registerData"
                        @validation-filed="fieldValid"
                    />
                    <form-input-component
                        label="Повторите пароль"
                        type="password"
                        name="password_confirmation"
                        placeholder="Повторите пароль"
                        :form="registerData"
                        @validation-filed="fieldValid"
                    />

                    <div :disabled="!validationForm" class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <span
                                v-if="loading"
                                class="spinner-border text-primary"
                                role="status"
                            >
                                <span class="visually-hidden">Загрузка</span>
                            </span>
                            <button
                                v-else
                                @click="Registration()"
                                type="submit"
                                class="btn btn-primary btn-lg"
                            >
                                Регистрация
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Form from "vform"
import addElementBd from "../../mixins/add-element-Bd.js"
export default {
    mixins: [addElementBd],
    data() {
        return {
            registerData: Form.make({
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
            }),
            validation: {
                name: false,
                email: false,
                password: false,
                password_confirmation: false,
            },
            loading: false,
        }
    },
    methods: {
        Registration() {
            if (this.validationForm) {
                axios
                this.registerData
                    .post("/api/register")
                    .then((response) => {
                        this.loading = false
                        this.$store.dispatch("getUser", response.data.user)
                        this.$router.push({ name: "Home" })
                    })
                    .finally(() => {
                        this.loading = false
                    })
            }
        },
    },
}
</script>
