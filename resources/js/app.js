/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap")

window.Vue = require("vue").default

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component("navbar-component", require("./components/NavBar.vue").default)

Vue.component(
    "progress-bar-component",
    require("./components/ProgressBar.vue").default
)

Vue.component(
    "button-send-form-component",
    require("./components/Buttons/ButtonSendForm.vue").default
)

Vue.component(
    "button-mass-delete-component",
    require("./components/Buttons/ButtonMassDelete.vue").default
)

Vue.component(
    "form-input-component",
    require("./components/Forms/formInput.vue").default
)
Vue.component(
    "form-textarea-component",
    require("./components/Forms/formTextarea.vue").default
)
Vue.component(
    "form-file-component",
    require("./components/Forms/formFile.vue").default
)
Vue.component(
    "form-select-component",
    require("./components/Forms/formSelect.vue").default
)
Vue.component(
    "form-checkbox-component",
    require("./components/Forms/formCheckbox.vue").default
)
Vue.component("loading-component", require("./components/Loading.vue").default)
Vue.component(
    "button-back-component",
    require("./components/Buttons/ButtonBack.vue").default
)
Vue.component(
    "show-errors-component",
    require("./components/ShowErrors.vue").default
)
Vue.component("import-component", require("./components/Import.vue").default)
Vue.component("export-component", require("./components/Export.vue").default)
Vue.component(
    "button-change-cart-component",
    require("./components/Buttons/ButtonChangeCart.vue").default
)
Vue.component(
    "change-password-component",
    require("./components/ChangePassword.vue").default
)
Vue.component(
    "address-list-component",
    require("./components/addressList.vue").default
)
Vue.component("search-component", require("./components/Search.vue").default)
Vue.component(
    "pagination-component",
    require("./components/Pagination.vue").default
)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import store from "./store/store.js"
import router from "./router/router.js"
import Vue from "vue"
const app = new Vue({
    el: "#app",
    store,
    router,
})
