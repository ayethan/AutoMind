export default {
    namespaced: true,

    state: {
        customers: [],
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
        getCustomersCount(state) {
            return state.customers.length;
        }
    },

    mutations: {
        setCustomers(state, payload) {
            state.customers = payload;
        },

        setMeta(state, payload) {
            state.pagination_meta = payload;
        },

        removeCustomerByID(state, payload) {
            _.remove(state.customers, (customer) => {
                customer.id == payload.id;
            });
        },

        setLoaded(state, payload) {
            state.loaded = payload;
        }
    },

    actions: {
        getCustomers(context, payload = {}) {
            if(payload.page == context.state.pagination_meta.current_page) {
               return; 
            }
            const new_param = Object.assign({}, context.state.filter, payload);
            axios.get(`/api/customers`, {params: new_param}).then((response) => { 
                context.commit("setCustomers", response.data.data);
                context.commit("setMeta", response.data.meta); 
                context.commit("setLoaded", true); 
            });
        },

        removeCustomerByID(context, payload = {}) {
            context.commit("removeCustomerByID", payload);
        },

        reload(context) {
            context.dispatch("getCustomers");
        }
    }
}