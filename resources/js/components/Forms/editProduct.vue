<template>
    <div>
        <div v-if="errors" class="alert alert-danger">
            <ul>
                <li v-for="(error, idx) in errors" :key="idx">
                    {{ error[0] }}
                </li>
            </ul>
        </div>
        <button @click="$router.go(-1)" class="btn btn-success mt-2 mb-2">
            Назад
        </button>
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
        <img class="avatar" :src="`/storage/img/products/` + picture" />
        <div class="mb-3">
            <label class="form-label"> Выберите картинку для продукта </label>
            <br />
            <input
                id="file"
                type="file"
                ref="file"
                class="form-control w-50"
                @change="handleFileUpload()"
            />
        </div>

        <div class="gx-3 gy-2 mb-3 row align-items-center">
            <div class="col-sm-3">
                <label class="form-label"> Категория </label>
                <select
                    class="form-select"
                    v-model="product.categoryID"
                    @change="setCategoryName(product.categoryID)"
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
        </div>
        <button-send-form
            :validation-form="validationForm"
            name-button-accepted="Принять изменения"
            name-button-denied="Внесите изменения"
            @acceptedForm="sendForm()"
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
                                    {{ product.categoryN }}
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
                                    {{ oldData.categoryN }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template></button-send-form
        >
    </div>
</template>

<script>
export default {
    props: {
        name: { type: String, required: true },
        price: { type: Number, required: true },
        description: { type: String, required: true },
        categoryID: { type: Number, required: true },
        id: { type: Number, required: true },
        picture: { type: String, required: true },
    },
    data() {
        return {
            oldData: {
                name: this.name,
                price: this.price,
                description: this.description,
                categoryID: this.categoryID,
                categoryN: "",
            },
            product: {
                name: this.name,
                price: this.price,
                description: this.description,
                categoryID: this.categoryID,
                categoryN: "",
            },
            errors: null,
            file: {},
            categories: [],
            checkfile: false,
        }
    },
    computed: {
        validationForm() {
            return (
                (JSON.stringify(this.oldData) !==
                    JSON.stringify(this.product) &&
                    Object.values(this.product).every(
                        (val) => val.length != 0
                    )) ||
                this.checkfile == true
            )
        },
    },
    created() {
        axios.get("/api/categories/get").then((response) => {
            this.categories = response.data
            this.setCategoryName(this.categoryID)
        })
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
    },
    methods: {
        sendForm() {
            if (this.validationForm) {
                let formData = new FormData()
                formData.append("id", this.id)
                formData.append("name", this.product.name)
                formData.append("description", this.product.description)
                formData.append("price", this.product.price)
                formData.append("categoryId", this.product.categoryID)
                formData.append("picture", this.file)
                axios
                    .post("/api/admin/products/updProduct", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    })
                    .then(() => {
                        this.$swal({
                            title: "Изменение прошло успешно",
                            icon: "success",
                        }).then(() => {
                            this.$router.go(-1)
                        })
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors
                    })
            } else {
                this.$swal({
                    title: "Недостаточно данных для отправки",
                    icon: "error",
                })
            }
        },

        handleFileUpload() {
            this.file = this.$refs.file.files[0]
            this.checkfile = true
        },

        setCategoryName(id) {
            let item = this.categories.find((item) => item.id == id)
            if (id == this.categoryID) {
                this.oldData.categoryN = item.name
                this.product.categoryN = item.name
            } else {
                this.product.categoryN = item.name
            }
        },
    },
}
</script>

<style></style>
