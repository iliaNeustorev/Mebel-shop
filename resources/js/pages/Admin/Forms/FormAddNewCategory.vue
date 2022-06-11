<template>
    <div>
        <div v-if="errors" class="alert alert-danger">
            <ul>
                <li v-for="(error, idx) in errors" :key="idx">
                    {{ error[0] }}
                </li>
            </ul>
        </div>
        <router-link to="/admin/categoriesAdmin">
            <button class="btn btn-success mt-2">Назад</button></router-link
        >
        <div class="mb-3">
            <label class="form-label"> Имя категории </label>
            <input
                required
                ref="firstInp"
                class="form-control w-50"
                v-model.trim="category.name"
                @keyup.enter="$refs.twoInp.focus()"
                placeholder="Введите имя категории"
            />
        </div>

        <div class="mb-3">
            <label class="form-label"> Описание категории </label>
            <textarea
                required
                class="form-control w-50"
                ref="twoInp"
                v-model.trim="category.description"
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
        <button-send-form
            :validation-form="validationForm"
            name-button-accepted="Добавить категорию"
            name-button-denied="Заполните поля"
            @acceptedForm="sendForm()"
        ></button-send-form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            category: {
                name: "",
                description: "",
            },
            file: {},
            errors: null,
        }
    },
    computed: {
        validationForm() {
            return Object.values(this.category).every((val) => val.length > 0)
        },
    },
    mounted() {
        this.$refs.firstInp.focus()
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
    },
    methods: {
        handleFileUpload() {
            this.file = this.$refs.file.files[0]
        },
        sendForm() {
            if (this.validationForm) {
                let formData = new FormData()
                formData.append("name", this.category.name)
                formData.append("description", this.category.description)
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
            } else {
                this.$swal({
                    title: "нет данных для отправки",
                    icon: "error",
                })
            }
        },
    },
}
</script>

<style></style>
