import Vue from "vue"
import Vuex from "vuex"

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        basketProductsQuantity: 0,
    },
    getters: {},
    mutations: {
        setBasketProductQuantity(state, payload) {
            state.basketProductsQuantity = payload
        },
    },
    actions: {
        changeBasketProductsQuantity(context, payload) {
            context.commit("setBasketProductQuantity", payload)
        },
    },
})

export default store
