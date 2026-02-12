<template>
  <div>
    <div class="container-fluid">
      <form id="sale_edit_form">
        <div class="row">
          <div class="col-sm-2">
            <h3>Sale Edit</h3>
          </div>
          <div class="col d-flex">
            <router-link class="btn btn-secondary" :to="`/sales/${sale.id}/expenses`">Expenses</router-link>
            <router-link class="btn btn-secondary" :to="`/sales/${sale.id}/payments`">Payments</router-link>
          </div>
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
                  <strong>{{item.join('\n')}}</strong>
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
              <input
                type="date"
                class="col-sm-6 form-control"
                v-model="sale.date"
                @input="updateSale"
              >
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <div class="form-check">
                <input
                  type="checkbox"
                  class="form-check-input"
                  @input="updateSale"
                  v-model="sale.is_taxi"
                  id="is_taxi"
                >
                <label for="is_taxi" class="form-check-label">Taxi</label>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-row form-group">
              <label class="col-sm-4">Car No.</label>
              <input
                type="text"
                class="col-sm-6 form-control"
                v-model="sale.car.car_no"
                @input="updateSale"
              >
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-row form-group">
              <label class="col-sm-4">Customer</label>
              <input
                type="text"
                class="col-sm-5 form-control"
                @click="showCustomerSelectModal"
                readonly
                :value="sale.customer.name"
              >
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
              <label class="col-sm-4">Model</label>
              <div class="col-sm-6 form-control" >{{sale.car.model}}</div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-row form-group">
              <label class="col-sm-4">Milage</label>
              <input
                type="text"
                class="col-sm-6 form-control"
                v-model="sale.mileage"
                @input="updateSale"
              >
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <div class="form-row form-group">
              <label class="col-sm-4">Job Done By</label>
              <input
                type="text"
                class="col-sm-6 form-control"
                v-model="sale.job_done_by"
                @input="updateSale"
              >
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-row form-group">
              <label class="col-sm-4">Remark</label>
              <textarea
                type="text"
                class="col-sm-6 form-control"
                v-model="sale.remark"
                @input="updateSale"
              ></textarea>
            </div>
          </div>
        </div>

        <hr>

        <div class="row p-2">
          <div class="col">
            <h4>Products</h4>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <Search
              v-model="query"
              :onSearch="onSearch"
              :onSelect="onSelect"
              :items="filtered_products"
              :onCancel="onCancel"
              :placeholder="'Type Product Name or Code'"
              v-if="sale.status_name != 'Closed'"
            />
          </div>
        </div>

        <div class="row pt-2">
          <div class="col">
            <div class="table-responsive">
              <table class="table">
                <thead class="text-center">
                  <tr>
                    <th>No.</th>
                    <th>Code</th>
                    <th>Product</th>
                    <th style="width: 40%;">Remark</th>
                    <th style="width: 10%;">Price</th>
                    <th style="width: 10%;">Qty</th>
                    <th>Total</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody class="text-right">
                  <tr v-for="(product, index) in sale.sale_products" v-bind:key="index">
                    <td>{{index+1}}</td>
                    <td class="text-center">{{product.product.code}}</td>
                    <td class="text-center">{{product.name}}</td>
                    <td>
                      <input
                        @input="updateSale"
                        class="form-control"
                        type="text"
                        v-model="product.remark"
                      >
                    </td>
                    <td>
                      <input
                        @input="updateSale"
                        class="form-control text-right"
                        type="number"
                        min="1"
                        v-model="product.sell_price"
                      >
                    </td>
                    <td>
                      <input
                        @input="updateSale"
                        class="form-control text-right"
                        type="number"
                        min="1"
                        v-model="product.qty"
                      >
                    </td>
                    <td>{{product.qty * product.sell_price}}</td>
                    <td>
                      <button
                        type="button"
                        class="btn btn-danger text-white"
                        @click="removeProduct(product.id)"
                      >&times;</button>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6">Sub Total</td>
                    <td>{{sale.sub_total}}</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="6">Tax</td>
                    <td>{{sale.tax}}</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="6">Discount</td>
                    <input
                      @input="updateSale"
                      type="number"
                      min="0"
                      step="1"
                      class="form-control text-right my-auto"
                      v-model="sale.discount"
                    >
                    <td></td>
                  </tr>
                  <tr>
                    <td colspan="6">Grand Total</td>
                    <td>{{sale.total}}</td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-11 d-flex">
            <a
              class="btn btn-warning ml-auto"
              v-if="sale.status_name == 'Open'"
              @click="closeSale"
            >Close The Sale</a>
            <a
              class="btn btn-secondary ml-auto text-white"
              v-else-if="sale.status_name == 'Closed'"
              @click="openSale"
            >Reopen</a>
          </div>
        </div>
      </form>
    </div>
    <!-- Container Ends -->
    <CustomerAddModal ref="customerAddModal" @customerSelected="selectCustomer" />
  </div>
