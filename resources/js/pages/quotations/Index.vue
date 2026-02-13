<template>
    <div class="card">
        <div class="card-header">
            Quotations
            <router-link to="/quotations/create" class="btn btn-primary btn-sm float-right">Add New</router-link>
        </div>
        <div class="card-body">
            <div v-if="isLoading" class="text-center">Loading...</div>
            <div v-else-if="hasError" class="text-danger">{{ errorMessage }}</div>
            <div v-else>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="quotation in allQuotations" :key="quotation.id">
                            <td>{{ quotation.id }}</td>
                            <td>{{ quotation.title }}</td>
                            <td>{{ quotation.customer ? quotation.customer.name : 'N/A' }}</td>
                            <td>{{ quotation.quotation_date | formatDate }}</td>
                            <td>{{ quotation.total_amount | formatCurrency }}</td>
                            <td>{{ quotation.status }}</td>
                            <td>
                                <router-link :to="`/quotations/${quotation.id}`" class="btn btn-info btn-sm">View</router-link>
                                <router-link :to="`/quotations/${quotation.id}/edit`" class="btn btn-warning btn-sm">Edit</router-link>
                                <button @click="deleteQuotation(quotation.id)" class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: "QuotationIndex",
    created() {
        this.fetchAllQuotations();
    },
    computed: {
        ...mapGetters('quotation', ['allQuotations', 'isLoading', 'hasError', 'errorMessage']),
    },
    methods: {
        ...mapActions('quotation', ['fetchAllQuotations', 'deleteQuotation']),
        async deleteQuotation(id) {
            if (confirm('Are you sure you want to delete this quotation?')) {
                try {
                    await this.$store.dispatch('quotation/deleteQuotation', id);
                    alert('Quotation deleted successfully!');
                } catch (error) {
                    alert('Error deleting quotation: ' + (error.response?.data?.message || error.message));
                }
            }
        },
        formatDate(dateString) {
            if (!dateString) return '';
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        },
        formatCurrency(value) {
            if (typeof value !== 'number') return value;
            return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
        }
    },
    filters: {
        formatDate(value) {
            if (!value) return '';
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(value).toLocaleDateString(undefined, options);
        },
        formatCurrency(value) {
            if (typeof value !== 'number') return value;
            return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
        }
    }
};
</script>
