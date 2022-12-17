<template>
    <div>
        <show-errors-component v-if="errors" :errors="errors">
            <template v-if="errors.email">
                Ссылка на
                <router-link :to="{ name: 'login' }">Вход</router-link>
            </template></show-errors-component
        >
        <table v-if="products.length" class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th>Добавить 1 товар/убрать 1 товар</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td>{{ product.title }}</td>
                    <td>{{ product.price }}</td>
                    <td>{{ product.quantity }}</td>
                    <td>
                        {{ product.quantity * product.price }}
                    </td>
                    <td>
                        <button-change-cart-component
                            :id="product.id"
                            :quantity="product.quantity"
                            @new-quantity="changeQuantity(product.id, $event)"
                        />
                    </td>
                </tr>
                <tr v-if="products">
                    <td colspan="5">
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
                <router-link :to="{ name: 'Home' }">
                    Перейти в каталог</router-link
                ></em
            >
        </div>
        <form @submit.prevent="submit()" v-if="products">
            <label>Имя</label>
            <input
                :disabled="validationForm"
                v-model.trim.lazy="userData.name"
                class="form-control mb-2"
            />
            <label>Адрес</label>
            <input
                :disabled="validationForm"
                v-model.trim.lazy="userData.address"
                class="form-control mb-2"
            />
            <label>Почта</label>
            <input
                :disabled="validationForm"
                v-model.trim.lazy="userData.email"
                type="email"
                class="form-control mb-2"
            />
            <button :disabled="!validationForm" class="btn btn-success">
                <span
                    v-if="processing"
                    class="spinner-border text-success"
                    role="status"
                >
                    <span class="visually-hidden">Загрузка</span>
                </span>
                <span v-else>Оформить заказ</span>
            </button>
        </form>
    </div>
</template>
<script>
import Form from "vform"
export default {
    data() {
        return {
            processing: false,
            errors: null,
            products: [],
            userData: Form.make({
                email: "",
                address: "",
                name: "",
            }),
        }
    },
    computed: {
        validationForm() {
            return (
                this.userData.email.length > 0 &&
                this.userData.email.length > 0 &&
                this.userData.address.length > 0 &&
                this.sumOrder != 0
            )
        },
        sumOrder() {
            return this.products.reduce((sum, elem) => {
                return (sum += elem.quantity * elem.price)
            }, 0)
        },
    },
    created() {
        axios.get("/api/basket/").then((response) => {
            if (response.data.user.name != null) {
                Object.assign(this.userData, response.data.user)
            }
            this.products = response.data.products
        })
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
    },
    methods: {
        changeQuantity(id, e) {
            let idx = this.products.findIndex((product) => {
                return product.id == id
            })
            this.products[idx].quantity = e.quantityProduct
            this.$store.dispatch(
                "changeBasketProductsQuantity",
                e.allQuantityCart
            )
        },
        submit() {
            if (this.validationForm) {
                this.processing = true
                this.errors = null
                axios
                this.userData
                    .post("/api/basket/create_order")
                    .then((response) => {
                        this.$swal({
                            icon: "success",
                            title: "Заказ оформлен!",
                        }).then(() => {
                            this.$store.dispatch("getBasketProductsQuantity")
                            this.$router.push({ name: "Orders" })
                        })
                        this.$store.dispatch(
                            "getChekOrders",
                            response.data.orders
                        )
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors
                    })
                    .finally(() => {
                        this.processing = false
                    })
            }
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
