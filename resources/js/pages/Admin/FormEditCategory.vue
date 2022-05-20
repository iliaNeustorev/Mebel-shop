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
            <label class="form-label"> Имя категории </label>
            <input
                @keydown="isDisabled = false"
                required
                class="form-control w-50"
                v-model.trim="category.name"
                placeholder="Введите имя категории"
            />
        </div>

        <div class="mb-3">
            <label class="form-label"> Описание категории </label>
            <textarea
                required
                @keydown="isDisabled = false"
                class="form-control w-50"
                v-model.trim="category.description"
                row="3"
                placeholder="Введите описание категории"
            ></textarea>
        </div>
        <img
            class="avatar"
            :src="`/storage/img/categories/` + category.picture"
        />
        <div class="mb-3">
            <label class="form-label"> Выберите картинку для категории </label>
            <br />
            <input
                id="file"
                type="file"
                ref="file"
                class="form-control w-50"
                v-on:change="handleFileUpload()"
            />
        </div>
        <button
            :disabled="isDisabled"
            @click="sendForm()"
            class="btn btn-success w-50"
        >
            Принять изменения
        </button>
        <router-link to="/admin/categoriesAdmin">
            <button class="btn btn-success w-50 mt-2">
                Назад
            </button></router-link
        >
    </div>
</template>

<script>
export default {
    data() {
        return {
            oldData: { picture: "", id: "", name: "", description: "" },
            category: {
                picture: "",
                id: "",
                name: "",
                description: "",
            },
            file: {},
            chekFile: false,
            errors: null,
            isDisabled: true,
        }
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
        this.$root.$on("eventing", (data) => {
            this.oldData = data
            this.category.picture = data.picture
            this.category.id = data.id
            this.category.name = data.name
            this.category.description = data.description
        })
    },
    methods: {
        handleFileUpload() {
            this.isDisabled = false
            this.file = this.$refs.file.files[0]
            this.chekFile = true
        },
        sendForm() {
            if (
                this.oldData.name == this.category.name &&
                this.oldData.description == this.category.description &&
                this.chekFile == false
            ) {
                this.$swal({
                    title: "Вы не внесли изменений",
                    icon: "danger",
                })
            } else {
                let formData = new FormData()
                formData.append("id", this.category.id)
                formData.append("name", this.category.name)
                formData.append("description", this.category.description)
                formData.append("picture", this.file)
                axios
                    .post("/api/admin/categories/category/update", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    })
                    .then(() => {
                        this.$swal({
                            title: "Изменения выполнены",
                            icon: "success",
                        }).then(() => {})
                        this.$router.push("/admin/categoriesAdmin")
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors
                    })
                    .finally(() => {})
            }
        },
    },
}
</script>

<style></style>
