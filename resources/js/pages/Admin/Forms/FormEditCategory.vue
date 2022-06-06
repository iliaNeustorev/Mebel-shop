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
        <img class="avatar" :src="`/storage/img/categories/` + picture" />
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
            :disabled="validationForm"
            @click="sendForm()"
            class="btn btn-success"
        >
            Принять изменения
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            oldData: { picture: "", id: "", name: "", description: "" },
            picture: "",
            id: "",
            name: "",
            description: "",
            file: {},
            chekFile: false,
            errors: null,
        }
    },
    computed: {
        validationForm() {
            if (
                !this.name ||
                !this.description ||
                (this.name == this.oldData.name &&
                    this.description == this.oldData.description &&
                    this.chekFile == false)
            ) {
                return true
            } else {
                return false
            }
        },
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
        this.$root.$on("eventing", (data) => {
            this.oldData = data
            this.picture = data.picture
            this.id = data.id
            this.name = data.name
            this.description = data.description
        })
    },
    methods: {
        handleFileUpload() {
            this.file = this.$refs.file.files[0]
            this.chekFile = true
        },
        sendForm() {
            if (!this.validationForm) {
                let formData = new FormData()
                formData.append("id", this.id)
                formData.append("name", this.name)
                formData.append("description", this.description)
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
