export default {
    namespaced: true,

    state: {
        expenses: [],
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
        getExpensesCount(state) {
            return state.expenses.length;
        }
    },

    mutations: {
        setExpenses(state, payload) {
            state.expenses = payload;
        },

        setMeta(state, payload) {
            state.pagination_meta = payload;
        },

        removeExpenseByID(state, payload) {
            _.remove(state.expenses, (expense) => {
                expense.id == payload.id;
            });
        },

        setLoaded(state, payload) {
            state.loaded = payload;
        }
    },

    actions: {
        getExpenses(context, payload = {}) {
            if(payload.page == context.state.pagination_meta.current_page) {
                return; 
            }
            const new_param = Object.assign({}, context.state.filter, payload);
            axios.get("/api/expenses", {params: new_param}).then((response) => { 
                context.commit("setExpenses", response.data.data);
                context.commit("setMeta", response.data.meta); 
                context.commit("setLoaded", true); 
            });
        },

        removeExpenseByID(context, payload = {}) {
            context.commit("removeExpenseByID", payload);
        },

        reload(context) {
            context.dispatch("getExpenses");
        }
    }
}