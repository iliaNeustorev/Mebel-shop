import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)

const Page404 = { template: "<div>Страница не найдена</div>" }

import HomePage from "../pages/Home"
import CategoryPage from "../pages/Category"
import BasketPage from "../pages/Basket"
import LoginPage from "../pages/Auth/Login"
import RegisterPage from "../pages/Auth/Register"
import AdminPage from "../pages/Admin/Admin"
import showRegUsersPage from "../pages/Admin/ShowUsers"
import CategoriesAdminPage from "../pages/Admin/CategoriesAdmin"
import ProfilePage from "../pages/Profile"
import ProductsAdminPage from "../pages/Admin/ProductsAdmin"

const routes = [
    { path: "/", component: HomePage },
    { path: "/categories/:id", component: CategoryPage },
    { path: "/basket", component: BasketPage },
    { path: "/login", component: LoginPage },
    { path: "/register", component: RegisterPage },
    { path: "/admin", component: AdminPage },
    { path: "/admin/showRegUsers", component: showRegUsersPage },
    { path: "/admin/categoriesAdmin", component: CategoriesAdminPage },
    { path: "/admin/productsAdmin", component: ProductsAdminPage },
    { path: "/profile", component: ProfilePage },
    { path: "*", redirect: "404" },
    { path: "/404", component: Page404, name: "404" },
]

const router = new VueRouter({
    mode: "history",
    routes,
})

export default router
