<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>Sales</h3>
            </div>
            <div class="col d-flex">
                <router-link to="/sales/create" class="btn btn-success ml-auto">
                    <i class="fas fas-fw fa-plus"></i> Add
                </router-link>
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
        <div class="row">
            <div class="col-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <label for="status" class="input-group-text">Status</label>
                    </div>
                    <select v-model="filter_status" id="status" @change="filterSearch()" class="form-control">
                        <option value="">All</option>
                        <option v-for="(sta, index) in status" :key="index" :value="index">{{sta}}</option>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <label for="paid_status" class="input-group-text">Paid Status</label>
                    </div>
                    <select v-model="filter_paid_status" @change="filterSearch()" id="paid_status" class="form-control">
                        <option value="">All</option>
                        <option value=true>Paid</option>
                        <option value=false>Unpaid</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Car No.</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Paid</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(sale, index) in sales" :key="index">
                            <td class="text-right">{{sale.id}}</td>
                            <td>{{sale.car_no}}</td>
                            <td>{{sale.date}}</td>
                            <td>{{sale.customer.name}}</td>
                            <td>{{sale.total}}</td>
                            <td>{{sale.status_string}}</td>
                            <td>
                                <input type="checkbox" disabled v-model="sale.is_paid">
                            </td>
                            <td>
                                <router-link :to="getEditLink(sale.id)">
                                    <i class="fas fas-fw fa-edit"></i>
                                </router-link>
                                <a href="javascript:void(0);" @click="exportInvoice(sale.id)"><i class="fas fa-cloud-download-alt"></i></a>
                                <a href="javascript:void(0);" @click="showDeleteModal(sale.id)" class="text-danger"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
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


        <!-- Modal -->
        <div class="modal" tabindex="-1" role="dialog" id="saleDelModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure to delete this sale?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete this sale. You won't be able to recover after deleted. Are you sure?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" @click="deleteSale(sale_to_delete)">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import _ from "lodash";
import Pagination from "./../../components/Pagination.vue";

export default {
  name: "SaleIndex",
  created() {
    this.initialize();
  },
  components: {
    Pagination
  },
  data() {
    return {
      errors: null,
      sale_to_delete: null,
      filter_status: "",
      filter_paid_status: ""
    };
  },
  methods: {
    async initialize() {
      if (!this.$store.state.sale.loaded) {
        await this.$store.dispatch("sale/getSales");
      }
    },

    deleteSale(id) {
      var app = this;
      axios
        .delete(`/api/sales/${id}`)
        .then(response => {
          $("#saleDelModal").modal("hide");
          app.sale_to_delete = null;
          this.$store.dispatch("sale/reload");
        })
        .catch(error => {
            app.errors = error.response.data.errors;
        });
    },

    getSales() {
      this.$store.dispatch("sale/getSales");
    },

    getEditLink(id) {
      return `/sales/${id}/edit`;
    },

    getShowLink(id) {
      return `/sales/${id}`;
    },

    getExportLink(id) {
      return `/sales/${id}/exports/invoice`;
    },
    showDeleteModal(id) {
      this.sale_to_delete = id;
      $("#saleDelModal").modal("show");
    },

    paginate(page) {
      this.$store.dispatch("sale/getSales", { page: page });
    },

    filterSearch: _.debounce(function() {
      this.getSales();
    }, 500),

    exportInvoice(id) {
        //Gist from https://gist.github.com/javilobo8/097c30a233786be52070986d8cdb1743
      axios({
        url: `/api/sales/${id}/exports/invoice`,
        method: "GET",
        responseType: "blob"
      }).then(response => {
        console.log(response.headers);
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        const filename = response.headers["content-disposition"].split("=")[1];
        link.setAttribute("download", filename);
        document.body.appendChild(link);
        link.click();
      });
    }
  },
  computed: {
    sales() {
      return this.$store.state.sale.sales;
    },
    pagination_meta() {
      return this.$store.state.sale.meta;
    },
    status() {
      return this.$store.state.sale.status;
    }
  },
  watch: {
    filter_status: function(new_val, old_val) {
      this.$store.dispatch("sale/setFilterStatus", new_val);
    },
    filter_paid_status: function(new_val, old_val) {
      this.$store.dispatch("sale/setFilterPaidStatus", new_val);
    }
  }
};
</script>

