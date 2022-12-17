<template>
    <div>
        <h1 class="mb-3">Список категорий</h1>
        <div class="text-center" v-if="loading">
            <loading-component />
        </div>
        <div v-else class="row">
            <div
                v-for="category in categories"
                :key="category.id"
                class="col-3"
            >
                <router-link
                    :to="{ name: 'ShowCategory', params: { id: category.id } }"
                >
                    <div class="card mb-4" style="width: 18rem">
                        <img
                            style="width: 100%; height: 300px"
                            :src="`storage/img/categories/` + category.picture"
                            class="card-img-top"
                        />
                        <div class="card-body">
                            <h5 class="card-title text-center">
                                {{ category.name }}
                            </h5>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>
        <pagination-component
            class="justify-content-center"
            :elems="categories"
            :links="links"
            :current="currentPage"
            @change-page="getCategories"
        />
    </div>
</template>
<script>
export default {
    data() {
        return {
            categories: [],
            loading: true,
            links: [],
            page: this.$route.query.page,
            currentPage: null,
        }
    },
    methods: {
        getCategories(page = 1) {
            if (page != this.$route.query.page) {
                this.$router.push({ name: "Home", query: { page } })
            }
            this.loading = true
            axios
                .get(`/api/categories/get`, { params: { page } })
                .then((response) => {
                    this.categories = response.data.data
                    this.links = response.data.links.slice(1, -1)
                    this.currentPage = response.data.current_page
                })
                .catch(() => {})
                .finally(() => {
                    this.loading = false
                })
        },
    },
    created() {
        this.getCategories(this.page)
    },
}
</script>
<style scoped>
a {
    text-decoration: none;
    color: rgb(17, 117, 25);
}

a:hover {
    color: rgb(153, 161, 29);
}

a:after {
    color: rgb(153, 161, 29);
}

.card:hover {
    box-shadow: 0.4em 0.4em 5px rgb(122 122 122 / 50%);
}
h1 {
    text-align: center;
}
</style>
