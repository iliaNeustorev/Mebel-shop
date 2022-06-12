<template>
    <div>
        <span class="buttonGroups mb-2">
            <button
                @click="showForm = !showForm"
                class="btn btn-success itemButtonGroups"
            >
                {{ titleButton }}
            </button>

            <button-send-form
                :hidden="showForm"
                class="itemButtonGroups"
                :validation-form="validationForm"
                name-button-accepted="Удалить выбраное"
                name-button-denied="Выберите продукты для удаления"
                class-button-denied="btn-warning"
                class-button-accepted="btn-danger"
                @acceptedForm="deleteProducts()"
                ><template v-slot:mainModal
                    ><p class="container">
                        Удалить в количестве {{ sizeM }}
                    </p></template
                ></button-send-form
            >
            <button
                @click="$router.go(-1)"
                class="btn btn-success itemButtonGroups"
            >
                Назад
            </button>
        </span>
        <h2 class="text-center">
            Продукты категории
            <strong
                ><em>{{ categoryName }}</em></strong
            >
        </h2>
        <transition name="slide">
            <addProduct-component
                :category-id="categoryId"
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
                        <td>
                            <router-link
                                :to="{
                                    name: 'EditProduct',
                                    params: {
                                        id: product.id,
                                        name: product.name,
                                        description: product.description,
                                        price: product.price,
                                        picture: product.picture,
                                        categoryID: product.category_id,
                                    },
                                }"
                                >{{ product.name }}</router-link
                            >
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
            showForm: false,
            loading: true,
            products: [],
            categoryId: 0,
            categoryName: "",
            checkedIdforDelete: [],
        }
    },
    computed: {
        sizeM() {
            return Object.keys(this.checkedIdforDelete).length
        },
        titleButton() {
            return this.showForm
                ? "К списку продуктов"
                : "Добавить продукт в категорию"
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
