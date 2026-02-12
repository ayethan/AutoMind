<template>
    <div>
        <div class="container-fluid">
            <form @submit.prevent="updateSale">
        
                <div class="row">
                    <div class="col-sm-2">
                        <h3>
                            Sale Edit
                        </h3>
                    </div>
                    <div class="col"><button type="submit" class="btn btn-success">Save</button></div>
                </div>

                <div class="row">
                    <div class="col">
                        <hr>
                    </div>
                </div>

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

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-row form-group">
                            <label class="col-sm-4">Date</label>
                            <input type="date" class="col-sm-6 form-control" :value="sale.date">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-row form-group">
                            <label class="col-sm-4">Taxi</label>
                            <div class="col-6">
                                <input type="checkbox" class="form-control" v-model="sale.is_taxi">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-row form-group">
                            <label class="col-sm-4">Car No.</label>
                            <input type="text" class="col-sm-6 form-control" v-model="sale.car_no">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-row form-group">
                            <label class="col-sm-4">Customer</label>
                            <input type="text" class="col-sm-5 form-control" @click="showCustomerSelectModal" readonly :value="sale.customer.name">
                            <div class="col-sm-1">
                                <button type="button" class="btn btn-danger" @click="removeCustomer">
                                    <span aria-hidden="true" class="text-white">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-row form-group">
                            <label class="col-sm-4">Status</label>
                            <input type="text" class="col-sm-6 form-control" readonly :value="sale.status">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-row form-group">
                            <label class="col-sm-4">Is Paid</label>
                            <div class="col-6">
                                <input type="checkbox" class="form-control" v-model="sale.is_paid">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-row form-group">
                            <label class="col-sm-4">Model</label>
                            <input type="text" class="col-sm-6 form-control" v-model="sale.model">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-row form-group">
                            <label class="col-sm-4">Milage</label>
                            <input type="text" class="col-sm-6 form-control" v-model="sale.mileage">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-row form-group">
                            <label class="col-sm-4">Job Done By</label>
                            <input type="text" class="col-sm-6 form-control" v-model="sale.job_done_by">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-row form-group">
                            <label class="col-sm-4">Remark</label>
                            <textarea type="text" class="col-sm-6 form-control" v-model="sale.remark"></textarea>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row p-2">
                    <div class="col">
                        <h4>Products</h4>
                    </div>
                    <div class="col d-flex">
                        <button class="btn btn-success ml-auto" @click="showAddProductModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="text-center">
                                    <tr>
                                        <th>No.</th>
                                        <th>Product</th>
                                        <th style="width: 10%;">Buy Price</th>
                                        <th style="width: 10%;">Sell Price</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 40%;">Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="text-right">
                                    <tr v-for="(product, index) in sale.sale_products" v-bind:key="index">
                                        <td>{{index+1}}</td>
                                        <td class="text-left"><input class="form-control" type="text" :value="product.name" readonly></td>
                                        <td><input class="form-control" type="number" v-model="product.buy_price"></td>
                                        <td><input class="form-control" type="number" v-model="product.sell_price"></td>
                                        <td><input class="form-control" type="number" v-model="product.qty"></td>
                                        <td><input class="form-control" type="text" v-model="product.remark"></td>
                                        <td><a class="btn btn-sm btn-danger text-white" @click="removeProduct(product.id)">Remove</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row p-2">
                    <div class="col">
                        <h4>External Products</h4>
                    </div>
                    <div class="col d-flex">
                        <button class="btn btn-success ml-auto" @click="showAddExtProductModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="text-center">
                                    <tr>
                                        <th>No.</th>
                                        <th>Product</th>
                                        <th style="width: 10%;">Buy Price</th>
                                        <th style="width: 10%;">Sell Price</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 40%;">Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="text-right">
                                    <tr v-for="(eproduct, index) in sale.sale_external_products" v-bind:key="index">
                                        <td>{{index+1}}</td>
                                        <td class="text-left"><input class="form-control" type="text" :value="eproduct.name" readonly></td>
                                        <td><input class="form-control" type="number" v-model="eproduct.buy_price"></td>
                                        <td><input class="form-control" type="number" v-model="eproduct.sell_price"></td>
                                        <td><input class="form-control" type="number" v-model="eproduct.qty"></td>
                                        <td><input class="form-control" type="text" v-model="eproduct.remark"></td>
                                        <td><a class="btn btn-sm btn-danger text-white" @click="removeExternalProduct(eproduct.id)">Remove</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row p-2">
                    <div class="col">
                        <h4>Services</h4>
                    </div>
                    <div class="col d-flex">
                        <button class="btn btn-success ml-auto" @click="showAddServiceModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="text-center">
                                    <tr>
                                        <th>No.</th>
                                        <th>Service</th>
                                        <th style="width: 10%;">Price</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 40%;">Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="text-right">
                                    <tr v-for="(service, index) in sale.sale_services" v-bind:key="index">
                                        <td>{{index+1}}</td>
                                        <td class="text-left"><input class="form-control" type="text" :value="service.service.name" readonly></td>
                                        <td><input class="form-control" type="number" v-model="service.price"></td>
                                        <td><input class="form-control" type="number" v-model="service.qty"></td>
                                        <td><input class="form-control" type="text" v-model="service.remark"></td>
                                        <td><a class="btn btn-sm btn-danger text-white" @click="removeService(service.id)">Remove</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row p-2">
                    <div class="col">
                        <h4>Expenses</h4>
                    </div>
                    <div class="col d-flex">
                        <button class="btn btn-success ml-auto" @click="showExpenseAddModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="text-center">
                                    <tr>
                                        <th>No.</th>
                                        <th>Date</th>
                                        <th style="width: 10%;">Expense Type</th>
                                        <th style="width: 10%;">Amount</th>
                                        <th style="width: 40%;">Remark</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="text-right">
                                    <tr v-for="(expense, index) in sale.expenses" v-bind:key="index">
                                        <td>{{index+1}}</td>
                                        <td><input class="form-control" readonly :value="expense.date"></td>
                                        <td class="text-left"><input class="form-control" type="text" :value="expense.expense_type.name" readonly></td>
                                        <td><input class="form-control" readonly type="number" :value="expense.amount"></td>
                                        <td><input class="form-control" readonly type="text" :value="expense.remark"></td>
                                        <td><a class="btn btn-sm btn-danger text-white" @click="removeExpense(expense.id)">Remove</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-sm-6 offset-sm-6 d-flex">
                        <div class="form-group form-row ml-auto">
                            <label for="sale.sub_total" class="col-sm-6 pull-right">Sub Total</label>
                            <input type="text" class="form-control col-sm-6" :value="sale.sub_total" id="sale.sub_total" readonly>    
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 offset-sm-6 d-flex">
                        <div class="form-group form-row ml-auto">
                            <label for="sale.sub_total" class="col-sm-6 pull-right">Discount</label>
                            <input type="text" class="form-control col-sm-6" :value="sale.discount" id="sale.sub_total" readonly>    
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 offset-sm-6 d-flex">
                        <div class="form-group form-row ml-auto">
                            <label for="sale.sub_total" class="col-sm-6 pull-right">Grand Total</label>
                            <input type="text" class="form-control col-sm-6" :value="sale.total" id="sale.sub_total" readonly>    
                        </div>
                    </div>
                </div>

            </form>


        </div> <!-- Container Ends -->
    </div>
</template>

<script>
export default {
  name: "SaleShow",
  data() {
    return {
      sale: {
        customer: {},
        sale_products: [],
        sale_external_products: [],
        sale_services: [],
        sale_expenses: [],
        created_at: {},
        updated_at: {}
      },
      errors: null
    };
  },
  async created() {
    try {
      var response = await axios.get(`/api/sales/${id}`);
      this.sale = response.data;
    } catch (e) {
      this.errors = error.response.data.errors;
    }
  }
};
</script>

