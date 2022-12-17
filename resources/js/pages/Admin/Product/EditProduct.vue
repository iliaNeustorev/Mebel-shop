<template>
    <div>
        <button-back-component class="mb-2" />
        <div class="text-center" v-if="loading"><loading-component /></div>
        <div v-else>
            <form>
                <form-input-component
                    label="Имя продукта"
                    name="name"
                    :form="product"
                    placeholder="Введите имя продукта"
                    :old-value="oldData.name"
                    @validation-filed="fieldValid"
                />
                <form-textarea-component
                    label="Описание продукта"
                    name="description"
                    :form="product"
                    placeholder="Введите описание продукта"
                    :old-value="oldData.description"
                    @validation-filed="fieldValid"
                />
                <form-input-component
                    label="Цена"
                    name="price"
                    :form="product"
                    placeholder="Введите цену"
                    :old-value="oldData.price"
                    @validation-filed="fieldValid"
                />
                <img
                    class="avatar"
                    :src="`/storage/img/products/` + oldData.picture"
                />
                <form-file-component
                    label=" Выберите картинку для продукта"
                    name="picture"
                    :check-file="checkFile"
                    @change-check-file="changedPicture"
                    :form="product"
                />
                <form-select-component
                    label="Категория"
                    name="category_id"
                    :form="product"
                    @validation-filed="fieldValid"
                    :old-value="oldData.category_id"
                    :options="categories"
                />
            </form>
            <button-send-form-component
                :validation-form="validationForm"
                name-button-accepted="Принять изменения"
                name-button-denied="Внесите изменения"
                @accepted-form="sendForm()"
            >
                <template v-slot:mainModal>
                    <div class="container">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <td>Имя продукта</td>
                                    <td>Описание</td>
                                    <td>Цена</td>
                                    <td>Категория</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ product.name }}
                                    </td>
                                    <td>
                                        {{ product.description }}
                                    </td>
                                    <td>
                                        {{ product.price }}
                                    </td>
                                    <td>
                                        {{ categories[product.category_id] }}
                                    </td>
                                </tr>
                                <tr v-if="checkFile">
                                    <td colspan="4">
                                        <b>Изменение картинки</b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h3>Старые значения</h3>
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <td>Имя продукта</td>
                                    <td>Описание</td>
                                    <td>Цена</td>
                                    <td>Категория</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ oldData.name }}
                                    </td>
                                    <td>
                                        {{ oldData.description }}
                                    </td>
                                    <td>
                                        {{ oldData.price }}
                                    </td>
                                    <td>
                                        {{ categories[oldData.category_id] }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </template></button-send-form-component
            >
        </div>
    </div>
</template>

<script>
import Form from "vform"
import loadCategories from "../../../mixins/load-categories.js"
import editElementBd from "../../../mixins/edit-element-Bd.js"
export default {
    mixins: [loadCategories, editElementBd],
    data() {
        return {
            oldData: {
                name: "",
                price: "",
                description: "",
                category_id: "",
                picture: null,
            },
            product: Form.make({
                name: "",
                description: "",
                price: "",
                category_id: "",
                picture: null,
            }),
            validation: {
                name: false,
                description: false,
                price: false,
                category_id: false,
            },
            loading: true,
        }
    },
    created() {
        axios
            .get(`/api/admin/products/product/${this.$route.params.id}`)
            .then((response) => {
                this.oldData = response.data
                Object.assign(this.product, response.data)
            })
            .catch(() => {})
            .finally(() => {
                this.loading = false
            })
    },
    methods: {
        sendForm() {
            if (this.validationForm) {
                axios
                this.product
                    .post("/api/admin/products/updProduct")
                    .then(() => {
                        this.$swal({
                            icon: "success",
                            title: "Изменение прошло успешно",
                        })
                    })
                    .catch(() => {})
                    .finally(() => {
                        this.$router.go(-1)
                    })
            } else {
                this.$swal({
                    icon: "error",
                    title: "Недостаточно данных для отправки",
                })
            }
        },
    },
}
</script>
