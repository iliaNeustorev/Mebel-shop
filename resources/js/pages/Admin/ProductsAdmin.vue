<template>
    <div>
        <div class="d-grid gap-2 d-md-block text-center mb-3">
            <button
                @click="exportProducts()"
                class="btn btn-primary btn-xl mb-2"
            >
                Выгрузить список продуктов
            </button>
            <button type="submit" class="btn btn-link btn-xl mb-2">
                Загрузить список продуктов
            </button>
            <button @click="$router.go(-1)" class="btn btn-success">
                Назад
            </button>
            <div v-if="processing" class="alert alert-warning text-center">
                Продукты выгружаются
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div
                v-else-if="exportFinished"
                class="alert alert-success text-center"
            >
                Продукты выгружены <a :href="downloadLink">(скачать)</a>
            </div>
        </div>
        <hr />
        <h2 class="text-center">Таблица со всеми продуктами</h2>
        <table class="table table-bordered mt-2 text-center">
            <thead>
                <th></th>
                <th>Имя</th>
                <th>Описание</th>
                <th>Цена</th>
                <th>Категория</th>
                <th>Изображение</th>
                <th>Дата создания</th>
                <th>Последнее изменение</th>
            </thead>
            <tr v-if="loading" class="text-center">
                <td colspan="7">
                    <img
                        class="loader text-center"
                        src="/storage/img/loaders/loader.gif"
                    />
                </td>
            </tr>
            <tbody v-else>
                <tr v-for="product in products" :key="product.id">
                    <td>
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="check_delete[]"
                                value=""
                                id="flexCheckDefault"
                            />
                            <label
                                class="form-check-label"
                                for="flexCheckDefault"
                            ></label>
                        </div>
                    </td>
                    <td>
                        <a href="" title="Редактировать продукт">{{
                            product.name
                        }}</a>
                    </td>
                    <td>{{ product.description }}</td>
                    <td>{{ product.price }}</td>
                    <td></td>
                    <a href="" title="Редактировать продукт"
                        ><img
                            class="avatar"
                            :src="`/storage/img/products/` + product.picture"
                            :alt="product.name"
                    /></a>
                    <td>{{ product.created_at }}</td>
                    <td>{{ product.updated_at }}</td>
                </tr>
                <tr>
                    <td class="text-start" colspan="8">
                        <button type="submit" class="btn btn-danger">
                            Удалить выбраные записи
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            processing: false,
            exportFinished: false,
            downloadLink: null,
            products: [],
        }
    },
    methods: {
        exportProducts() {
            this.processing = true
            axios
                .post("/api/admin/exportProducts")
                .then(() => {})
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
                .finally(() => {
                    this.exportFinished = false
                })
        },
    },
    mounted() {
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }

        axios.get("/api/admin/products").then((response) => {
            this.products = response.data
            this.loading = false
        })

        Echo.channel("general").listen(".products-export-finish", (e) => {
            this.processing = false
            this.exportFinished = true
            this.downloadLink = `/storage/${e.message}`
        })
    },
    destroyed() {
        Echo.channel("general").stopListening(".products-export-finish")
    },
}
</script>

<style scoped></style>
