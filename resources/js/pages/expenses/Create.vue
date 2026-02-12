<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>New Expense</h3>
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
            <div class="col-lg-4 col-xs-12">
                <form @submit.prevent="addExpense">
                    <div class="form-group">
                        <label for="new_expense.date">Date</label>
                        <input type="date" required id="new_expense.date" v-model="new_expense.date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="new_expense.expense_type_id">Expense Type</label>
                        <select class="form-control" required v-model="new_expense.expense_type_id" name="new_expense.expense_type_id" id="new_expense.expense_type_id">
                            <option value="">--Expense Type--</option>
                            <option v-for="(expense_type, key) in expense_types" :key="key" :value="expense_type.id">{{expense_type.name}}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="new_expense.amount">Amount</label>
                        <input type="number" required min="1" id="new_expense.amount" v-model="new_expense.amount" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="new_expense.remark">Remark</label>
                        <textarea id="new_expense.remark" v-model="new_expense.remark" class="form-control"></textarea>
                    </div>

                    <div class="form-group d-flex">
                        <button type="submit" class="btn btn-success ml-auto">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import validate from "validate.js";
export default {
  name: "ExpenseCreate",
  data() {
    return {
      new_expense: {
        expense_type_id: "",
        amount: "",
        date: "",
        remark: ""
      },
      expense_types: [],
      validation_rules: {
        amount: {
          presence: true,
          numericality: {
            onlyInteger: true,
            greaterThan: 0,
            message: "must be an integer."
          }
        },
        expense_type_id: {
          presence: true,
          numericality: {
            onlyInteger: true,
            message: "^Please Choose Expense Type"
          }
        }
      },
      errors: null
    };
  },

  created() {
    this.initialize();
  },
  methods: {
    addExpense() {
      var app = this;
      app.errors = null;
      const errors = validate(this.new_expense, this.validation_rules);

      if (errors) {
        this.errors = errors;
        return;
      }

      axios.post("/api/expenses", app.new_expense)
      .then(response => {
          app.$router.push({ path: "/expenses" });
      })
      .catch((e) => {
        app.errors = {
          server_error: [e.message]
        }
      });
    },

    async initialize() {
      try {
        var response = await axios.get("/api/expense-types/all");
        this.expense_types = response.data;
      } catch (e) {
        this.errors = error.response.data.errors;
      }
    }
  }
};
</script>

