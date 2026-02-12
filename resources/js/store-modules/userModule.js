export default {
    namespaced: true, 

    state: {
        users:[],
        pagination_meta: {
            total: 0,
            last_page: 0,
            from: 0,
            to: 0,
            current_page: 0
        },
        loaded: false
    },

    getters: {
        getUsersCount(state) {
            return state.users.length;
        } 
    },

    mutations: {
        setUsers(state, payload) {
            state.users = payload;
        },

        setMeta(state, payload) {
            state.pagination_meta = payload;
        },

        removeUserByID(state, payload) {
            _.remove(state.services, (user) => {
                user.id == payload.id;
            });
        },

        setLoaded(state, payload) {
            state.loaded = payload;
        }
    },

    actions: {
        getUsers(context, payload = {}) {
            if(payload.page == context.state.pagination_meta.current_page) {
                return;
            }

            const new_param = Object.assign({}, context.state.filter, payload);
            axios.get("/api/users", {params: new_param})
            .then((response) => {
                context.commit("setUsers", response.data.data);
                context.commit("setMeta", response.data.meta);
                context.commit("setLoaded", true); 
            });
        },

        removeUserByID(context, payload = {}) {
            context.commit("removeUserByID", payload);
        },

        reload(context) {
            context.dispatch("getUsers");
        }
    }
}