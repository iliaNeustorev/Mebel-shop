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

        <div class="mb-3">
            <label class="form-label"> Категория </label>
            <select disabled class="form-control w-50" v-model="categoryName">
                <option>
                    {{ categoryName }}
                </option>
            </select>
        </div>
        <button
            @click="sendForm()"
            :disabled="!isDisabled"
            class="btn btn-success w-50"
        >
            Добавить продукт в категорию
        </button>
    </div>
</template>

<script>
export default {
    props: { categoryId: Number, categoryName: String },
    data() {
        return {
            errors: null,
            product: {
                name: "",
                description: "",
                price: "",
            },
            file: {},
        }
    },
    computed: {
        id() {
            return this.categoryId
        },
        isDisabled() {
            return Object.values(this.product).every((val) => val.length > 0)
        },
    },
    methods: {
        sendForm() {
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
        },
        handleFileUpload() {
            this.file = this.$refs.file.files[0]
        },
    },

    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
    },
}
</script>

<style></style>
