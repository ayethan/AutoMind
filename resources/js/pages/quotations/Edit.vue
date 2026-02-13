<template>
    <div class="card">
        <div class="card-header">
            Edit Quotation #{{ form.id }}
            <router-link to="/quotations" class="btn btn-secondary btn-sm float-right">Back to List</router-link>
        </div>
        <div class="card-body">
            <div v-if="isLoading" class="text-center">Loading quotation data...</div>
            <div v-else-if="hasError" class="alert alert-danger">{{ errorMessage }}</div>
            <form v-else @submit.prevent="updateQuotation">
                <div class="form-group">
                    <label for="customer">Customer</label>
                    <select id="customer" class="form-control" v-model="form.customer_id" required>
                        <option value="">Select Customer</option>
                        <option v-for="customer in allCustomers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
                    </select>
                    <small class="text-danger" v-if="errors.customer_id">{{ errors.customer_id[0] }}</small>
                </div>

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" class="form-control" v-model="form.title">
                    <small class="text-danger" v-if="errors.title">{{ errors.title[0] }}</small>
                </div>

                <div class="form-group">
                    <label for="expiration_date">Expiration Date</label>
                    <input type="date" id="expiration_date" class="form-control" v-model="form.expiration_date">
                    <small class="text-danger" v-if="errors.expiration_date">{{ errors.expiration_date[0] }}</small>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" class="form-control" v-model="form.status">
                        <option value="draft">Draft</option>
                        <option value="sent">Sent</option>
                        <option value="accepted">Accepted</option>
                        <option value="rejected">Rejected</option>
                        <option value="converted">Converted</option>
                        <option value="expired">Expired</option>
                    </select>
                    <small class="text-danger" v-if="errors.status">{{ errors.status[0] }}</small>
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" class="form-control" v-model="form.notes"></textarea>
                    <small class="text-danger" v-if="errors.notes">{{ errors.notes[0] }}</small>
                </div>

                <!-- Products Section -->
                <div class="card my-4">
                    <div class="card-header">Products</div>
                    <div class="card-body">
                        <div v-for="(productItem, index) in form.products" :key="index" class="form-row mb-2 align-items-end">
                            <div class="col">
                                <label>Product</label>
                                <select class="form-control" v-model="productItem.product_id" required>
                                    <option value="">Select Product</option>
                                    <option v-for="product in allProducts" :key="product.id" :value="product.id">{{ product.name }}</option>
                                </select>
                                <small class="text-danger" v-if="errors['products.' + index + '.product_id']">{{ errors['products.' + index + '.product_id'][0] }}</small>
                            </div>
                            <div class="col">
                                <label>Quantity</label>
                                <input type="number" class="form-control" v-model.number="productItem.quantity" min="1" required>
                                <small class="text-danger" v-if="errors['products.' + index + '.quantity']">{{ errors['products.' + index + '.quantity'][0] }}</small>
                            </div>
                            <div class="col">
                                <label>Price</label>
                                <input type="number" class="form-control" v-model.number="productItem.price" min="0" step="0.01">
                                <small class="text-danger" v-if="errors['products.' + index + '.price']">{{ errors['products.' + index + '.price'][0] }}</small>
                            </div>
                            <div class="col">
                                <label>Discount</label>
                                <input type="number" class="form-control" v-model.number="productItem.discount" min="0" step="0.01">
                                <small class="text-danger" v-if="errors['products.' + index + '.discount']">{{ errors['products.' + index + '.discount'][0] }}</small>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-danger" @click="removeProduct(index)">Remove</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success btn-sm" @click="addProduct">Add Product</button>
                    </div>
                </div>

                <!-- Services Section -->
                <div class="card my-4">
                    <div class="card-header">Services</div>
                    <div class="card-body">
                        <div v-for="(serviceItem, index) in form.services" :key="index" class="form-row mb-2 align-items-end">
                            <div class="col">
                                <label>Service</label>
                                <select class="form-control" v-model="serviceItem.service_id" required>
                                    <option value="">Select Service</option>
                                    <option v-for="service in allServices" :key="service.id" :value="service.id">{{ service.name }}</option>
                                </select>
                                <small class="text-danger" v-if="errors['services.' + index + '.service_id']">{{ errors['services.' + index + '.service_id'][0] }}</small>
                            </div>
                            <div class="col">
                                <label>Quantity</label>
                                <input type="number" class="form-control" v-model.number="serviceItem.quantity" min="1" required>
                                <small class="text-danger" v-if="errors['services.' + index + '.quantity']">{{ errors['services.' + index + '.quantity'][0] }}</small>
                            </div>
                            <div class="col">
                                <label>Price</label>
                                <input type="number" class="form-control" v-model.number="serviceItem.price" min="0" step="0.01">
                                <small class="text-danger" v-if="errors['services.' + index + '.price']">{{ errors['services.' + index + '.price'][0] }}</small>
                            </div>
                            <div class="col">
                                <label>Discount</label>
                                <input type="number" class="form-control" v-model.number="serviceItem.discount" min="0" step="0.01">
                                <small class="text-danger" v-if="errors['services.' + index + '.discount']">{{ errors['services.' + index + '.discount'][0] }}</small>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-danger" @click="removeService(index)">Remove</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success btn-sm" @click="addService">Add Service</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" :disabled="isLoading">Update Quotation</button>
                <div v-if="formErrors" class="alert alert-danger mt-3">{{ formErrors }}</div>
            </form>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
    name: "QuotationEdit",
    data() {
        return {
            form: {
                id: null,
                customer_id: '',
                title: '',
                expiration_date: '',
                notes: '',
                status: 'draft',
                products: [],
                services: []
            },
            errors: {},
            formErrors: null,
        };
    },
    computed: {
        ...mapGetters('customer', { allCustomers: 'allCustomers' }),
        ...mapGetters('product', { allProducts: 'allProducts' }),
        ...mapGetters('service', { allServices: 'allServices' }),
        ...mapGetters('quotation', ['isLoading', 'getQuotation', 'hasError', 'errorMessage']),
    },
    watch: {
        getQuotation: {
            handler(newQuotation) {
                if (newQuotation) {
                    this.form.id = newQuotation.id;
                    this.form.customer_id = newQuotation.customer_id;
                    this.form.title = newQuotation.title;
                    this.form.expiration_date = newQuotation.expiration_date ? new Date(newQuotation.expiration_date).toISOString().split('T')[0] : '';
                    this.form.notes = newQuotation.notes;
                    this.form.status = newQuotation.status;
                    this.form.products = newQuotation.products.map(item => ({
                        product_id: item.product.id,
                        quantity: item.quantity,
                        price: item.price,
                        discount: item.discount,
                    }));
                    this.form.services = newQuotation.services.map(item => ({
                        service_id: item.service.id,
                        quantity: item.quantity,
                        price: item.price,
                        discount: item.discount,
                    }));
                }
            },
            immediate: true,
        }
    },
    created() {
        this.fetchData();
    },
    methods: {
        ...mapActions('customer', ['fetchAllCustomers']),
        ...mapActions('product', ['fetchAllProducts']),
        ...mapActions('service', ['fetchAllServices']),
        ...mapActions('quotation', ['fetchQuotationById', 'updateQuotation']),

        async fetchData() {
            const quotationId = this.$route.params.id;
            await Promise.all([
                this.fetchAllCustomers(),
                this.fetchAllProducts(),
                this.fetchAllServices(),
                this.fetchQuotationById(quotationId)
            ]);
        },
        addProduct() {
            this.form.products.push({ product_id: '', quantity: 1, price: 0, discount: 0 });
        },
        removeProduct(index) {
            this.form.products.splice(index, 1);
        },
        addService() {
            this.form.services.push({ service_id: '', quantity: 1, price: 0, discount: 0 });
        },
        removeService(index) {
            this.form.services.splice(index, 1);
        },
        async updateQuotation() {
            this.errors = {};
            this.formErrors = null;
            try {
                const quotationData = {
                    customer_id: this.form.customer_id,
                    title: this.form.title,
                    expiration_date: this.form.expiration_date,
                    notes: this.form.notes,
                    status: this.form.status,
                    products: this.form.products.map(item => ({
                        product_id: item.product_id,
                        quantity: item.quantity,
                        price: item.price,
                        discount: item.discount
                    })),
                    services: this.form.services.map(item => ({
                        service_id: item.service_id,
                        quantity: item.quantity,
                        price: item.price,
                        discount: item.discount
                    }))
                };

                await this.$store.dispatch('quotation/updateQuotation', {
                    id: this.form.id,
                    quotationData: quotationData
                });
                alert('Quotation updated successfully!');
                this.$router.push('/quotations');
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    this.formErrors = error.response?.data?.message || 'An unexpected error occurred.';
                }
            }
        }
    }
};
</script>
