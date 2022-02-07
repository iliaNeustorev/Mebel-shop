import Vue from "vue"
import VueRouter from "vue-router"

Vue.use(VueRouter)


const Foo = { template: '<div>Foo</div>' }
const Bar = { template: '<div>Bar</div>' }

const routes = [
    { path: '/', component: Foo },
    { path: '/Bar', component: Bar },
]

const router = new VueRouter ({ 
    routes 
})

export default router
