<template>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Авторизация</div>

                <div class="card-body">
                    <form-input-component
                        ref="first"
                        label="Почта"
                        name="email"
                        type="email"
                        placeholder="Введите почту"
                        :form="loginData"
                        autocomplete="email"
                        @validationFiled="fieldValid"
                    />

                    <form-input-component
                        label="Пароль"
                        type="password"
                        name="password"
                        placeholder="Введите пароль"
                        :form="loginData"
                        autocomplete="current-password"
                        @validationFiled="fieldValid"
                    />
                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-2">
                            <span
                                v-if="loading"
                                class="spinner-border text-primary"
                                role="status"
                            >
                                <span class="visually-hidden">Загрузка</span>
                            </span>
                            <button
                                v-else
                                :disabled="!validationForm"
                                @click="login()"
                                class="btn btn-primary btn-lg"
                            >
                                Войти
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
            loginData: Form.make({
                email: "",
                password: "",
            }),
            loading: false,
            validation: { email: false, password: false },
        }
    },
    methods: {
        login() {
            if (this.validationForm) {
                this.loading = true
                axios.get("/sanctum/csrf-cookie").then(() => {
                    axios
                    this.loginData
                        .post("/api/login")
                        .then((response) => {
                            this.$store.dispatch("getUser", response.data.user)
                            this.$store.dispatch(
                                "getChekOrders",
                                response.data.orders
                            )
                            localStorage.setItem(
                                "user",
                                response.data.user.name
                            )
                            window.history.length > 1
                                ? this.$router.go(-1)
                                : this.$router.push({ name: "Home" })
                        })
                        .catch(() => {})
                        .finally(() => {
                            this.loading = false
                        })
                })
            }
        },
    },
}
</script>
