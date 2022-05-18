<template>
    <div>
        <div v-if="!orders.length"><h2>У вас еще нет заказов</h2></div>
        <div v-else v-for="order in orders" :key="order.order.order_id">
            <p>
                <a
                    class="btn btn-link"
                    data-bs-toggle="collapse"
                    :href="`#collapseExample` + order.order.order_id"
                    role="button"
                    aria-expanded="false"
                    aria-controls="collapseExample"
                >
                    #{{ order.order.order_id }}
                </a>
                {{ order.order.data }}
            </p>
            <div
                class="collapse mb-4"
                :id="`collapseExample` + order.order.order_id"
            >
                <div class="card card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Наименование:</th>
                                <th>Количество:</th>
                                <th>Цена:</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(product, key) in order.products"
                                :key="key"
                            >
                                <td>{{ product.name }}</td>
                                <td>{{ product.quantity }}</td>
                                <td>{{ product.price }}</td>
                                <td>
                                    {{ product.quantity * product.price }}
                                </td>
                            </tr>
                            <td class="text-end" colspan="4">
                                <span
                                    >Общая сумма заказа:<b>{{
                                        order.order.sum
                                    }}</b></span
                                >
                            </td>
                        </tbody>
                    </table>

                    <button
                        class="btn btn-info repeatOrderBtn"
                        @click="repeatOrder(order.order.order_id)"
                    >
                        Повторить заказ
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            orders: [],
        }
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
        axios
            .get("/api/home/orders/")
            .then((response) => {
                this.orders = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
            .finally(() => {})
    },
    methods: {
        repeatOrder(OrderId) {
            axios
                .get(`/api/home/orders/repeatOrder${OrderId}`)
                .then((response) => {
                    this.$swal({
                        title: "Заказ в корзине",
                        icon: "success",
                    }).then(() => {})
                    this.$router.push("/basket")
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
.repeatOrderBtn {
    text-transform: uppercase;
    margin-left: auto;
    color: #534d1a;
    background-color: wheat;
}
</style>
