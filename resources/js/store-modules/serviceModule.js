export default {
    namespaced: true,

    state: {
        services: [],
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
        getServicesCount(state) {
            return state.services.length;
        }
    },

    mutations: {
        setServices(state, payload) {
            state.services = payload;
        },

        setMeta(state, payload) {
            state.pagination_meta = payload;
        },

        removeServiceByID(state, payload) {
            _.remove(state.services, (service) => {
                service.id == payload.id;
            });
        },

        setLoaded(state, payload) {
            state.loaded = payload;
        }
    },

    actions: {
        getServices(context, payload = {}) {
            if(payload.page == context.state.pagination_meta.current_page) {
                return; 
            }
            const new_param = Object.assign({}, context.state.filter, payload);
            axios.get("/api/services", {params: new_param}).then((response) => { 
                context.commit("setServices", response.data.data);
                context.commit("setMeta", response.data.meta); 
                context.commit("setLoaded", true); 
            });
        },

        removeServiceByID(context, payload = {}) {
            context.commit("removeServiceByID", payload);
        },

        reload(context) {
            context.dispatch("getServices");
        }
    }
}