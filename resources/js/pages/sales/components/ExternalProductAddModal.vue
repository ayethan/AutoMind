<template>
            <!-- External Product Add Modal -->
        <div class="modal" tabindex="-1" role="dialog" id="newExternalProductModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form @submit.prevent="addExternalProduct">
                        <div class="modal-header">
                            <h3 class="modal-title">External Product</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                                <div class="form-group">
                                    <label for="new_external_product.name">Name</label>
                                    <input type="text" required id="new_external_product.name" v-model="new_external_product.name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="new_external_product.buy_price">Buy Price</label>
                                    <input type="number" required min="1" id="new_external_product.buy_price" v-model="new_external_product.buy_price" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="new_external_product.sell_price">Sell Price</label>
                                    <input type="number" required min="1" id="new_external_product.sell_price" v-model="new_external_product.sell_price" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="new_external_product.remark">Remark</label>
                                    <textarea id="new_external_product.remark" v-model="new_external_product.remark" class="form-control"></textarea>
                                </div>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="ext_product_errors">
                                <ul>
                                    <li v-for="(item, index) in ext_product_errors" :key="index">
                                        <strong> {{item.join('\n')}} </strong>
                                    </li>
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="d-flex form-group">
                                <button type="button" class="btn btn-secondary ml-auto" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success ml-1">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</template>

<script>
import validate from 'validate.js';
export default {
    name: "ExternalProductAddModal",
    data() {
        return {
            new_external_product: {
                name: "",
                buy_price: "",
                sell_price: "",
                remark: ""
            },
            validation_rules: {
                name: {
                    presence: true,
                    length:{
                        minimum: 1,
                        message: "should have at least 1 character."
                    }
                },
                buy_price: {
                    presence: true,
                    numericality: {
                        onlyInteger: true,
                        greaterThan: 0,
                        message: "must be an integer."
                    }
                },
                sell_price: {
                    presence: true,
                    numericality: {
                        onlyInteger: true,
                        greaterThan: 0,
                        message: "must be an integer."
                    }
                }
            },
            ext_product_errors: null
        };
    },
    methods: {
        addExternalProduct() {
            this.$emit('extProductAdded', this.new_external_product);
        },
        show() {
            $('#newExternalProductModal').modal('show');
        },
        hide() {
            $('#newExternalProductModal').modal('hide');
        }
    }
}
</script>

