
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import 'bootstrap/dist/css/bootstrap.css'
import 'shards-ui/dist/css/shards.css'
import 'element-ui/lib/theme-chalk/index.css'
import 'vue-datetime/dist/vue-datetime.css'
import 'es6-promise/auto'

import Vue2Filters from 'vue2-filters'
import Date from 'datejs'
import axios from 'axios'
import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueMaterialIcon from 'vue-material-icon'
import ShardsVue from 'shards-vue'
import VueForm from 'vue-form'
import ElementUI from 'element-ui'
import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'
import { DataTables, DataTablesServer } from 'vue-data-tables'
import VueDataTables from 'vue-data-tables'
import Datetime from 'vue-datetime'
import MonthPicker from 'vue-month-picker'
import MonthPickerInput from 'vue-month-picker'
import VoerroTagsInput from '@voerro/vue-tagsinput'
import VueRouter from 'vue-router'
import Auth from '@js/auth.js'
import Router from '@js/routes/router.js'
import App from '@js/components/App.vue'

locale.use(lang)

Vue.router = Router

Vue.use(Vue2Filters)
Vue.use(Date)
Vue.use(VueRouter)
Vue.use(VueAxios, axios)
Vue.use(VueAuth, Auth)
Vue.use(ShardsVue)
Vue.use(VueForm)
Vue.use(ElementUI)
Vue.use(DataTables)
Vue.use(DataTablesServer)
Vue.use(VueDataTables)
Vue.use(Datetime)
Vue.use(MonthPicker)
Vue.use(MonthPickerInput)

Vue.component('tags-input', VoerroTagsInput)
Vue.component(VueMaterialIcon.name, VueMaterialIcon)

axios.defaults.baseURL = process.env.MIX_APP_URL +'/api'

const app = new Vue({
    el      : '#app',
    router  : Router,
    render  : app => app(App)
})