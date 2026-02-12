export default {
    namespaced: true,

    state: {
        cars: [],
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
        getCarsCount(state) {
            return state.cars.length;
        }
    },

    mutations: {
        setCars(state, payload) {
            state.cars = payload;
        },

        setMeta(state, payload) {
            state.pagination_meta = payload;
        },

        removeCarByID(state, payload) {
            _.remove(state.cars, (car) => {
                car.id == payload.id;
            });
        },

        setLoaded(state, payload) {
            state.loaded = payload;
        }
    },

    actions: {
        getCars(context, payload = {}) {
            if(payload.page == context.state.pagination_meta.current_page) {
                return; 
            }
            const new_param = Object.assign({}, context.state.filter, payload);
            axios.get("/api/cars", {params: new_param}).then((response) => { 
                context.commit("setCars", response.data.data);
                context.commit("setMeta", response.data.meta); 
                context.commit("setLoaded", true); 
            });
        },

        removeCarByID(context, payload = {}) {
            context.commit("removeCarByID", payload);
        },

        reload(context) {
            context.dispatch("getCars");
        }
    }
}