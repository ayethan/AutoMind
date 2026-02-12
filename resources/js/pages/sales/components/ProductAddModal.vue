<template>
    <div class="modal" tabindex="-1" role="dialog" id="productAddModal">
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
                                                <td class="text-center">{{product.name}}</td>
                                                <td>{{product.stock}}</td>
                                                <td>{{product.buy_price}}</td>
                                                <td>{{product.sell_price}}</td>
                                                <td class="text-center">{{product.remark}}</td>
                                                <td class="text-center">
                                                    <a @click="addProduct(product.id)" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
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
</template>


<script>
import Pagination from "./../../../components/Pagination.vue";
export default {
    components: {
        Pagination
    },
    name: "ProductAddModal",
    computed: {
        pagination_meta() {
            return this.$store.state.product.pagination_meta;
        },
        products() {
            if(!this.$store.state.product.loaded) {
                this.$store.dispatch("product/getProducts");
            }
            return this.$store.state.product.products;
        }
    },
    methods: {
        paginate(page) {
            this.$store.dispatch("product/getProducts", {page: page});
        },
        addProduct(id) {
            this.$emit('productAdded', id);
        },
        show() {
            $('#productAddModal').modal('show');
        },
        hide() {
            $('#productAddModal').modal('hide');
        }
    }

}
</script>
