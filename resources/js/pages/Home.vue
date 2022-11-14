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
    </div>
</template>
<script>
export default {
    data() {
        return {
            categories: [],
            loading: true,
        }
    },
    created() {
        axios
            .get("/api/categories/get")
            .then((response) => {
                this.categories = response.data
            })
            .catch(() => {})
            .finally(() => {
                this.loading = false
            })
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
