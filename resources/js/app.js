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

Vue.component(
    "navbar-component",
    require("./components/NavBarComponent.vue").default
)

Vue.component(
    "progress-bar-component",
    require("./components/ProgressBarComponent.vue").default
)

Vue.component(
    "button-send-form",
    require("./components/Buttons/ButtonSendForm.vue").default
)

Vue.component(
    "button-mass-delete",
    require("./components/Buttons/ButtonMassDelete.vue").default
)

Vue.component("form-input", require("./components/Forms/formInput.vue").default)
Vue.component(
    "form-textarea",
    require("./components/Forms/formTextarea.vue").default
)
Vue.component("form-file", require("./components/Forms/formFile.vue").default)
Vue.component(
    "form-select",
    require("./components/Forms/formSelect.vue").default
)
Vue.component(
    "form-checkbox",
    require("./components/Forms/formCheckbox.vue").default
)
Vue.component("loading", require("./components/Loading.vue").default)
Vue.component(
    "button-back",
    require("./components/Buttons/ButtonBack.vue").default
)
Vue.component("show-errors", require("./components/ShowErrors.vue").default)
Vue.component(
    "import-categories",
    require("./components/ImportCategories.vue").default
)
Vue.component("export", require("./components/Export.vue").default)
Vue.component(
    "button-change-cart",
    require("./components/Buttons/ButtonChangeCart.vue").default
)
Vue.component(
    "change-password",
    require("./components/ChangePassword.vue").default
)
Vue.component(
    "address-list-component",
    require("./components/addressListComponent.vue").default
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
