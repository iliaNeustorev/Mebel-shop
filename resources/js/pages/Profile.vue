<template>
    <div>
        <show-errors-component v-if="errors" :errors="errors" />
        <div class="text-center" v-if="loading"><loading-component /></div>
        <div v-else>
            <form-input-component
                label="Почта"
                name="email"
                type="email"
                :form="userCurrent"
                :old-value="oldData.email"
                @validation-filed="fieldValid"
            />
            <form-input-component
                label="Имя"
                name="name"
                :form="userCurrent"
                :old-value="oldData.name"
                @validation-filed="fieldValid"
            />
            <div class="mb-3">
                <button
                    @click="passwordChange = !passwordChange"
                    class="btn btn-success mt-2 mb-2"
                >
                    Сменить пароль
                </button>
                <transition name="slide">
                    <change-password-component
                        v-show="passwordChange"
                        @completed="passwordChange = false"
                    />
                </transition>
                <div class="mb-3">
                    <img
                        class="avatar"
                        :src="`/storage/img/users/` + userCurrent.picture"
                    />
                    <form-file-component
                        label="Аватар"
                        name="picture"
                        :form="userPicture"
                    />
                    <button
                        :disabled="!validationChangePicture"
                        @click="loadAvatar()"
                        class="btn btn-success mt-2"
                    >
                        Загрузить новый аватар
                    </button>
                </div>
            </div>
            <button
                @click="showAddress = !showAddress"
                class="btn btn-success mt-2 mb-2"
            >
                {{ btnAddress }}
            </button>
            <transition name="slide">
                <address-list-component
                    v-show="showAddress"
                    :addresses-list="addressesList"
                    @event-adress-list="changeAdressList"
                />
            </transition>
            <br />

            <button
                :disabled="!validationForm"
                @click="saveProfile()"
                class="btn btn-primary btn-lg mt-2"
            >
                Сохранить
            </button>
        </div>
    </div>
</template>

<script>
import Form from "vform"
import editElementBd from "../mixins/edit-element-Bd.js"
export default {
    mixins: [editElementBd],
    data() {
        return {
            oldData: {
                email: "",
                name: "",
            },
            userCurrent: Form.make({
                email: "",
                name: "",
            }),
            validation: { email: false, name: false },
            userPicture: Form.make({
                picture: null,
            }),
            passwordChange: false,
            showAddress: false,
            errors: null,
            loading: true,
            addressesList: [],
        }
    },
    computed: {
        validationChangePicture() {
            return this.userPicture.picture != null
        },
        btnAddress() {
            return !this.address ? "Показать адреса" : "Скрыть адреса"
        },
    },
    created() {
        axios
            .get("/api/home/profile/")
            .then((response) => {
                this.oldData = response.data.user
                Object.assign(this.userCurrent, response.data.user)
                this.addressesList = response.data.addresses
                this.loading = false
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
    },
    methods: {
        saveProfile() {
            if (validationForm) {
                this.errors = null
                axios
                this.userCurrent
                    .post("/api/home/profile/update")
                    .then(() => {
                        this.$swal({
                            icon: "success",
                            title: "Профиль обновлен",
                        })
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors
                    })
            }
        },
        loadAvatar() {
            axios
            this.userPicture
                .post("/api/home/profile/updateAvatar")
                .then((response) => {
                    this.userCurrent.picture = response.data
                    this.$swal({
                        icon: "success",
                        title: "Аватар обновлен",
                    })
                    this.$forceUpdate()
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
        changeAdressList(e) {
            this.addressesList = e.newAddressesList
        },
    },
}
</script>

<style scoped>
.avatar {
    height: 200px;
    width: 200px;
}
</style>
