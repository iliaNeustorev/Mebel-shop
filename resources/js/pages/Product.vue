<template>
    <div class="text-center" v-if="loading">
        <loading-component />
    </div>
    <div v-else class="card mb-3 p-2" style="max-width: 740px">
        <div class="row g-0">
            <div class="col-md-4 text-center">
                <img
                    style="width: 100%; height: 300px"
                    :src="'/storage/img/products/' + product.picture"
                    :alt="product.name"
                    class="img-fluid rounded-bottom border border-2 border-success"
                />
                <p class="card-text mt-2">
                    <small class="text-muted">
                        <button-change-cart-component
                            :id="product.id"
                            :quantity="product.quantity"
                            @new-quantity="changeQuantity"
                            ><span class="card-counter">
                                {{ product.quantity }}
                            </span>
                        </button-change-cart-component></small
                    >
                    <span class="card-title text-center"
                        >Цена: {{ product.price }}</span
                    >
                </p>
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">
                        <p>
                            <span class="title"> Категория:</span>
                            <router-link
                                :to="{
                                    name: 'ShowCategory',
                                    params: { id: product.category_id },
                                }"
                                >{{ product.category.name }}</router-link
                            >
                        </p>
                        <span class="title">Название:</span> {{ product.name }}
                    </h5>
                    <p class="card-text">
                        <span class="title">Описание:</span>
                        {{ product.description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            product: {},
        }
    },
    created() {
        axios
            .get(`/api/categories/product/${this.$route.params.id}`)
            .then((response) => {
                this.product = response.data[0]
            })
            .catch(() => {})
            .finally(() => {
                this.loading = false
            })
    },
    methods: {
        changeQuantity(e) {
            this.product.quantity = e.quantityProduct
            this.$store.dispatch(
                "changeBasketProductsQuantity",
                e.allQuantityCart
            )
        },
    },
}
</script>

<style scoped>
.title {
    color: #978b19;
}
.card-basket-buttons {
    display: flex;
    justify-content: space-between;
}

.card-counter {
    line-height: 34px;
}
</style>
