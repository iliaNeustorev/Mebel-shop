import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)


const Bar = { template: '<div>Bar</div>' }
const Page404 = { template: '<div>Страница не найдена</div>' }

import HomePage from "../pages/Home"
import CategoryPage from "../pages/Category"
import BasketPage from "../pages/Basket"

const routes = [
    { path: '/', component: HomePage},
    { path: '/categories/:id', component: CategoryPage},
    { path: '/basket', component: BasketPage},
    { path: '/bar', component: Bar },
    { path: '*', redirect: '404'},
    { path: '/404', component: Page404, name: '404'},
]

const router = new VueRouter ({ 
    mode:'history',
    routes 
})

export default router
