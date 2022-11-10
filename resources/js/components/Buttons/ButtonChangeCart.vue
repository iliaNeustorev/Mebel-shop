<template>
    <div class="card-basket-buttons">
        <button @click="basket(id, 'add')" class="btn btn-success">+</button>
        <slot></slot>
        <button
            @click="basket(id, 'remove')"
            :disabled="quantity == 0"
            class="btn btn-danger"
        >
            -
        </button>
    </div>
</template>

<script>
export default {
    props: {
        id: { type: Number, required: true },
        quantity: { type: Number, required: true },
    },
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
                    this.$emit("newQuantity", {
                        quantityProduct: data.quantity,
                        allQuantityCart: data.basketProductsQuantity,
                    })
                })
        },
    },
}
</script>
