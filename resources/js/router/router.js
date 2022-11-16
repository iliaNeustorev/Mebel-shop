import Vue from "vue"
import VueRouter from "vue-router"
import store from "../store/store.js"

Vue.use(VueRouter)

const Page404 = { template: "<div>Страница не найдена</div>" }

import HomePage from "../pages/Home"
import CategoryPage from "../pages/Category"
import CartPage from "../pages/Cart"
import LoginPage from "../pages/Auth/Login"
import RegisterPage from "../pages/Auth/Register"
import AdminPage from "../pages/Admin/Main"
import showRegUsersPage from "../pages/Admin/ShowUsers"
import CategoriesAdminPage from "../pages/Admin/AllCategories"
import ProfilePage from "../pages/Profile"
import OrdersPage from "../pages/Orders"
import ProductsAdminPage from "../pages/Admin/AllProducts"
import AddNewCategory from "../pages/Admin/Category/AddCategory"
import EditCategory from "../pages/Admin/Category/EditCategory"
import ProductAdmin from "../pages/Admin/CategoryWithProducts"
import AddProduct from "../pages/Admin/Product/AddProduct"
import EditProduct from "../pages/Admin/Product/EditProduct"
import Search from "../pages/Search.vue"

const routes = [
    {
        name: "Home",
        path: "/",
        component: HomePage,
    },
    {
        name: "ShowCategory",
        path: "/categories/:id",
        component: CategoryPage,
    },
    {
        name: "cart",
        path: "/cart",
        component: CartPage,
    },
    {
        name: "Search",
        path: "/result",
        component: Search,
    },
    {
        name: "login",
        path: "/login",
        component: LoginPage,
        meta: { Auth: true },
    },
    {
        name: "register",
        path: "/register",
        component: RegisterPage,
        meta: { Auth: true },
    },
    {
        name: "admin",
        path: "/admin",
        component: AdminPage,
        meta: { Admin: true },
    },

    {
        name: "ShowRegUsers",
        path: "/admin/showRegUsers",
        component: showRegUsersPage,
        meta: { Admin: true },
    },
    {
        name: "profile",
        path: "/profile/:name",
        component: ProfilePage,
        meta: { NoAuth: true },
    },
    {
        name: "AdminCategories",
        path: "/admin/categories",
        component: CategoriesAdminPage,
        meta: { Admin: true },
    },
    {
        name: "AddNewCategory",
        path: "/admin/addCategory",
        component: AddNewCategory,
        meta: { Admin: true },
    },
    {
        name: "EditCategory",
        path: "/admin/editCategory/:name",
        component: EditCategory,
        meta: { Admin: true },
    },
    {
        name: "AdminProducts",
        path: "/admin/productsAdmin",
        component: ProductsAdminPage,
        meta: { Admin: true },
    },
    {
        name: "Orders",
        path: "/orders",
        component: OrdersPage,
        meta: { NoAuth: true },
    },
    {
        name: "ShowOneCategoryWithProducts",
        path: "/admin/category/:id/products",
        component: ProductAdmin,
        meta: { Admin: true },
    },
    {
        name: "AddProduct",
        path: "/admin/addProduct",
        component: AddProduct,
        meta: { Admin: true },
    },
    {
        name: "EditProduct",
        path: "/admin/category/EditProduct/:id",
        component: EditProduct,
        meta: { Admin: true },
    },

    { path: "*", redirect: "404" },
    { path: "/404", component: Page404, name: "404" },
]

const router = new VueRouter({
    mode: "history",
    routes,
})

router.beforeEach((to, from, next) => {
    let user = localStorage.getItem("user")
    let admin = store.state.user.admin
    if (to.matched.some((record) => record.meta.NoAuth)) {
        if (!user) {
            next({
                name: "login",
            })
        } else {
            next()
        }
    }
    if (to.matched.some((record) => record.meta.Auth)) {
        if (user) {
            next({
                name: "Home",
            })
        } else {
            next()
        }
    }
    if (to.matched.some((record) => record.meta.Admin)) {
        if (!admin) {
            next({
                name: "Home",
            })
        }
    }
    next()
})

export default router
