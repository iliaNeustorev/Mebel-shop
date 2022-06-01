<template>
    <div>
        <div class="row">
            <div class="text-center" v-if="loading">
                <img src="/storage/img/loaders/loader.gif" />
            </div>
            <div class="row" v-else>
                <div class="text-center" v-if="!products.length">
                    <em><h2>Товары пока отсутствуют</h2></em>
                </div>
                <div
                    v-else
                    v-for="product in products"
                    :key="product.id"
                    class="col-3 mb-4"
                >
                    <div class="card" style="width: 18rem">
                        <img
                            style="width: 100%; height: 300px"
                            :src="'/storage/img/products/' + product.picture"
                            class="card-img-top"
                            :alt="product.name"
                        />
                        <div class="card-body">
                            <h5 class="card-title">{{ product.name }}</h5>
                            <p class="card-text">
                                {{ product.description }}...
                            </p>
                            <div class="card-basket-buttons">
                                <button
                                    @click="basket(product.id, 'add')"
                                    type="submit"
                                    class="btn btn-success"
                                >
                                    +
                                </button>
                                <div class="card-counter">
                                    {{ product.quantity }}
                                </div>
                                <button
                                    @click="basket(product.id, 'remove')"
                                    :disabled="product.quantity == 0"
                                    type="submit"
                                    class="btn btn-danger"
                                >
                                    -
                                </button>
                            </div>
                            <p class="card-price mt-1 text-center">
                                {{ product.price }} руб.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            products: [],
            loading: true,
        }
    },
    computed: {},
    watch: {},
    methods: {
        basket(productId, method, quantity) {
            if (method == "remove" && quantity == 0) {
                alert("!!!!!")
            }
            const params = {
                id: productId,
            }
            axios
                .post(`/api/basket/product/${method}`, params)
                .then(({ data }) => {
                    const idx = this.products.findIndex((product) => {
                        return product.id == productId
                    })
                    this.products[idx].quantity = data.quantity
                    this.$store.dispatch(
                        "changeBasketProductsQuantity",
                        data.basketProductsQuantity
                    )
                })
        },
    },
    mounted() {
        axios
            .get(`/api/categories/${this.$route.params.id}/getProducts`)
            .then((response) => {
                this.products = response.data
            })
            .catch((error) => {})
            .finally(() => {
                this.loading = false
            })
    },
}
</script>
<style scoped>
.card-basket-buttons {
    display: flex;
    justify-content: space-between;
}

.card-counter {
    line-height: 34px;
}

.card-price {
    font-size: 130%;
    border-bottom: 2px solid gray;
}

.card-text {
    height: 70px;
}

.card-title {
    height: 45px;
    text-align: center;
    font-weight: bold;
}
</style>
