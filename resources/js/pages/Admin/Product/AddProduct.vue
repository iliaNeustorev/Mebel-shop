<template>
    <div>
        <button-back-component class="mb-2" />
        <form>
            <form-input-component
                ref="first"
                label="Имя продукта"
                name="name"
                :form="product"
                placeholder="Введите имя продукта"
                @validation-filed="fieldValid"
            />
            <form-textarea-component
                label="Описание продукта"
                name="description"
                :form="product"
                placeholder="Введите описание продукта"
                @validation-filed="fieldValid"
            />
            <form-input-component
                label="Цена"
                name="price"
                :form="product"
                placeholder="Введите цену"
                @validation-filed="fieldValid"
            />
            <form-file-component
                label=" Выберите картинку для продукта"
                name="picture"
                :form="product"
            />
            <form-select-component
                label="Категория"
                name="category_id"
                @validation-filed="fieldValid"
                :form="product"
                :options="categories"
            />
        </form>
        <button-send-form-component
            :validation-form="validationForm"
            name-button-accepted="Добавить продукт в категорию"
            name-button-denied="Заполните поля"
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
                        </tbody>
                    </table>
                </div>
            </template>
        </button-send-form-component>
    </div>
</template>

<script>
import Form from "vform"
import loadCategories from "../../../mixins/load-categories.js"
import addElementBd from "../../../mixins/add-element-Bd.js"
export default {
    mixins: [loadCategories, addElementBd],
    data() {
        return {
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
            categoryId: this.$route.params.categoryID,
        }
    },
    methods: {
        sendForm() {
            if (this.validationForm) {
                axios
                this.product
                    .post("/api/admin/products/addProduct")
                    .then(() => {
                        this.$swal({
                            icon: "success",
                            title: "Продукт в категорию добавлен",
                        })
                    })
                    .catch(() => {})
            } else {
                this.$swal({
                    icon: "error",
                    title: "нет данных для отправки",
                })
            }
        },
    },
    mounted() {
        this.product.category_id = this.categoryId ?? ""
        this.$refs.first.$refs.firstInp.focus()
    },
}
</script>
