import axios from "axios"
import Vue from "vue"
import Vuex from "vuex"

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        chekOrders:0,
        user: {},
        basketProductsQuantity: 0,
    },
    getters: {},
    mutations: {
        setBasketProductsQuantity(state, payload) {
            state.basketProductsQuantity = payload
        },
        setUser(state, payload) {
            state.user = payload
        },
        setChekOrders (state, payload) {
            state.chekOrders = payload
        }
    },
    actions: {
        getUser(context, payload) {
            context.commit('setUser', payload)
        },
        getChekOrders(context, payload) {
            context.commit('setChekOrders', payload)
        },
        changeBasketProductsQuantity(context, payload) {
            context.commit("setBasketProductsQuantity", payload)
        },
        getBasketProductsQuantity(context) {
            axios.get("/basket/getProductsQuantity").then((response) => {
                context.commit("setBasketProductsQuantity", response.data)
            })
        },
    },
})

export default store
