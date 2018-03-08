// Imports
import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import Vuetify from 'vuetify';

// Components
import App from './App.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';

// Css
import 'vuetify/dist/vuetify.min.css';

// Usages
Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(Vuetify, {
    theme: {
        primary: '#29a79c',
        secondary: '#b0bec5',
        accent: '#8c9eff',
        error: '#b71c1c'
    }
})

axios.defaults.baseURL = 'http://over-surgery.test/api';
const router = new VueRouter({
    routes: [{
        path: '/',
        name: 'loginHome',
        component: Login,
        meta: {
            auth: false
        }
    }, {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false
        }
    }, {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    }, {
        path: '/home',
        name: 'home',
        component: Home,
        meta: {
            auth: true
        }
    }]
});
Vue.router = router;
Vue.use(require('@websanova/vue-auth'), {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});
App.router = Vue.router;
console.log(App);
new Vue({
    el: '#app',
    router,
    data() {
        return {
            bgc: {
                backgroundColor: '#29a79c'
            }
        }
    },
    render: h => h(App)
});