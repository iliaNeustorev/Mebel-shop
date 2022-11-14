<template>
    <div class="container addressess mt-2 pt-2">
        <label class="form-lable"> Список адресов </label>
        <br />
        <div v-for="address in addressesList" :key="address.id">
            <input
                :checked="address.main"
                :value="address.id"
                @change="updAddress"
                type="radio"
            />
            <label>
                {{ address.address }}
            </label>
            <button
                @click="delAddress(address.id)"
                :disabled="address.main == 1"
                title="Удалить адрес"
                class="btn-del-address btn btn-danger btn-sm"
            >
                X
            </button>
        </div>
        <div class="mt-3">
            <form-input-component
                :form="newAddress"
                name="new_address"
                label="Новый адрес"
            />
            <form-checkbox-component
                :form="newAddress"
                name="main_new_address"
                label="Сделать основным"
            />
        </div>
        <button
            :disabled="!validationNewAddress"
            @click="saveAddress()"
            class="btn btn-success mb-3"
        >
            Добавить новый адрес
        </button>
    </div>
</template>

<script>
import Form from "vform"
export default {
    props: {
        addressesList: { type: Array, required: true },
    },
    computed: {
        validationNewAddress() {
            return this.newAddress.new_address.length > 0
        },
    },
    data() {
        return {
            newAddress: Form.make({
                main_new_address: false,
                new_address: "",
            }),
        }
    },
    methods: {
        saveAddress() {
            axios
            this.newAddress
                .post("/api/home/profile/addAddress")
                .then((response) => {
                    this.$swal({
                        icon: "success",
                        title: "Адрес добавлен",
                    })
                    this.$emit("eventAdressList", {
                        newAddressesList: response.data,
                    })
                })
                .catch(() => {})
        },

        delAddress(addressId) {
            const params = {
                address_id: addressId,
            }
            axios
                .post("/api/home/profile/del_address", params)
                .then((response) => {
                    this.$swal({
                        icon: "success",
                        title: "Адрес удален",
                    })
                    this.$emit("eventAdressList", {
                        newAddressesList: response.data,
                    })
                })
                .catch(() => {})
        },
        updAddress(e) {
            const params = {
                main_address: e.target.value,
            }
            axios
                .post("/api/home/profile/updateMainAddress", params)
                .then((response) => {
                    this.$swal({
                        icon: "success",
                        title: "Основной адрес изменен",
                    })
                    this.$emit("eventAdressList", {
                        newAddressesList: response.data,
                    })
                })
                .catch(() => {})
        },
    },
}
</script>

<style>
.addressess {
    width: 40%;
    margin-left: 3%;
    border: 3px dotted lavender;
    font-size: small;
}
.btn-del-address {
    height: 20px;
    width: 20px;
}

.btn-sm {
    padding: 0.11rem 0.3rem;
    font-size: 0.587rem;
    border-radius: 0.2rem;
}
</style>
