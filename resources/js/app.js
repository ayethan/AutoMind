require('./bootstrap');

import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import { createStore } from 'vuex';

import { routes } from './routes';
import StoreData from './store';
import MainComponent from './components/MainComponent';
import { setAuthenticationHeader, getUser } from './helpers/auth';

// 1. Setup Vuex Store (PLAIN OBJECT -> createStore)
const store = createStore(StoreData);

// 2. Setup Router (Array -> createRouter)
const router = createRouter({
    history: createWebHistory(),
    routes,
});

/* STOP! Ensure there are NO lines like these below:
   Vue.use(VueRouter); <--- DELETE THIS
   Vue.use(Vuex);      <--- DELETE THIS
*/

// 3. Navigation Guard
router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);

    // Safety check: ensure store and auth state exist
    const user = store.getters['auth/currentUser'];

    if (requiresAuth && !user) {
        return next('/login');
    }
    next();
});

// 4. Create the App Instance
const app = createApp(MainComponent);

// 5. Global Properties & Auth Setup
const user = getUser();
if (user) {
    setAuthenticationHeader(user.token);
    // Directly setting state for the initial boot
    store.commit('auth/setInitialUser', user);
}

app.config.globalProperties.axios = axios;

// 6. Axios Interceptor
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            store.commit('auth/logout');
            router.push('/login');
        }
        return Promise.reject(error);
    }
);

// 7. Mount Plugins to the INSTANCE (This is the new .use())
app.use(router);
app.use(store);
app.mount('#app');

require('./jquery.easing');
require('./sb-admin');