<template>
    <div>
        <h2>
            Продукты категории
            <strong
                ><em>{{ categoryName }}</em></strong
            >
            <button @click="showForm = !showForm" class="btn btn-success">
                {{ titleButton }}
            </button>

            <button @click="$router.go(-1)" class="btn btn-success">
                Назад
            </button>
        </h2>
        <transition name="slide">
            <addProduct-component
                :category-id="categoryId"
                :category-name="categoryName"
                v-show="showForm"
            ></addProduct-component>
        </transition>

        <div v-if="loading" class="text-center">
            <span>
                <img
                    class="loader text-center"
                    src="/storage/img/loaders/loader.gif"
                />
            </span>
        </div>

        <div v-else-if="!showForm">
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
                        <td>{{ product.name }}</td>
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
                    <tr>
                        <td colspan="7" class="text-start">
                            <button
                                class="btn btn-danger"
                                @click="deleteProducts()"
                            >
                                Удалить выбраное
                            </button>
                        </td>
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
            showForm: false,
            loading: true,
            products: [],
            categoryName: "",
            categoryId: 0,
            checkedIdforDelete: [],
        }
    },
    computed: {
        titleButton() {
            return this.showForm
                ? "К списку продуктов"
                : "Добавить продукт в категорию"
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
    mounted() {},
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
                    this.products = response.data.products
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
    },
}
</script>

<style scoped></style>
