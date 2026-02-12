require('./bootstrap');


import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import {routes} from './routes';
import Store from './store';
import MainComponent from './components/MainComponent';
import {setAuthenticationHeader, getUser} from './helpers/auth';


Vue.use(VueRouter);
Vue.use(Vuex);

const store = new Vuex.Store(Store);

const router = new VueRouter({
    routes,
    mode: 'history'
});

router.beforeEach((to, from, next) => {
    var requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    var user = store.state.auth.current_user;

    if(requiresAuth && !user) {
        next('/login');
    } else if(to.path == '/login' && user) {
        next('/');
    } else {
        next();
    }
});

axios.interceptors.response.use(null , function (error) {
    if(error.response.status == 401) {
        store.commit('auth/logout');
    }
    return Promise.reject(error);
  });

var app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        'main-app': MainComponent
    },
    created() {
        const user = getUser();
        if(user) {
            setAuthenticationHeader(user.token);
        }
    }
});

require('./jquery.easing');
require('./sb-admin');