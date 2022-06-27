<template>
    <div>
        <div class="d-grid gap-2 d-md-block mb-3">
            <button
                @click="exportProducts()"
                class="btn btn-primary btn-xl mb-2"
            >
                Выгрузить список продуктов
            </button>
            <button type="submit" class="btn btn-link btn-xl mb-2">
                Загрузить список продуктов
            </button>
            <button-mass-delete
                class="btn btn-link btn-xl mb-2"
                :validation-form="validationForm"
                :count-items="countProducts"
                @acceptedDelete="deleteProducts()"
            ></button-mass-delete>
            <button
                class="btn btn-success btn-xl mb-2"
                @click="showForm = !showForm"
            >
                {{ titleButton }}
            </button>
            <button @click="$router.go(-1)" class="btn btn-success mb-2">
                Назад
            </button>
            <div v-if="processing" class="alert alert-warning text-center">
                Продукты выгружаются
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div
                v-else-if="exportFinished"
                class="alert alert-success text-center"
            >
                Продукты выгружены <a :href="downloadLink">(скачать)</a>
            </div>
        </div>
        <hr />
        <transition name="slide">
            <addProduct-component v-show="showForm"></addProduct-component>
        </transition>
        <div v-show="!showForm">
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
                        <img
                            class="loader text-center"
                            src="/storage/img/loaders/loader.gif"
                        />
                    </td>
                </tr>
                <tbody v-else>
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
                        <td></td>
                        <a href="" title="Редактировать продукт"
                            ><img
                                class="avatar"
                                :src="
                                    `/storage/img/products/` + product.picture
                                "
                                :alt="product.name"
                        /></a>
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
export default {
    data() {
        return {
            loading: true,
            processing: false,
            exportFinished: false,
            downloadLink: null,
            products: [],
            checkedIdforDelete: [],
            showForm: false,
        }
    },
    computed: {
        countProducts() {
            return Object.keys(this.checkedIdforDelete).length
        },
        validationForm() {
            return this.checkedIdforDelete.length !== 0
        },
        titleButton() {
            return this.showForm
                ? "К списку продуктов"
                : "Добавить продукт в категорию"
        },
    },
    methods: {
        exportProducts() {
            this.processing = true
            axios
                .post("/api/admin/exportProducts")
                .then(() => {})
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
                .finally(() => {
                    this.exportFinished = false
                })
        },
        deleteProducts() {
            const params = {
                idProductsDelete: this.checkedIdforDelete,
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

        Echo.channel("general").listen(".products-export-finish", (e) => {
            this.processing = false
            this.exportFinished = true
            this.downloadLink = `/storage/${e.message}`
        })
    },
    destroyed() {
        Echo.channel("general").stopListening(".products-export-finish")
    },
}
</script>

<style scoped></style>
