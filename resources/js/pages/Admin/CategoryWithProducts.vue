<template>
    <div>
        <show-errors v-if="errors" :errors="errors" />
        <div class="buttonGroups mb-2">
            <router-link
                :to="{ name: 'AddProduct', params: { categoryID: categoryId } }"
            >
                <button class="btn btn-success itemButtonGroups">
                    Добавить продукт в категорию {{ categoryName }}
                </button>
            </router-link>
            <button-mass-delete
                class="itemButtonGroups"
                :validation-form="validationForm"
                :count-items="countProducts"
                @acceptedDelete="deleteProducts()"
            ></button-mass-delete>
            <button-back class="itemButtonGroups" />
        </div>
        <h2 class="text-center">
            Продукты категории
            <strong
                ><em>{{ categoryName }}</em></strong
            >
        </h2>
        <div v-if="loading" class="text-center">
            <span>
                <loading />
            </span>
        </div>
        <div>
            <table class="table table-bordered text-center">
                <thead>
                    <th></th>
                    <th>Имя</th>
                    <th>Описание</th>
                    <th>Цена</th>
                    <th>Изображение</th>
                    <th>Дата создания</th>
                    <th>Последнее изменение</th>
                </thead>
                <tbody v-if="products.length != 0">
                    <tr v-for="product in products" :key="product.id">
                        <td>
                            <input
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
                        <td>
                            <img
                                class="avatar"
                                :src="
                                    `/storage/img/products/` + product.picture
                                "
                                :alt="product.name"
                            />
                        </td>
                        <td>{{ product.created_at }}</td>
                        <td>{{ product.updated_at }}</td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="7"><em>Продукты отсутcтвуют</em><br /></td>
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
            products: [],
            categoryId: 0,
            categoryName: "",
            checkedIdforDelete: [],
            errors: null,
        }
    },
    computed: {
        countProducts() {
            return Object.keys(this.checkedIdforDelete).length
        },
        validationForm() {
            return this.checkedIdforDelete.length !== 0
        },
    },
    created() {
        axios
            .get(
                `/api/admin/categories/${this.$route.params.id}/getProductsCategory`
            )
            .then((response) => {
                this.products = response.data.products
                this.categoryName = response.data.categoryName
                this.categoryId = response.data.categoryId
            })
            .finally(() => {
                this.loading = false
            })
    },
    methods: {
        deleteProducts() {
            const params = {
                idProductsDelete: this.checkedIdforDelete,
                categoryId: this.categoryId,
            }
            axios
                .post("/api/admin/products/delProducts", params)
                .then((response) => {
                    this.$swal({
                        title: "Удаление прошло успешно",
                        icon: "info",
                    }).then(() => {})
                    this.checkedIdforDelete = []
                    this.products = response.data.products
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
    },
}
</script>

<style scoped>
.buttonGroups {
    display: flex;
    flex-direction: row;
}
.itemButtonGroups {
    margin: 3px;
}
</style>
