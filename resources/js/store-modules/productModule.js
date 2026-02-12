export default {
    namespaced: true,

    state: {
        products: [],
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
        getProductsCount(state) {
            return state.products.length;
        }
    },

    mutations: {
        setProducts(state, payload) {
            state.products = payload;
        },

        setMeta(state, payload) {
            state.pagination_meta = payload;
        },

        removeProductByID(state, payload) {
            _.remove(state.products, (product) => {
                product.id == payload.id;
            });
        },

        setLoaded(state, payload) {
            state.loaded = payload;
        }
        
    },

    actions: {
        getProducts(context, payload = {}) {
            if(payload.page == context.state.pagination_meta.current_page) {
                return; 
            }
            const new_param = Object.assign({}, context.state.filter, payload);
            axios.get("/api/products", {params: new_param}).then((response) => { 
                context.commit("setProducts", response.data.data);
                context.commit("setMeta", response.data.meta); 
                context.commit("setLoaded", true); 
            });
        },

        removeProductByID(context, payload = {}) {
            context.commit("removeProductByID", payload);
        },
        reload(context) {
            context.dispatch("getProducts");
        }
    }
}