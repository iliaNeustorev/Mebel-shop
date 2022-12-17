<template>
    <ul class="pagination">
        <li class="page-item">
            <button
                :disabled="current == 1"
                class="page-link"
                tabindex="-1"
                aria-disabled="true"
                @click="getProducts(current - 1)"
            >
                Предидущая
            </button>
        </li>
        <li
            v-for="(link, i) in links"
            :key="i"
            class="page-item"
            :class="{ active: link.active }"
        >
            <button
                :disabled="link.active"
                class="page-link"
                @click="getProducts(link.label)"
            >
                {{ link.label }}
            </button>
        </li>
        <li class="page-item">
            <button
                :disabled="current == links.length"
                @click="getProducts(current + 1)"
                class="page-link"
            >
                Следущая
            </button>
        </li>
    </ul>
</template>

<script>
export default {
    props: {
        elems: { Type: Array, required: true },
        links: { Type: Array, required: true },
        current: { Type: Number, required: true },
    },
    methods: {
        getProducts(page) {
            if (page == this.current) {
                return false
            }
            this.$emit("change-page", page)
        },
    },
}
</script>
