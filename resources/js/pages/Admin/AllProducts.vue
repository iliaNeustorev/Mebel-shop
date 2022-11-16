<template>
    <div>
        <show-errors-component v-if="errors" :errors="errors" />
        <div class="mb-3">
            <button @click="startExport()" class="btn btn-primary btn-xl mb-2">
                Выгрузить список продуктов
            </button>
            <button
                @click="showFormImport = !showFormImport"
                type="submit"
                class="btn btn-link btn-xl mb-2"
            >
                Загрузить список продуктов
            </button>
            <button-mass-delete-component
                class="btn btn-link btn-xl mb-2"
                :validation-form="validationForm"
                :count-items="countProducts"
                @clearDeleted="clearCheckedDelete()"
                @acceptedDelete="deleteProducts()"
            >
            </button-mass-delete-component>
            <router-link to="AddProduct">
                <button class="btn btn-success btn-xl mb-2">
                    Добавить продукт
                </button>
            </router-link>
            <button-back-component class="mb-2" />
        </div>
        <template v-if="showFormImport"
            ><import-component
                @cancelDownload="showFormImport = false"
                name="Продукты"
                uri="/api/admin/importProducts"
                channel="products-import-finish"
                event="ImportProductsCounter"
            />
        </template>
        <template v-if="checkExport"
            ><export-component
                name="Продукты"
                channel="products-export-finish"
                uri="/api/admin/exportProducts"
                event="ExportProductsCounter"
                :start="true"
        /></template>
        <hr />
        <div>
            <h2 class="text-center">Таблица со всеми продуктами</h2>
            <table class="table table-bordered mt-2 text-center">
                <thead>
                    <th></th>
                    <th>Имя</th>
                    <th>Описание</th>
                    <th>Цена</th>
                    <th>Категория</th>
                    <th>Изображение</th>
                    <th>Дата создания</th>
                    <th>Последнее изменение</th>
                </thead>
                <tr v-if="loading" class="text-center">
                    <td colspan="7">
                        <loading-component />
                    </td>
                </tr>
                <tbody v-else>
                    <tr v-for="product in products" :key="product.id">
                        <td>
                            <input
                                title="выбрать для удаления"
                                type="checkbox"
                                :value="product.id"
                                v-model="checkedIdforDelete"
                            />
                        </td>
                        <td>
                            <router-link
                                :to="{
                                    name: 'EditProduct',
                                    params: {
                                        id: product.id,
                                    },
                                }"
                                >{{ product.name }}
                            </router-link>
                        </td>
                        <td>{{ product.description }}</td>
                        <td>{{ product.price }}</td>
                        <td>{{ categories[product.category_id] }}</td>
                        <img
                            class="avatar"
                            :src="`/storage/img/products/` + product.picture"
                            :alt="product.name"
                        />
                        <td>{{ product.created_at }}</td>
                        <td>{{ product.updated_at }}</td>
                    </tr>
                    <tr>
                        <td class="text-start" colspan="8"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import loadCategories from "../../mixins/load-categories.js"
export default {
    mixins: [loadCategories],
    data() {
        return {
            loading: true,
            products: [],
            checkedIdforDelete: [],
            errors: null,
            checkExport: false,
            showFormImport: false,
        }
    },
    computed: {
        countProducts() {
            return Object.keys(this.checkedIdforDelete).length
        },
        validationForm() {
            return this.checkedIdforDelete.length != 0
        },
    },
    methods: {
        deleteProducts() {
            const params = {
                idProductsDelete: this.checkedIdforDelete,
            }
            axios
                .post("/api/admin/products/delProducts", params)
                .then((response) => {
                    this.$swal({
                        icon: "info",
                        title: "Удаление прошло успешно",
                    })
                    this.clearCheckedDelete()
                    this.products = response.data.products
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
        startExport() {
            this.checkExport = true
        },
        clearCheckedDelete() {
            this.checkedIdforDelete = []
        },
    },
    created() {
        axios.get("/api/admin/products").then((response) => {
            this.products = response.data
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
