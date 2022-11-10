<template>
    <div>
        <div class="row">
            <div class="text-center" v-if="loading">
                <loading />
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
                            <button-change-cart
                                :id="product.id"
                                :quantity="product.quantity"
                                @newQuantity="
                                    changeQuantity(product.id, $event)
                                "
                                ><div class="card-counter">
                                    {{ product.quantity }}
                                </div></button-change-cart
                            >
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
    created() {
        axios
            .get(`/api/categories/${this.$route.params.id}/getProducts`)
            .then((response) => {
                this.products = response.data
            })
            .catch(() => {})
            .finally(() => {
                this.loading = false
            })
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
