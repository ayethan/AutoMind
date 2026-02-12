export default {
    namespaced: true,

    state: {
        car_mades: [],
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
        getCarMadeCount(state) {
            return state.car_mades.length;
        }
    },

    mutations: {
        setCarMades(state, payload) {
            state.car_mades = payload;
        },

        setMeta(state, payload) {
            state.pagination_meta = payload;
        },

        removeCarMadeByID(state, payload) {
            _.remove(state.car_mades, (car_made) => {
                car_made.id == payload.id;
            });
        },

        setLoaded(state, payload) {
            state.loaded = payload;
        }
    },

    actions: {
        getCarMades(context, payload = {}) {
            if(payload.page == context.state.pagination_meta.current_page) {
               return; 
            }
            const new_param = Object.assign({}, context.state.filter, payload);
            axios.get(`/api/car-mades`, {params: new_param}).then((response) => { 
                context.commit("setCarMades", response.data.data);
                context.commit("setMeta", response.data.meta); 
                context.commit("setLoaded", true); 
            });
        },

        removeCarMadeByID(context, payload = {}) {
            context.commit("removeCarMadeByID", payload);
        },

        reload(context) {
            context.dispatch("getCarMades");
        }
    }
}