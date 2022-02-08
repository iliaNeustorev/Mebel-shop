import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)


const Bar = { template: '<div>Bar</div>' }
const Page404 = { template: '<div>Страница не найдена</div>' }

import HomeComponent from "../pages/Home.vue"

const routes = [
    { path: '/', component: HomeComponent},
    { path: '/bar', component: Bar },
    { path: '*', redirect: '404'},
    { path: '/404', component: Page404, name: '404'},
]

const router = new VueRouter ({ 
    mode:'history',
    routes 
})

export default router
