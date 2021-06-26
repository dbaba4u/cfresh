import router from "./routes";
import index from "./Index";

require('./bootstrap');
require('./adminlte');

// window.Vue = require('vue');
import Vue from 'vue'
import VueRouter from "vue-router";

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('payment-list', require('./components/PaymentList.vue').default);
// Vue.component('example', require('./components/ExampleApp.vue').default);

/**route
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueRouter);

const app = new Vue({
    el: '#app',
    router,
    components:{
        index
    }
});
