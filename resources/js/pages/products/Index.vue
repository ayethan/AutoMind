<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h3>Products</h3>
                </div>
                <div class="col">
                    <div class="d-flex">
                        <router-link to="/products/create" class="btn btn-success ml-auto">
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
                <div class="col"></div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="text-center">
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Stock</th>
                                    <th>Buy Price</th>
                                    <th>Sell Price</th>
                                    <th style="width: 30%;">Remark</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-right">
                                <tr v-for="(product, index) in products" :key="index">
                                    <td>{{product.id}}</td>
                                    <td class="text-center">{{product.code}}</td>
                                    <td class="text-center">{{product.name}}</td>
                                    <td>{{product.stock}}</td>
                                    <td>{{product.buy_price}}</td>
                                    <td>{{product.sell_price}}</td>
                                    <td class="text-center">{{product.remark}}</td>
                                    <td class="text-center">
                                        <router-link :to="getEditLink(product.id)"><i class="fas fas-fw fa-edit"></i></router-link>
                                        <a @click="showDeleteModal(product.id)" class="text-danger"><i class="fas fa-trash"></i></a>
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
        <div class="modal" tabindex="-1" role="dialog" id="productDelModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure to delete this product?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete this product. You won't be able to recover after deleted. Are you sure?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" @click="deleteProduct(product_to_delete)">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from "./../../components/Pagination.vue";
export default {
    name: "ProductIndex",
    data() {
        return {
            product_to_delete: null,
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
            this.$store.dispatch("product/getProducts", {page: page});
        },
        async initialize() {
            if(!this.$store.state.product.loaded) {
                await this.$store.dispatch("product/getProducts");            
            }
        },
        getEditLink(id) {
            return `/products/${id}/edit`;
        },
        showDeleteModal(id) {
            this.product_to_delete = id;
            $('#productDelModal').modal('show');
        },
        deleteProduct(id) {
            var app = this;
            axios.delete(`/api/products/${id}`)
            .then((response) => {
                $('#productDelModal').modal('hide');
                app.product_to_delete = null;
                this.$store.dispatch("product/reload");
            })
            .catch((error) => {
                app.errors = error.response.data.errors;
            });
        }
    },
    computed: {
        products() {
            return this.$store.state.product.products;
        },
        pagination_meta() {
            return this.$store.state.product.pagination_meta;
        }
    }
}
</script>
