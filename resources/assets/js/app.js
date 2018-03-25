// Imports
import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import Vuetify from 'vuetify';
import fullCalendar from 'vue-fullcalendar'

// Components
import App from './App.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import Dashboard from './components/Dashboard.vue';
import Availability from './components/Availability.vue';
import Calendar from './components/Calendar.vue';

// Css
import 'vuetify/dist/vuetify.min.css';

// Usages
Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(Vuetify, {
    theme: {
        primary: "#009688",
        secondary: "#B2DFDB",
        accent: "#90A4AE",
        error: "#FB8C00",
        warning: "#FFB74D",
        info: "#4DB6AC",
        success: "#78909C"
    }
});
Vue.component('full-calendar', fullCalendar);

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
        },
        children: [
            {
                path: '/dashboard',
                name: 'dashboard',
                component: Dashboard,
                meta: {
                    auth: true
                }
            }, {
                path: '/availability',
                name: 'availability',
                component: Availability,
                meta: {
                    auth: true
                }
            }, {
                path: '/calendar',
                name: 'calendar',
                component: Calendar,
                meta: {
                    auth: true
                }
            }
        ]
    }]
});
Vue.router = router;
Vue.use(require('@websanova/vue-auth'), {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});
App.router = Vue.router;
new Vue({
    el: '#app',
    router,
    render: h => h(App)
});