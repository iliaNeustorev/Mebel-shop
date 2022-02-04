<template>
    <li v-if="quantity" class="nav-item">
        <a class="nav-link" :href="routeBasket"> Корзина ({{ quantity }}) </a>
    </li>
</template>

<script>
export default {
    props: ["routeBasket"],
    computed: {
        quantity() {
            return this.$store.state.basketProductsQuantity
        },
    },
    mounted() {
        this.$store.dispatch("getBasketProductsQuantity")
    },
}
</script>
