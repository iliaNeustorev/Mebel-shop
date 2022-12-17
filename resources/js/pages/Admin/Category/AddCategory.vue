<template>
    <div>
        <button-back-component />
        <form>
            <form-input-component
                ref="first"
                label="Имя категории"
                name="name"
                placeholder="Введите имя категории"
                :form="category"
                @validation-filed="fieldValid"
            />
            <form-textarea-component
                label="Описание категории"
                name="description"
                placeholder="Введите описание категории"
                :form="category"
                @validation-filed="fieldValid"
            />
            <form-file-component
                label=" Выберите картинку для категории"
                name="picture"
                :form="category"
            />
        </form>
        <button-send-form-component
            :validation-form="validationForm"
            name-button-accepted="Добавить категорию"
            name-button-denied="Заполните поля"
            @accepted-form="sendForm()"
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
                        </tbody>
                    </table>
                </div>
            </template></button-send-form-component
        >
    </div>
</template>

<script>
import Form from "vform"
import addElementBd from "../../../mixins/add-element-Bd.js"
export default {
    mixins: [addElementBd],
    data() {
        return {
            category: Form.make({
                name: "",
                description: "",
                picture: null,
            }),
            validation: { name: false, description: false },
        }
    },
    mounted() {
        this.$refs.first.$refs.firstInp.focus()
    },
    methods: {
        sendForm() {
            if (this.validationForm) {
                axios
                this.category
                    .post("/api/admin/categories/create")
                    .then(() => {
                        this.$swal({
                            icon: "success",
                            title: "Категория добавлена",
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
                    title: "нет данных для отправки",
                })
            }
        },
    },
}
</script>
