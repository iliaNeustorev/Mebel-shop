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
                required
                class="form-control w-50"
                v-model.trim="name"
                placeholder="Введите имя категории"
            />
        </div>

        <div class="mb-3">
            <label class="form-label"> Описание категории </label>
            <textarea
                required
                class="form-control w-50"
                v-model.trim="description"
                row="3"
                placeholder="Введите описание категории"
            ></textarea>
        </div>

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
            Добавить категорию
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
            name: "",
            description: "",
            file: {},
            errors: null,
        }
    },
    computed: {
        isDisabled() {
            return !this.name && !this.description ? true : false
        },
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
    },
    methods: {
        handleFileUpload() {
            this.file = this.$refs.file.files[0]
        },
        sendForm() {
            let formData = new FormData()
            formData.append("name", this.name)
            formData.append("description", this.description)
            formData.append("picture", this.file)
            axios
                .post("/api/admin/categories/create", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then(() => {
                    this.$swal({
                        title: "категория добавлена",
                        icon: "success",
                    }).then(() => {})
                    this.$router.push("/admin/categoriesAdmin")
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
                .finally(() => {})
        },
    },
}
</script>

<style></style>
