<template>
    <div>
        <div>
            <div class="d-grid gap-2 d-md-block text-center mb-3">
                <button
                    @click="exportCategories()"
                    class="btn btn-primary btn-xl mb-2"
                >
                    Выгрузить список категорий
                </button>

                <button type="submit" class="btn btn-link btn-xl mb-2">
                    Загрузить список категорий
                </button>
                <router-link to="/admin/AddNewCategory">
                    <button class="btn btn-success">
                        Добавить категорию
                    </button></router-link
                >
                <div v-if="processing" class="alert alert-warning text-center">
                    Категории выгружаются
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <progress-bar-component
                        v-if="processing"
                        channel="counter"
                        event="ExportCategoriesCounter"
                    />
                </div>

                <div
                    v-else-if="exportFinished"
                    class="alert alert-success text-center"
                >
                    Категории выгружены <a :href="downloadLink">(скачать)</a>
                </div>
            </div>
        </div>

        <div>
            <hr />
            <h2 class="text-center">Таблица со всеми категориями</h2>

            <table class="table table-bordered mt-2 text-center">
                <thead>
                    <th>Имя</th>
                    <th>Редактировать</th>
                    <th>Описание</th>
                    <th>Изображение</th>
                    <th>Кол-во товара</th>
                    <th>Дата создания</th>
                    <th>Последнее изменение</th>
                </thead>
                <tr v-if="loading" class="text-center">
                    <td colspan="7">
                        <img
                            class="loader text-center"
                            src="/storage/img/loaders/loader.gif"
                        />
                    </td>
                </tr>
                <tbody v-else>
                    <tr v-for="category in categories" :key="category.id">
                        <td>
                            <router-link
                                :to="
                                    '/admin/category/' +
                                    category.id +
                                    '/products'
                                "
                                >{{ category.name }}</router-link
                            >
                        </td>
                        <td>
                            <button
                                class="btn btn-success"
                                @click="showEditCategoryForm(category.id)"
                            >
                                Edit
                            </button>
                            <button
                                v-if="category.products.length == 0"
                                class="btn btn-danger"
                                @click="deleteCategory(category.id)"
                            >
                                Delete
                            </button>
                        </td>
                        <td>{{ category.description }}</td>
                        <td>
                            <img
                                class="avatar"
                                :src="
                                    `/storage/img/categories/` +
                                    category.picture
                                "
                            />
                        </td>
                        <td>{{ category.products.length }}</td>
                        <td>{{ category.created_at }}</td>
                        <td>{{ category.updated_at }}</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="7"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            categories: [],
            processing: false,
            exportFinished: false,
            downloadLink: null,
        }
    },
    methods: {
        exportCategories() {
            this.processing = true
            axios
                .post("/api/admin/exportCategories")
                .then(() => {})
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
                .finally(() => {
                    this.exportFinished = false
                })
        },
        showEditCategoryForm(category) {
            this.$router.push("/admin/editCategory")
            axios
                .get(`/api/admin/categories/category/${category}/edit`)
                .then((response) => {
                    this.$root.$emit("eventing", response.data)
                })
        },
        deleteCategory(categoryId) {
            axios
                .delete(`/api/admin/categories/category/${categoryId}`)
                .then((response) => {
                    this.categories = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
        axios
            .get("/api/admin/categories/")
            .then((response) => {
                this.categories = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
            .finally(() => {
                this.loading = false
            })

        Echo.channel("general").listen(".categories-export-finish", (e) => {
            this.processing = false
            this.exportFinished = true
            this.downloadLink = `/storage/${e.message}`
        })
    },
    destroyed() {
        Echo.channel("general").stopListening(".categories-export-finish")
    },
}
</script>

<style scoped>
.avatar {
    height: 200px;
    width: 200px;
}
.loader {
    width: 30%;
    height: 30%;
}
</style>
