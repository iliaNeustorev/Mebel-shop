import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)

const Page404 = { template: "<div>Страница не найдена</div>" }

import HomePage from "../pages/Home"
import CategoryPage from "../pages/Category"
import CartPage from "../pages/Cart"
import LoginPage from "../pages/Auth/Login"
import RegisterPage from "../pages/Auth/Register"
import AdminPage from "../pages/Admin/Admin"
import showRegUsersPage from "../pages/Admin/ShowUsers"
import CategoriesAdminPage from "../pages/Admin/CategoriesAdmin"
import ProfilePage from "../pages/Profile"
import OrdersPage from "../pages/Orders"
import ProductsAdminPage from "../pages/Admin/ProductsAdmin"
import AddNewCategory from "../pages/Admin/Forms/FormAddNewCategory"
import EditCategory from "../pages/Admin/Forms/FormEditCategory"
import ProductAdmin from "../pages/Admin/ProductСategoryAdmin.vue"
import EditProduct from "../components/Forms/editProduct.vue"

const routes = [
    { path: "/", component: HomePage },
    { path: "/categories/:id", component: CategoryPage },
    {
        name: "cart",
        path: "/cart",
        component: CartPage,
    },
    {
        name: "login",
        path: "/login",
        component: LoginPage,
    },
    {
        name: "register",
        path: "/register",
        component: RegisterPage,
    },
    {
        name: "admin",
        path: "/admin",
        component: AdminPage,
    },
    {
        name: "ShowRegUsers",
        path: "/admin/showRegUsers",
        component: showRegUsersPage,
    },
    {
        name: "profile",
        path: "/profile/:name",
        component: ProfilePage,
    },
    {
        name: "AdminCategories",
        path: "/admin/categories",
        component: CategoriesAdminPage,
    },
    {
        name: "AddNewCategory",
        path: "/admin/addNewCategory",
        component: AddNewCategory,
    },
    {
        name: "EditCategory",
        path: "/admin/editCategory/:name",
        component: EditCategory,
    },
    {
        name: "AdminProducts",
        path: "/admin/productsAdmin",
        component: ProductsAdminPage,
    },
    {
        name: "Orders",
        path: "/orders",
        component: OrdersPage,
    },
    {
        name: "ShowOneCategoryWithProducts",
        path: "/admin/category/:id/products",
        component: ProductAdmin,
    },
    {
        name: "EditProduct",
        path: "/admin/category/EditProduct/:id",
        component: EditProduct,
        props: true,
    },
    { path: "*", redirect: "404" },
    { path: "/404", component: Page404, name: "404" },
]

const router = new VueRouter({
    mode: "history",
    routes,
})

export default router
