export default {
    namespaced: true,

    state: {
        categories: [],
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
        getCategoriesCount(state) {
            return state.categories.length;
        }
    },

    mutations: {
        setCategories(state, payload) {
            state.categories = payload;
        },

        setMeta(state, payload) {
            state.pagination_meta = payload;
        },

        removeCategoryByID(state, payload) {
            _.remove(state.categories, (category) => {
                category.id == payload.id;
            });
        },

        setLoaded(state, payload) {
            state.loaded = payload;
        }
    },

    actions: {
        getCategories(context, payload = {}) {
            if(payload.page == context.state.pagination_meta.current_page) {
               return; 
            }
            const new_param = Object.assign({}, context.state.filter, payload);
            axios.get(`/api/categories`, {params: new_param}).then((response) => { 
                context.commit("setCategories", response.data.data);
                context.commit("setMeta", response.data.meta); 
                context.commit("setLoaded", true); 
            });
        },

        removeCategoryByID(context, payload = {}) {
            context.commit("removeCategoryByID", payload);
        },

        reload(context) {
            context.dispatch("getCategories");
        }
    }
}