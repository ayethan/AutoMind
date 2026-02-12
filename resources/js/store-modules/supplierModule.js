export default {
    namespaced: true,

    state: {
        suppliers: [],
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
        getSuppliersCount(state) {
            return state.suppliers.length;
        }
    },

    mutations: {
        setSuppliers(state, payload) {
            state.suppliers = payload;
        },

        setMeta(state, payload) {
            state.pagination_meta = payload;
        },

        removeSupplierByID(state, payload) {
            _.remove(state.suppliers, (customer) => {
                customer.id == payload.id;
            });
        },

        setLoaded(state, payload) {
            state.loaded = payload;
        }
    },

    actions: {
        getSuppliers(context, payload = {}) {
            if(payload.page == context.state.pagination_meta.current_page) {
               return; 
            }
            const new_param = Object.assign({}, context.state.filter, payload);
            axios.get(`/api/suppliers`, {params: new_param}).then((response) => { 
                context.commit("setSuppliers", response.data.data);
                context.commit("setMeta", response.data.meta); 
                context.commit("setLoaded", true); 
            });
        },

        removeSupplierByID(context, payload = {}) {
            context.commit("removeSupplierByID", payload);
        },

        reload(context) {
            context.dispatch("getSuppliers");
        }
    }
}