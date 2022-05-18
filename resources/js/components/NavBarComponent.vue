<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <router-link class="navbar-brand" to="/"> Mebel-shop </router-link>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto"></ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <li v-if="quantity" class="nav-item">
                        <router-link class="nav-link" to="/basket">
                            Корзина
                        </router-link>
                    </li>
                    <!-- Authentication Links -->
                    <li v-if="!user.name" class="nav-item">
                        <router-link class="nav-link" to="/login"
                            >Авторизация</router-link
                        >
                    </li>

                    <li v-if="!user.name" class="nav-item">
                        <router-link class="nav-link" to="/register"
                            >Регистрация</router-link
                        >
                    </li>
                    <li v-if="user.name" class="nav-item dropdown">
                        <span
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                        >
                            {{ user.name }}
                            <img
                                class="avatar"
                                style="
                                    height: 20px;
                                    width: 20px;
                                    border-radius: 20px;
                                "
                                :src="`storage/img/users/` + user.picture"
                            />
                        </span>

                        <div
                            class="dropdown-menu dropdown-menu-right"
                            aria-labelledby="navbarDropdown"
                        >
                            <router-link class="dropdown-item" to="/admin"
                                >Администрирование</router-link
                            >
                            <router-link class="dropdown-item" to="/profile"
                                >Личный кабинет</router-link
                            >
                            <router-link
                                v-if="orders > 0"
                                class="dropdown-item"
                                to="/orders"
                                >Заказы</router-link
                            >
                            <button class="dropdown-item" @click="logout()">
                                Выход
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    data() {
        return {}
    },
    computed: {
        quantity() {
            return this.$store.state.basketProductsQuantity
        },
        user() {
            return this.$store.state.user
        },
        orders() {
            return this.$store.state.chekOrders
        },
    },
    mounted() {
        this.$store.dispatch("getBasketProductsQuantity", {})
        if (!this.user.name) {
            axios.get("/api/user").then((response) => {
                this.$store.dispatch("getUser", response.data.user)
                this.$store.dispatch("getChekOrders", response.data.orders)
            })
        }
    },
    methods: {
        logout() {
            axios.post("/api/logout").then(() => {
                this.$store.dispatch("getUser", {})
                this.$store.dispatch("getChekOrders", 0)
                if (this.$route.path != "/") this.$router.push("/")
            })
        },
    },
}
</script>
