// Imports
// require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import Vuetify from 'vuetify';

// Pages
import App from './App.vue';
import Home from './components/Home.vue';
import Register from './components/pages//Register.vue';
import Login from './components/pages//Login.vue';
import Dashboard from './components/pages//Dashboard.vue';
import Availability from './components/pages//Availability.vue';
import Appointments from './components/pages//Appointments.vue';
import Prescriptions from './components/pages//Prescriptions.vue';
import Tests from './components/pages//Tests.vue';
import Chat from './components/pages//Chat.vue';
import Calendar from './components/pages//Calendar.vue';

// Utility
import fullCalendar from 'vue-fullcalendar'
import Confirm from "./components/utility/Confirm.vue";
import MaterialSnackbar from "./components/utility/MaterialSnackbar.vue";
import ChatMessage from "./components/utility/ChatMessage.vue";
import ChatLog from "./components/utility/ChatLog.vue";
import ChatComposer from "./components/utility/ChatComposer.vue";

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

// Components
Vue.component('full-calendar', fullCalendar);
Vue.component('v-confirm', Confirm);
Vue.component('v-material-snackbar', MaterialSnackbar);
Vue.component('chat-message', ChatMessage);
Vue.component('chat-log', ChatLog);
Vue.component('chat-composer', ChatComposer);

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
                    icon: "dashboard",
                    title: "Dashboard",
                    auth: true,
                    roles: [
                        'patient'
                    ],
                    icon: "dashboard"
                }
            }, {
                path: '/availability',
                name: 'availability',
                component: Availability,
                meta: {
                    icon: "person",
                    title: "Availability",
                    auth: true,
                    roles: [
                        'patient'
                    ]
                }
            }, {
                path: '/appointments',
                name: 'appointments',
                component: Appointments,
                meta: {
                    icon: "event",
                    title: "Appointments",
                    auth: true,
                    roles: [
                        'patient'
                    ]
                }
            }, {
                path: '/prescriptions',
                name: 'prescriptions',
                component: Prescriptions,
                meta: {
                    icon: "attachment",
                    title: "Prescriptions",
                    auth: true,
                    roles: [
                        'patient'
                    ]
                }
            }, {
                path: '/tests',
                name: 'tests',
                component: Tests,
                meta: {
                    icon: "history",
                    title: "Tests",
                    auth: true,
                    roles: [
                        'patient'
                    ]
                }
            }, {
                path: '/calendar',
                name: 'calendar',
                component: Calendar,
                meta: {
                    icon: "calendar",
                    title: "Calendar",
                    auth: true,
                    roles: [
                        'receptionist'
                    ]
                }
            }, {
                path: '/chat',
                name: 'chat',
                component: Chat,
                meta: {
                    icon: "message",
                    title: "Chat",
                    auth: true,
                    roles: [
                        'patient',
                        'receptionist'
                    ]
                }
            }
        ]
    }]
});
router.beforeEach((to, from, next) => {
    let user = JSON.parse(window.localStorage.getItem('auth-user'));
    if (to.meta.roles && !to.meta.roles.includes(user.account_type)) {
        switch (user.account_type) {
            case 'patient':
                next('dashboard');
                break;
            case 'receptionist':
                next('calendar');
                break;
        }
    }
    next();
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