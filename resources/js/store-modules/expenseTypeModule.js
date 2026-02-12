export default {
    namespaced: true,

    state: {
        expense_types: [],
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
        getExpenseTypesCount(state) {
            return state.expense_types.length;
        }
    },

    mutations: {
        setExpenseTypes(state, payload) {
            state.expense_types = payload;
        },

        setMeta(state, payload) {
            state.pagination_meta = payload;
        },

        removeExpenseTypeByID(state, payload) {
            _.remove(state.expenses_types, (expense) => {
                expense.id == payload.id;
            });
        },

        setLoaded(state, payload) {
            state.loaded = payload;
        }
    },

    actions: {
        getExpenseTypes(context, payload = {}) {
            if(payload.page == context.state.pagination_meta.current_page) {
                return; 
            }
            const new_param = Object.assign({}, context.state.filter, payload);
            axios.get("/api/expense-types", {params: new_param}).then((response) => { 
                context.commit("setExpenseTypes", response.data.data);
                context.commit("setMeta", response.data.meta); 
                context.commit("setLoaded", true); 
            });
        },

        removeExpenseTypeByID(context, payload = {}) {
            context.commit("removeExpenseTypeByID", payload);
        },

        reload(context) {
            context.dispatch("getExpenseTypes");
        }
    }
}