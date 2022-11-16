<template>
    <div class="d-flex">
        <input
            class="form-control me-2"
            type="search"
            v-model="search"
            placeholder="Поиск по сайту"
            aria-label="Search"
        />
        <button
            :disabled="!validation"
            @click.prevent="beginSearch()"
            class="btn btn-outline-success"
        >
            Поиск
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            search: "",
        }
    },
    computed: {
        validation() {
            return this.search.length > 0
        },
    },
    methods: {
        beginSearch() {
            if (this.validation) {
                this.$emit("search", this.search)
            }
        },
    },
}
</script>
