import axios from 'axios';

const quotationModule = {
    namespaced: true,
    state: {
        quotations: [],
        quotation: null,
        loading: false,
        error: null,
    },
    mutations: {
        SET_QUOTATIONS(state, quotations) {
            state.quotations = quotations;
        },
        SET_QUOTATION(state, quotation) {
            state.quotation = quotation;
        },
        ADD_QUOTATION(state, quotation) {
            state.quotations.push(quotation);
        },
        UPDATE_QUOTATION(state, updatedQuotation) {
            const index = state.quotations.findIndex(q => q.id === updatedQuotation.id);
            if (index !== -1) {
                state.quotations.splice(index, 1, updatedQuotation);
            }
        },
        REMOVE_QUOTATION(state, id) {
            state.quotations = state.quotations.filter(q => q.id !== id);
        },
        SET_LOADING(state, status) {
            state.loading = status;
        },
        SET_ERROR(state, message) {
            state.error = message;
        },
        CLEAR_ERROR(state) {
            state.error = null;
        }
    },
    actions: {
        async fetchAllQuotations({ commit }) {
            commit('SET_LOADING', true);
            commit('CLEAR_ERROR');
            try {
                const response = await axios.get('/api/quotations');
                commit('SET_QUOTATIONS', response.data.data);
            } catch (error) {
                commit('SET_ERROR', 'Failed to fetch quotations: ' + (error.response?.data?.message || error.message));
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async fetchQuotationById({ commit }, id) {
            commit('SET_LOADING', true);
            commit('CLEAR_ERROR');
            try {
                const response = await axios.get(`/api/quotations/${id}`);
                commit('SET_QUOTATION', response.data.data);
            } catch (error) {
                commit('SET_ERROR', 'Failed to fetch quotation: ' + (error.response?.data?.message || error.message));
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async createQuotation({ commit }, quotationData) {
            commit('SET_LOADING', true);
            commit('CLEAR_ERROR');
            try {
                const response = await axios.post('/api/quotations', quotationData);
                commit('ADD_QUOTATION', response.data.data);
                return response.data.data;
            } catch (error) {
                commit('SET_ERROR', 'Failed to create quotation: ' + (error.response?.data?.message || error.message));
                throw error;
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async updateQuotation({ commit }, { id, quotationData }) {
            commit('SET_LOADING', true);
            commit('CLEAR_ERROR');
            try {
                const response = await axios.put(`/api/quotations/${id}`, quotationData);
                commit('UPDATE_QUOTATION', response.data.data);
                return response.data.data;
            } catch (error) {
                commit('SET_ERROR', 'Failed to update quotation: ' + (error.response?.data?.message || error.message));
                throw error;
            } finally {
                commit('SET_LOADING', false);
            }
        },
        async deleteQuotation({ commit }, id) {
            commit('SET_LOADING', true);
            commit('CLEAR_ERROR');
            try {
                await axios.delete(`/api/quotations/${id}`);
                commit('REMOVE_QUOTATION', id);
            } catch (error) {
                commit('SET_ERROR', 'Failed to delete quotation: ' + (error.response?.data?.message || error.message));
                throw error;
            } finally {
                commit('SET_LOADING', false);
            }
        }
    },
    getters: {
        allQuotations: state => state.quotations,
        getQuotation: state => state.quotation,
        isLoading: state => state.loading,
        hasError: state => !!state.error,
        errorMessage: state => state.error,
    }
};

export default quotationModule;
