export default {
    namespaced: true,

    state: {
        sales: [],
        meta: {
            total: 0,
            last_page: 0,
            from: 0,
            to: 0,
            current_page: 0
        },
        status: "",
        filter: {
            paid_status: "",
            status: ""
        },
        loaded: false
    },

    mutations: {
        setSales(state, payload) {
            state.sales = payload;
        },

        setMeta(state, payload) {
            state.meta = payload;
        },

        removeSaleByID(state, payload) {
            _.remove(state.sales, (sale) => {
                sale.id == payload.id;
            });
        },

        setStatus(state, payload) {
            state.status = payload;
        },

        setFilterStatus(state, payload) {
            state.filter.status = payload;
        },

        setFilterPaidStatus(state, payload) {
            state.filter.paid_status = payload;
        },

        setLoaded(state, payload) {
            state.loaded = payload;
        }
    },

    getters: {
        getSalesCount(state) {
            return state.sales.length;
        }
    },

    actions: {
        getSales(context, payload = {}) {
            if(payload.page == context.state.meta.current_page) {
                return; 
            }
            const new_param = Object.assign({}, context.state.filter, payload);
            axios.get('/api/sales', {params: new_param})
            .then((response) => {
                context.commit('setSales', response.data.data);
                context.commit('setMeta', response.data.meta);
                context.commit('setStatus', response.data.status);
                context.commit('setFilterStatus', response.data.filter.status);
                context.commit('setFilterPaidStatus', response.data.filter.paid_status);
                context.commit("setLoaded", true); 
            });
        },

        reload(context) {
            context.dispatch('getSales');
        },

        setFilterStatus(context, payload = "") {
            context.commit('setFilterStatus', payload);
        },
         
        setFilterPaidStatus(context, payload = "") {
            context.commit('setFilterPaidStatus', payload);
        }
    }
}