<template>
    <div>
        <show-errors-component v-if="errors" :errors="errors" />
        <div>
            <div class="d-grid gap-2 d-md-block mb-3">
                <button
                    @click="startExport()"
                    class="btn btn-primary btn-xl mb-2"
                >
                    Выгрузить список категорий
                </button>

                <button
                    @click="showFormImport = !showFormImport"
                    class="btn btn-link btn-xl mb-2"
                >
                    Загрузить список категорий
                </button>
                <router-link :to="{ name: 'AddNewCategory' }">
                    <button class="btn btn-success">
                        Добавить категорию
                    </button></router-link
                >
                <button-back-component />
                <template v-if="showFormImport"
                    ><import-component
                        @cancelDownload="showFormImport = false"
                        name="Категории"
                        uri="/api/admin/importCategories"
                        channel="categories-import-finish"
                        event="ImportCategoriesCounter"
                    />
                </template>
                <template v-if="checkExport"
                    ><export-component
                        :start="true"
                        name="Категории"
                        uri="/api/admin/exportCategories"
                        channel="categories-export-finish"
                        event="ExportCategoriesCounter"
                /></template>
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
                        <loading-component />
                    </td>
                </tr>
                <tbody v-else>
                    <tr v-for="category in categories" :key="category.id">
                        <td>
                            <router-link
                                :to="{
                                    name: 'ShowOneCategoryWithProducts',
                                    params: { id: category.id },
                                }"
                                >{{ category.name }}</router-link
                            >
                        </td>
                        <td>
                            <router-link
                                :to="{
                                    name: 'EditCategory',
                                    params: {
                                        id: category.id,
                                        name: category.name,
                                    },
                                }"
                            >
                                <button class="btn btn-success">
                                    Редактировать
                                </button>
                            </router-link>
                            <button
                                v-if="category.products.length == 0"
                                class="btn btn-danger"
                                @click="deleteCategory(category.id)"
                            >
                                Удалить
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
            errors: null,
            showFormImport: false,
            checkExport: false,
        }
    },
    methods: {
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
        startExport() {
            this.checkExport = !this.checkExport
        },
    },
    created() {
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
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
    },
}
</script>
