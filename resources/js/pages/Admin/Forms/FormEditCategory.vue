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
            <router-link to="/admin/categoriesAdmin">
                <button class="btn btn-success mt-2">Назад</button></router-link
            >
        </div>
        <div class="mb-3">
            <label class="form-label"> Имя категории </label>
            <input
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
        <button-send-form
            :validation-form="validationForm"
            name-button-accepted="Принять изменения"
            name-button-denied="Внесите изменения"
            @acceptedForm="sendForm()"
        ></button-send-form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            oldData: { picture: "", id: "", name: "", description: "" },
            category: { picture: "", id: "", name: "", description: "" },
            file: {},
            checkFile: false,
            errors: null,
        }
    },
    computed: {
        validationForm() {
            return (
                JSON.stringify(this.category) !==
                    JSON.stringify(this.oldData) || this.checkFile
            )
        },
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
        this.$root.$on("eventing", (data) => {
            this.oldData.name = data.name
            this.oldData.description = data.description
            this.oldData.id = data.id
            this.oldData.picture = data.picture
            this.category.name = data.name
            this.category.description = data.description
            this.category.id = data.id
            this.category.picture = data.picture
        })
    },
    methods: {
        handleFileUpload() {
            this.file = this.$refs.file.files[0]
            this.checkFile = true
        },
        sendForm() {
            if (this.validationForm) {
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
                            title: "Изменения приняты",
                            icon: "success",
                        }).then(() => {})
                        this.$router.push("/admin/categoriesAdmin")
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors
                    })
            } else {
                this.$swal({
                    title: "Валидация не пройдена",
                    icon: "error",
                })
            }
        },
    },
}
</script>

<style></style>
