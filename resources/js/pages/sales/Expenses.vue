<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-2">
        <h3>Expenses</h3>
      </div>
        <div class="col d-flex">
            <button @click="showExpenseAddModal" class="btn btn-secondary">Add Expense</button>
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
          <label class="col-sm-4"><strong>Date</strong></label>
          <div class="col-sm-6">{{sale.date}}</div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-row form-group">
          <label class="col-sm-4"><strong>Taxi</strong></label>
          <div class="col-sm-6">{{sale.is_taxi ? "Yes": "No"}}</div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-row form-group">
          <label class="col-sm-4"><strong>Car No.</strong></label>
          <div class="col-sm-6">{{sale.car.car_no}}</div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-row form-group">
          <label class="col-sm-4"><strong>Customer</strong></label>
          <div>{{sale.customer.name}}</div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-row form-group">
          <label class="col-sm-4"><strong>Model</strong></label>
          <div class="col-sm-6">{{sale.car.model}}</div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-row form-group">
          <label class="col-sm-4"><strong>Milage</strong></label>
          <div class="col-sm-6">{{sale.mileage}}</div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <table class="table table-striped table-bordered">
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
            <tbody class="text-center">
                <tr v-for="(expense, index) in sale.expenses" v-bind:key="index">
                    <td>{{index+1}}</td>
                    <td>{{expense.date}}</td>
                    <td class="text-left">{{expense.expense_type.name}}</td>
                    <td>{{expense.amount}}</td>
                    <td>{{expense.remark}}</td>
                    <td><a class="btn btn-danger text-white" @click="removeExpense(expense.id)">&times;</a></td>
                </tr>
            </tbody>
        </table>
      </div>
    </div>

    <ExpenseAddModal ref="expenseAddModal" @expenseAdded="addExpense"/>

  </div>
</template>

<script>
import ExpenseAddModal from "./components/ExpenseAddModal.vue";
export default {

  name: "SaleExpenses",
  components: {
    ExpenseAddModal
  },
  data: function() {
    return {
      sale: {
        customer: {},
        car: {},
        expenses: [],
      },
      errors: null,

    };
  },
  async created() {
    this.sale = (await axios.get(`/api/sales/${this.$route.params.id}`)).data;
  },
  methods: {
    showExpenseAddModal() {
      this.$refs.expenseAddModal.show();
    },
    
    addExpense(new_expense) {
      var app = this;
      var sale_id = this.$route.params.id;
      axios
        .post(`/api/sales/${sale_id}/expenses`, new_expense)
        .then(response => {
          app.sale = response.data;
        })
        .catch(error => {
          app.errors = error.response.data.errors;
        });
    },

    removeExpense(id) {
      var app = this;
      var sale_id = this.$route.params.id;
      axios
        .delete(`/api/sales/${sale_id}/expenses/${id}`)
        .then(response => {
          app.sale = response.data;
        })
        .catch(error => {
          app.errors = error.response.data.errors;
        });
    }
  }
};
</script>

