<template>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <router-link class="navbar-brand" :to="{ name: 'Home' }">
                Mebel-shop
            </router-link>
            <div v-if="hiddenSearch">
                <search-component @search="search" />
            </div>
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
                        <router-link class="nav-link" :to="{ name: 'cart' }">
                            Корзина ({{ quantity }})
                        </router-link>
                    </li>
                    <!-- Authentication Links -->
                    <li v-if="!user.name" class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'login' }"
                            >Авторизация</router-link
                        >
                    </li>

                    <li v-if="!user.name" class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'register' }"
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
                            <router-link
                                v-if="user.admin"
                                class="dropdown-item"
                                :to="{ name: 'admin' }"
                                >Администрирование</router-link
                            >
                            <router-link
                                class="dropdown-item"
                                :to="{
                                    name: 'profile',
                                    params: { name: user.name },
                                }"
                                >Личный кабинет</router-link
                            >
                            <router-link
                                v-if="chekOrders > 0"
                                class="dropdown-item"
                                :to="{ name: 'Orders' }"
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
import { mapGetters } from "vuex"
export default {
    computed: {
        ...mapGetters(["user", "quantity", "chekOrders"]),
        hiddenSearch() {
            return this.$route.path != "/result"
        },
    },
    mounted() {
        if (!this.user.length) {
            axios
                .get("/api/user")
                .then((response) => {
                    this.$store.dispatch("getUser", response.data.user)
                    this.$store.dispatch("getChekOrders", response.data.orders)
                    localStorage.setItem("user", response.data.user.name)
                })
                .catch(() => {
                    localStorage.removeItem("user")
                    this.$store.dispatch("getUser", {})
                })
        }
        if (this.quantity == 0) {
            this.$store.dispatch("getBasketProductsQuantity")
        }
    },

    methods: {
        search(field) {
            this.$router.push({
                name: "Search",
                params: { fieldSearch: field, start: true },
            })
        },
        logout() {
            axios.post("/api/logout").then(() => {
                localStorage.removeItem("user")
                this.$store.dispatch("getUser", {})
                this.$store.dispatch("getChekOrders", 0)
                this.$store.dispatch("changeBasketProductsQuantity", 0)
                if (this.$route.path != "/") this.$router.push({ name: "Home" })
            })
        },
    },
}
</script>
