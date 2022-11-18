<template>
    <div>
        <div class="row">
            <div class="text-center" v-if="loading">
                <loading-component />
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
                        <router-link
                            :to="{
                                name: 'ShowProduct',
                                params: {
                                    id: product.id,
                                    name: product.name,
                                },
                            }"
                        >
                            <img
                                title="Подробнее"
                                style="width: 100%; height: 300px"
                                :src="
                                    '/storage/img/products/' + product.picture
                                "
                                class="card-img-top"
                                :alt="product.name"
                        /></router-link>
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ product.name }}
                            </h5>
                            <button-change-cart-component
                                :id="product.id"
                                :quantity="product.quantity"
                                @newQuantity="
                                    changeQuantity(product.id, $event)
                                "
                                ><div class="card-counter">
                                    {{ product.quantity }}
                                </div>
                            </button-change-cart-component>
                            <p class="card-price mt-1 text-center">
                                {{ product.price }} руб.
                            </p>
                        </div>
                    </div>
                </div>
                <pagination-component
                    class="justify-content-center"
                    :elems="products"
                    :links="links"
                    :current="currentPage"
                    @changePage="getProducts"
                />
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
            routeId: this.$route.params.id,
            links: [],
            page: this.$route.query.page,
            currentPage: null,
        }
    },
    mounted() {
        this.getProducts(this.page)
    },
    methods: {
        getProducts(page = 1) {
            if (page != this.$route.query.page) {
                this.$router.push({
                    name: "ShowCategory",
                    query: { page },
                    params: { id: this.routeId },
                })
            }
            this.loading = true
            axios
                .get(`/api/categories/${this.routeId}/getProducts`, {
                    params: { page },
                })
                .then((response) => {
                    this.products = response.data.data
                    this.links = response.data.links.slice(1, -1)
                    this.currentPage = response.data.current_page
                })
                .catch(() => {})
                .finally(() => {
                    this.loading = false
                })
        },
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
