export default {
    namespaced: true,

    state: {
        sub_categories: [],
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
        getSubCategoriesCount(state) {
            return state.sub_categories.length;
        }
    },

    mutations: {
        setSubCategories(state, payload) {
            state.sub_categories = payload;
        },

        setMeta(state, payload) {
            state.pagination_meta = payload;
        },

        removeSubCategoryByID(state, payload) {
            _.remove(state.sub_categories, (subCategory) => {
                subCategory.id == payload.id;
            });
        },

        setLoaded(state, payload) {
            state.loaded = payload;
        }
    },

    actions: {
        getSubCategories(context, payload = {}) {
            if(payload.page == context.state.pagination_meta.current_page) {
               return; 
            }
            const new_param = Object.assign({}, context.state.filter, payload);
            axios.get(`/api/sub-categories`, {params: new_param}).then((response) => { 
                context.commit("setSubCategories", response.data.data);
                context.commit("setMeta", response.data.meta); 
                context.commit("setLoaded", true); 
            });
        },

        removeSubCategoryByID(context, payload = {}) {
            context.commit("removeSubCategoryByID", payload);
        },

        reload(context) {
            context.dispatch("getSubCategories");
        }
    }
}