<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h3>Supplier</h3>
                </div>
                <div class="col">
                    <div class="d-flex">
                        <router-link to="/suppliers/create" class="btn btn-success ml-auto">
                            <i class="fa fa-fw fa-plus"></i> Add
                        </router-link>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row" v-if="errors">
                <div class="col">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
                            <li v-for="(item, index) in errors" :key="index">
                                <strong> {{item.join('\n')}} </strong>
                            </li>
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
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
                                <tr v-for="(supplier, index) in suppliers" :key="index">
                                    <td class="text-right">{{supplier.id}}</td>
                                    <td>{{supplier.name}}</td>
                                    <td>{{supplier.phone}}</td>
                                    <td>{{supplier.remark}}</td>
                                    <td>
                                        <router-link :to="getEditLink(supplier.id)"><i class="fas fas-fw fa-edit"></i></router-link>
                                        <a @click="showDeleteModal(supplier.id)" class="text-danger"><i class="fas fa-trash"></i></a>
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

        <!-- Modal -->
        <div class="modal" tabindex="-1" role="dialog" id="supplierDelModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure to delete this supplier?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete this supplier. You won't be able to recover after deleted. Are you sure?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" @click="deleteSupplier(supplier_to_delete)">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from "./../../components/Pagination.vue";
export default {
    name: "SupplierIndex",
    data() {
        return {
            supplier_to_delete: null,
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
            this.$store.dispatch("supplier/getSuppliers", {page: page});
        },
        async initialize() {
            if(!this.$store.state.supplier.loaded) {
                await this.$store.dispatch("supplier/getSuppliers");            
            }
        },
        getEditLink(id) {
            return `/suppliers/${id}/edit`;
        },
        showDeleteModal(id) {
            this.supplier_to_delete = id;
            $('#supplierDelModal').modal('show');
        },
        deleteSupplier(id) {
            var app = this;
            axios.delete(`/api/suppliers/${id}`)
            .then((response) => {
                $('#supplierDelModal').modal('hide');
                app.supplier_to_delete = null;
                app.$store.dispatch("supplier/reload");
            })
            .catch((error) => {
                app.errors = error.response.data.errors;
            });
        }
    },
    computed: {
        suppliers() {
            return this.$store.state.supplier.suppliers;
        },
        pagination_meta() {
            return this.$store.state.supplier.pagination_meta;
        }
    }
}
</script>
