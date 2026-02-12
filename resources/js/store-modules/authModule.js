import {getUser} from "../helpers/auth";

const user = getUser();

 var authModule = {
    namespaced: true,

    state: {
        current_user: user,
        auth_error: null,
        is_logged_in: !!user,
        loading: false
    },

    mutations: {
        login(state) {
            state.loading = true;
            state.auth_error = null;
        },
        loginSuccess(state, payload) {
            state.auth_error = null;
            state.is_logged_in = true;
            state.current_user = Object.assign({}, payload.user, {token: payload.access_token});

            localStorage.setItem("user", JSON.stringify(state.current_user));
        },
        loginFailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state) {
            localStorage.removeItem("user");
            state.loading = false;
            state.current_user = null;
        }
    },

    actions: {
        login: function(context) {
            context.commit("login");
        },
        logout: function(context) {
            context.commit("logout");
        }
    }
}

export default authModule;