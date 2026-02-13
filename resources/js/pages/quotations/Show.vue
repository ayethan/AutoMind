<template>
    <div class="card">
        <div class="card-header">
            Quotation Details #{{ getQuotation ? getQuotation.id : '' }}
            <router-link to="/quotations" class="btn btn-secondary btn-sm float-right mr-2">Back to List</router-link>
            <router-link :to="`/quotations/${getQuotation.id}/edit`" class="btn btn-warning btn-sm float-right mr-2" v-if="getQuotation">Edit</router-link>
        </div>
        <div class="card-body">
            <div v-if="isLoading" class="text-center">Loading quotation details...</div>
            <div v-else-if="hasError" class="alert alert-danger">{{ errorMessage }}</div>
            <div v-else-if="!getQuotation" class="alert alert-warning">Quotation not found.</div>
            <div v-else>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Quotation Information</h5>
                        <p><strong>Title:</strong> {{ getQuotation.title }}</p>
                        <p><strong>Customer:</strong> {{ getQuotation.customer ? getQuotation.customer.name : 'N/A' }}</p>
                        <p><strong>Created By:</strong> {{ getQuotation.user ? getQuotation.user.name : 'N/A' }}</p>
                        <p><strong>Quotation Date:</strong> {{ getQuotation.quotation_date | formatDate }}</p>
                        <p><strong>Expiration Date:</strong> {{ getQuotation.expiration_date ? (getQuotation.expiration_date | formatDate) : 'N/A' }}</p>
                        <p><strong>Status:</strong> {{ getQuotation.status }}</p>
                        <p><strong>Notes:</strong> {{ getQuotation.notes || 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <h5>Financial Summary</h5>
                        <p><strong>Subtotal:</strong> {{ getQuotation.subtotal | formatCurrency }}</p>
                        <p><strong>Discount:</strong> {{ getQuotation.discount_amount | formatCurrency }}</p>
                        <p><strong>Tax:</strong> {{ getQuotation.tax_amount | formatCurrency }}</p>
                        <h4><strong>Total Amount:</strong> {{ getQuotation.total_amount | formatCurrency }}</h4>
                    </div>
                </div>

                <hr>

                <h5>Products</h5>
                <div v-if="getQuotation.products && getQuotation.products.length > 0">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in getQuotation.products" :key="item.product_id">
                                <td>{{ item.product ? item.product.name : 'N/A' }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>{{ item.price | formatCurrency }}</td>
                                <td>{{ item.discount | formatCurrency }}</td>
                                <td>{{ item.total | formatCurrency }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="alert alert-info">No products associated with this quotation.</div>

                <hr>

                <h5>Services</h5>
                <div v-if="getQuotation.services && getQuotation.services.length > 0">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in getQuotation.services" :key="item.service_id">
                                <td>{{ item.service ? item.service.name : 'N/A' }}</td>
                                <td>{{ item.quantity }}</td>
                                <td>{{ item.price | formatCurrency }}</td>
                                <td>{{ item.discount | formatCurrency }}</td>
                                <td>{{ item.total | formatCurrency }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="alert alert-info">No services associated with this quotation.</div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: "QuotationShow",
    created() {
        this.fetchQuotationById(this.$route.params.id);
    },
    computed: {
        ...mapGetters('quotation', ['getQuotation', 'isLoading', 'hasError', 'errorMessage']),
    },
    methods: {
        ...mapActions('quotation', ['fetchQuotationById']),
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
