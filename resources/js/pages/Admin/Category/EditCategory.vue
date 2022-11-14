<template>
    <div>
        <button-back-component />
        <div class="text-center" v-if="loading"><loading-component /></div>
        <div v-else>
            <form>
                <form-input-component
                    label="Имя категории"
                    name="name"
                    :form="category"
                    placeholder="Введите имя категории"
                    :oldValue="oldData.name"
                    @validationFiled="fieldValid"
                />

                <form-textarea-component
                    label="Описание категории"
                    name="description"
                    placeholder="Введите описание категории"
                    :form="category"
                    :oldValue="oldData.description"
                    @validationFiled="fieldValid"
                />
                <img
                    class="avatar"
                    :src="`/storage/img/categories/` + oldData.picture"
                />
                <form-file-component
                    label=" Выберите картинку для категории"
                    name="picture"
                    :form="category"
                    :checkFile="checkFile"
                    @changeCheckFile="changedPicture"
                />
            </form>
            <button-send-form-component
                :validation-form="validationForm"
                name-button-accepted="Принять изменения"
                name-button-denied="Внесите изменения"
                @acceptedForm="sendForm()"
                ><template v-slot:mainModal>
                    <div class="container">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <td>Имя категория</td>
                                    <td>Описание</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ category.name }}
                                    </td>
                                    <td>
                                        {{ category.description }}
                                    </td>
                                </tr>
                                <tr v-if="checkFile">
                                    <td colspan="2">
                                        <b>Изменение картинки</b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <h3>Старые значения</h3>
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <td>Имя категория</td>
                                    <td>Описание</td>
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
import editElementBd from "../../../mixins/edit-element-Bd.js"
export default {
    mixins: [editElementBd],
    data() {
        return {
            oldData: { picture: "", name: "", description: "" },
            category: Form.make({
                picture: null,
                name: "",
                description: "",
            }),
            validation: { name: false, description: false },
            loading: true,
        }
    },
    created() {
        axios
            .get(`/api/admin/categories/category/${this.$route.params.id}/edit`)
            .then((response) => {
                this.oldData = response.data
                Object.assign(this.category, response.data)
            })
            .finally(() => {
                this.loading = false
            })
    },
    methods: {
        async sendForm() {
            if (this.validationForm) {
                axios
                await this.category
                    .post("/api/admin/categories/category/update")
                    .then(() => {
                        this.$swal({
                            icon: "success",
                            title: "Изменения приняты",
                        }).then(() => {
                            this.$router.push({ name: "AdminCategories" })
                        })
                    })
                    .catch(() => {
                        this.$swal({
                            icon: "error",
                            title: "Ошибка",
                        })
                    })
            } else {
                this.$swal({
                    icon: "error",
                    title: "Валидация не пройдена",
                })
            }
        },
    },
}
</script>