</template>

<script>
import Search from "./../../components/Search.vue";
import CustomerAddModal from "./components/CustomerAddModal";
import _ from 'lodash';

export default {
  name: "SaleEdit",
  data() {
    return {
        sale: {
            customer: {},
            sale_products: [],
            sale_external_products: [],
            sale_services: [],
            sale_expenses: [],
            created_at: {},
            updated_at: {},
            car: {}
        },
        errors: null,
        status: {},
        filtered_products: [],
        query: ""
    };
  },
  async created() {
    try {
        this.sale = (await axios.get(`/api/sales/${this.$route.params.id}`)).data;
        this.status = (await axios.get(`/api/sales/status`)).data;

    } catch (e) {
      this.errors = {
        serer_error: [e.message]
      };
    }
  },
    updated() {
        this.$nextTick(function() {
            this.disableInputs();
        });
    },
  components: {
      Search,
      CustomerAddModal
  },

  methods: {
    updateSale: _.debounce(function() {
        var app = this;
        app.errors = null;
        var sale_id = this.$route.params.id;
        axios.put(`/api/sales/${sale_id}`, app.sale)
        .then((response) => {
            app.sale = response.data;
        })
        .catch((error) => {
                app.errors = error.response.data.errors;
        });
    }, 500),

    addProduct(id) {
      var app = this;
      var sale_id = this.$route.params.id;
      var payload = {
        product_id: id
      };
      axios
        .post(`/api/sales/${sale_id}/products`, payload)
        .then(response => {
          app.sale = response.data;
        })
        .catch(error => {
            app.errors = error.response.data.errors;
        });
    },

    removeProduct(id) {
      var app = this;
      var sale_id = this.$route.params.id;
      axios
        .delete(`/api/sales/${sale_id}/products/${id}`)
        .then(response => {
          app.sale = response.data;
        })
        .catch(error => {
            app.errors = error.response.data.errors;

        });
    },

    selectCustomer(id) {
      var app = this;
      var sale_id = this.$route.params.id;
      var payload = {
        customer_id: id
      };
      axios
        .post(`/api/sales/${sale_id}/customers`, payload)
        .then(response => {
          app.sale = response.data;
        })
        .catch(error => {
            app.errors = error.response.data.errors;

        });
    },

    removeCustomer() {
      var app = this;
      var sale_id = this.$route.params.id;
      axios
        .delete(`/api/sales/${sale_id}/customers`)
        .then(response => {
          app.sale = response.data;
        })
        .catch(error => {
            app.errors = error.response.data.errors;

        });
    },

    showCustomerSelectModal() {
        if(this.sale.status_name == "Closed") {
            return;
        }
        this.$refs.customerAddModal.show();
    },

    onSelect(id) {
        this.addProduct(id);
    },

    onCancel() {
        this.filtered_products = [];
        this.query = "";
    },

    onSearch: _.debounce(function(value) {
        if(value.length <= 0) {
            this.onCancel();
        }
        var vm  = this;
        axios.get(`/api/products/search?q=${value}&take=5`)
        .then(function(response) {
            vm.filtered_products = response.data.map(function(product) {
                return {
                    id: product.id,
                    name: `${product.name} (${product.code})`
                };
            });
        }).catch(function(error) {
            vm.errors = error.response.data.errors;
        });

    }, 300),

    closeSale() {
        var vm = this;
        axios
        .put(`/api/sales/${this.$route.params.id}/make-closed`)
        .then(function(response) {
            vm.sale = response.data;
        })
        .catch(function(error) {
            vm.errors = error.response.data.errors;
        });
    },

    openSale() {
        var vm = this;
        axios
        .put(`/api/sales/${this.$route.params.id}/make-open`)
        .then(function(response) {
            vm.sale = response.data;
        })
        .catch(function(error) {
            vm.errors = error.response.data.errors;
        });
    },

    disableInputs() {
        var form = document.getElementById("sale_edit_form");
        var elements = form.elements;

        var make_disabled = this.sale.status_name == "Closed";

        for(var i = 0; i < elements.length; i++) {
            elements[i].disabled = make_disabled;
        }
    }
  },

  watch: {
    query: function(value) {
        this.onSearch(value);
    }
  }
};
</script>

