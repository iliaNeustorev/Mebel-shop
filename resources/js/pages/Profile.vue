<template>
    <div>
        <div v-if="errors" class="alert alert-danger">
            <ul>
                <li v-for="(error, idx) in errors" :key="idx">
                    {{ error[0] }}
                </li>
            </ul>
        </div>
        <div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">
                    Почта
                </label>
                <input
                    type="email"
                    class="form-control w-50"
                    v-model="userCurrent.email"
                />
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">
                    Имя
                </label>
                <input
                    type="text"
                    class="form-control w-50"
                    v-model="userCurrent.name"
                />
            </div>
            <div class="mb-3">
                <button
                    @click="passwordChange = !passwordChange"
                    class="btn btn-success mt-2 mb-2"
                >
                    Сменить пароль
                </button>
                <div v-show="passwordChange" class="slide">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">
                            Текущий пароль
                        </label>
                        <input
                            type="password"
                            class="form-control w-50"
                            v-model="oldPassword"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">
                            Новый пароль
                        </label>
                        <input
                            type="password"
                            class="form-control w-50"
                            v-model="newPassword"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">
                            Повторите пароль
                        </label>
                        <input
                            type="password"
                            class="form-control w-50"
                            v-model="repeatNewPassword"
                        />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label"> Изображение </label>
                    <br />
                    <img
                        class="avatar"
                        :src="'/storage/img/users/' + userCurrent.picture"
                    />
                    <input
                        id="file"
                        type="file"
                        ref="file"
                        class="form-control w-50"
                        v-on:change="handleFileUpload()"
                    />
                    <button @click="loadAvatar()" class="btn btn-success mt-2">
                        Загрузить новый аватар
                    </button>
                </div>
            </div>
            <button
                @click="address = !address"
                class="btn btn-success mt-2 mb-2"
            >
                {{ btnAddress }}
            </button>
            <transition name="slide">
                <div v-show="address" class="container addressess">
                    <label class="form-lable"> Список адресов </label>
                    <br />
                    <div v-for="address in addressesList" :key="address.id">
                        <input
                            :checked="checkAddress"
                            v-model="pickedAddress"
                            @change="updAddress()"
                            :value="address.id"
                            type="radio"
                        />
                        <label>
                            {{ address.address }}
                        </label>
                        <button
                            @click="delAddress(address.id)"
                            :disabled="address.main == 1"
                            title="Удалить адрес"
                            type="submit"
                            class="btn-del-address btn btn-danger btn-sm"
                        >
                            X
                        </button>
                    </div>
                    <div class="mb-3 mt-3">
                        <label class="form-lable"> Новый адрес </label>
                        <input v-model="new_address" class="form-control" />
                        <label>Сделать основным</label>
                        <input
                            type="checkbox"
                            v-model="checked"
                            true-value="да"
                            false-value="нет"
                        />
                    </div>
                    <button @click="saveAddress()" class="btn btn-success mb-3">
                        Добавить новый адрес
                    </button>
                </div>
            </transition>
            <br />
        </div>
        <button @click="saveProfile()" class="btn btn-primary btn-lg mt-2">
            Сохранить
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            passwordChange: false,
            address: false,
            errors: null,
            checked: false,
            userCurrent: [],
            addressesList: [],
            file: {},
            new_address: "",
            pickedAddress: "",
            newPassword: "",
            oldPassword: "",
            repeatNewPassword: "",
        }
    },
    computed: {
        btnAddress() {
            return !this.address ? "Показать адреса" : "Скрыть адреса"
        },
        checkAddress() {
            for (let address of this.addressesList) {
                if (address.main) {
                    this.pickedAddress = address.id
                }
            }
        },
    },
    created() {
        axios
            .get("/api/home/profile/")
            .then((response) => {
                this.userCurrent = response.data.user
                this.addressesList = response.data.addresses
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
            .finally(() => {})
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
    },
    methods: {
        saveProfile() {
            this.errors = null
            const params = {
                name: this.userCurrent.name,
                email: this.userCurrent.email,
                password: this.newPassword,
                password_confirmation: this.repeatNewPassword,
                current_password: this.oldPassword,
            }
            axios
                .post("/api/home/profile/update", params)
                .then((response) => {
                    this.$swal({
                        title: "Профиль обновлен",
                        icon: "success",
                    }).then(() => {})
                    this.addressesList = response.data.addresses
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
                .finally(() => {})
        },

        loadAvatar() {
            let formData = new FormData()
            formData.append("file", this.file)
            axios
                .post("/api/home/profile/updateAvatar", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    this.$swal({
                        title: "Аватар обновлен",
                        icon: "success",
                    }).then(() => {})
                    this.userCurrent.picture = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
                .finally(() => {})
        },
        handleFileUpload() {
            this.file = this.$refs.file.files[0]
        },

        saveAddress() {
            this.errors = null
            const params = {
                main_new_address: this.checked,
                new_address: this.new_address,
            }

            axios
                .post("/api/home/profile/addAddress", params)
                .then((response) => {
                    this.$swal({
                        title: "Адрес добавлен",
                        icon: "success",
                    }).then(() => {})
                    this.addressesList = response.data.addresses
                    if (response.data.flagMainAddress == true) {
                        this.checkAddress
                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
                .finally(() => {})
        },

        delAddress(addressId) {
            const params = {
                address_id: addressId,
            }
            axios
                .post("/api/home/profile/del_address", params)
                .then((response) => {
                    this.$swal({
                        title: "Адрес удален",
                        icon: "success",
                    }).then(() => {})
                    this.addressesList = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
                .finally(() => {})
        },
        updAddress() {
            const params = {
                main_address: this.pickedAddress,
            }
            axios
                .post("/api/home/profile/updateMainAddress", params)
                .then((response) => {
                    this.$swal({
                        title: "Основной адрес изменен",
                        icon: "success",
                    }).then(() => {})
                    this.addressesList = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
                .finally(() => {})
        },
    },
}
</script>

<style scoped>
.btn-del-address {
    height: 20px;
    width: 20px;
}

.btn-sm {
    padding: 0.11rem 0.3rem;
    font-size: 0.587rem;
    border-radius: 0.2rem;
}

.form-check {
    display: block ruby;
    min-height: 1.44rem;
    padding-left: 1.5em;
    margin-bottom: 0.125rem;
}

.addressess {
    width: 40%;
    margin-left: 3%;
    border: 3px dotted lavender;
    font-size: small;
}

.avatar {
    height: 200px;
    width: 200px;
}

.selected {
    color: rgb(25, 105, 52);
}
</style>
