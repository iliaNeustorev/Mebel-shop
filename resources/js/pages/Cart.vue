<template>
    <div>
        <div v-if="errors" class="alert alert-danger">
            <ul>
                <li v-for="(error, idx) in errors" :key="idx">
                    {{ error[0] }}
                </li>
                <template v-if="errors.email">
                    Ссылка на <a href="">Вход</a>
                </template>
            </ul>
        </div>
        <table v-if="products.length" class="table table-bordered">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td>{{ product["title"] }}</td>
                    <td>{{ product["price"] }}</td>
                    <td>{{ product["quantity"] }}</td>
                    <td>
                        {{ product["quantity"] * product["price"] }}
                    </td>
                </tr>

                <tr v-if="products">
                    <td colspan="4">
                        <span
                            >Общая сумма заказа:<b>{{ sumOrder }}</b></span
                        >
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="mb-5 text-center" v-if="!products.length">
            <em>
                Нет товаров в корзине
                <router-link to="/"> Перейти в каталог</router-link></em
            >
        </div>
        <template v-if="products">
            <label>Имя</label>
            <input
                v-model="name"
                :disabled="isDisabled"
                class="form-control mb-2"
            />
            <label>Адрес</label>
            <input
                v-model="mainAddress"
                :disabled="isDisabledAddress"
                class="form-control mb-2"
            />
            <label>Почта</label>
            <input
                v-model="email"
                :disabled="isDisabled"
                type="email"
                class="form-control mb-2"
            />
            <button
                @click="submit"
                :disabled="processing || !products.length || !mainAddress"
                class="btn btn-success"
            >
                <span
                    v-if="processing"
                    class="spinner-border text-success"
                    role="status"
                >
                    <span class="visually-hidden">Загрузка</span>
                </span>
                <span v-else>Оформить заказ</span>
            </button>
        </template>
    </div>
</template>
<script>
export default {
    data() {
        return {
            processing: false,
            errors: null,
            isDisabled: true,
            isDisabledAddress: false,
            products: [],
            email: "",
            mainAddress: "",
            name: "",
            sumOrder: "",
        }
    },
    created() {
        axios.get("/api/basket/").then((response) => {
            this.products = response.data.products
            this.email = response.data.email
            this.mainAddress = response.data.mainAddress
            this.name = response.data.name
            this.sumOrder = response.data.sumOrder
            if (response.data.mainAddress != null) {
                this.isDisabledAddress = true
            }
        })
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
        if (!this.$store.state.user.name) {
            this.isDisabled = false
        }
    },
    methods: {
        submit() {
            this.processing = true
            this.errors = null
            const params = {
                name: this.name,
                email: this.email,
                address: this.mainAddress,
            }
            axios
                .post("/api/basket/create_order", params)
                .then((response) => {
                    this.$swal({
                        title: "Заказ оформлен!",
                        icon: "success",
                    }).then(() => {})
                    this.$store.dispatch("getChekOrders", response.data.orders)
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
                .finally(() => {
                    this.processing = false
                    this.$router.push({ name: "Orders" })
                })
        },
    },
}
</script>
<style scoped>
span {
    float: right;
}
.loader {
    width: 100px;
    height: 100px;
}
</style>
