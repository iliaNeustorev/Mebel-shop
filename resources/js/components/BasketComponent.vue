<template>
    <div>
        <div v-if="errors.length" class="alert alert-danger">
            <ul>
                <li v-for="(error, idx) in errors" :key="idx">
                    {{ error }}
                </li>
                <template v-if="errors.email">
                    Ссылка на <a :href="routeLogin">Вход</a>
                </template>
            </ul>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td>{{ product["title"] }}</td>
                    <td>{{ product["price"] }}</td>
                    <td>{{ product["quantity"] }}</td>
                    <td>{{ product["quantity"] * product["price"] }}</td>
                </tr>

                <tr v-if="products">
                    <td colspan="4">
                        <span
                            >Общая сумма заказа:<b>{{ sumOrder }}</b></span
                        >
                    </td>
                </tr>
                <tr v-if="!products">
                    <td colspan="4">
                        Нет товаров в корзине
                        <em><a :href="routeHome">Перейти в каталог</a></em>
                    </td>
                </tr>
            </tbody>
        </table>
        <template v-if="products">
            <label>Имя</label>
            <input v-model="name" class="form-control mb-2" />
            <label>Адрес</label>
            <input class="form-control mb-2" />
            <label>Почта</label>
            <input v-model="email" type="email" class="form-control mb-2" />
            <button class="btn btn-success">Оформить заказ</button>
        </template>
    </div>
</template>
<script>
export default {
    props: ["errorList", "routeLogin", "products", "routeHome", "sumOrder"],
    data() {
        return {
            errors: [],
            name: "",
            email: "",
        }
    },
    mounted() {
        console.log(this.products)
        for (let error in this.errorList) {
            this.errors.push(this.errorList[error][0])
        }
    },
}
</script>
<style scoped>
span {
    float: right;
}
</style>
