<template>
    <div class="modal" tabindex="-1" role="dialog" id="customerAddModal">
        <div class="modal-dialog modal-lg modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="modal-title">Products</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="text-center">
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th style="width: 30%;">Remark</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <tr v-for="(customer, index) in customers" :key="index">
                                                <td class="text-right">{{customer.id}}</td>
                                                <td>{{customer.name}}</td>
                                                <td>{{customer.phone}}</td>
                                                <td>{{customer.remark}}</td>
                                                <td>
                                                    <a @click="selectCustomer(customer.id)" class="btn btn-sm btn-success">Select</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="d-inline">
                                    <Pagination :callback="paginate" 
                                    :total="pagination_meta.total" 
                                    :current="pagination_meta.current_page"
                                    :last="pagination_meta.last_page" 
                                    :from="pagination_meta.from" 
                                    :to="pagination_meta.to"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from "./../../../components/Pagination.vue";
export default {
    name: "CustomerAddModal",
    data() {
        return {
            errors: null
        }
    },
    components: {
        Pagination
    },
    created () {
        this.initialize();
    },
    methods: {
        paginate(page) {
            this.$store.dispatch("customer/getCustomers", {page: page});
        },
        async initialize() {
            if(!this.$store.state.customer.loaded) {
                await this.$store.dispatch("customer/getCustomers");            
            }
        },
        selectCustomer(id) {
            this.$emit("customerSelected", id);
        },
        show() {
            $("#customerAddModal").modal('show');
        },
        hide() {
            $("#customerAddModal").modal('hide');
        }
    },
    computed: {
        customers() {
            return this.$store.state.customer.customers;
        },
        pagination_meta() {
            return this.$store.state.customer.pagination_meta;
        }
    }
}
</script>
