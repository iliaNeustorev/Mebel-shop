<template>
    <div>
        <div v-if="errors" class="alert alert-danger">
            <ul>
                <li v-for="(error, idx) in errors" :key="idx">
                    {{ error[0] }}
                </li>
            </ul>
        </div>
        <div class="mb-3">
            <label class="form-label"> Имя продукта </label>
            <input
                required
                ref="fInp"
                class="form-control w-50"
                v-model.trim="product.name"
                placeholder="Введите имя продукта"
            />
        </div>

        <div class="mb-3">
            <label class="form-label"> Описание продукта </label>
            <textarea
                required
                class="form-control w-50"
                v-model.trim="product.description"
                row="3"
                placeholder="Введите описание продукта"
            ></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label"> Цена продукта </label>
            <input
                required
                class="form-control w-50"
                v-model.trim="product.price"
                placeholder="Введите цену"
            />
        </div>

        <div class="mb-3">
            <label class="form-label"> Выберите картинку для продукта </label>
            <br />
            <input
                id="file"
                type="file"
                ref="file"
                class="form-control w-50"
                v-on:change="handleFileUpload()"
            />
        </div>

        <div class="gx-3 gy-2 mb-3 row align-items-center">
            <div class="col-sm-3">
                <label class="form-label"> Категория </label>
                <select
                    :disabled="!changeCategory"
                    class="form-select"
                    v-model="categoryId"
                >
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>
            <div class="col-auto mt-5">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        id="checkbox"
                        type="checkbox"
                        v-model="changeCategory"
                    />
                    <label class="form-check-label" for="checkbox"
                        >Изменить категорию</label
                    >
                </div>
            </div>
        </div>
        <button-send-form
            :validation-form="validationForm"
            name-button-accepted="Добавить продукт в категорию"
            name-button-denied="Заполните поля"
            @acceptedForm="sendForm()"
        ></button-send-form>
    </div>
</template>

<script>
export default {
    props: {
        categoryId: { type: Number, default: 1 },
    },
    data() {
        return {
            errors: null,
            product: {
                name: "",
                description: "",
                price: "",
            },
            categories: [],
            file: {},
            changeCategory: false,
            newID: 0,
        }
    },
    computed: {
        id() {
            return this.categoryId
        },
        validationForm() {
            return Object.values(this.product).every((val) => val.length > 0)
        },
    },
    methods: {
        sendForm() {
            if (this.validationForm) {
                let formData = new FormData()
                formData.append("name", this.product.name)
                formData.append("description", this.product.description)
                formData.append("price", this.product.price)
                formData.append("categoryId", this.id)
                formData.append("picture", this.file)
                axios
                    .post("/api/admin/products/addProduct", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    })
                    .then(() => {
                        this.$swal({
                            title: "Продукт в категорию добавлен",
                            icon: "success",
                        }).then(() => {
                            this.$router.go(0)
                        })
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors
                    })
            } else {
                this.$swal({
                    title: "нет данных для отправки",
                    icon: "error",
                })
            }
        },

        handleFileUpload() {
            this.file = this.$refs.file.files[0]
        },
    },
    created() {
        axios.get("/api/categories/get").then((response) => {
            this.categories = response.data
        })
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
        this.$refs.fInp.focus()
    },
}
</script>

<style></style>
