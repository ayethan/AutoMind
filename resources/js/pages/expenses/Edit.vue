<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>Edit Expense</h3>
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
                <form @submit.prevent="updateExpense">
                    <div class="form-group">
                        <label for="old_expense.date">Date</label>
                        <input type="date" required id="old_expense.date" v-model="old_expense.date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="old_expense.expense_type_id">Expense Type</label>
                        <select class="form-control" required v-model="old_expense.expense_type_id" name="old_expense.expense_type_id" id="old_expense.expense_type_id">
                            <option value="">--Expense Type--</option>
                            <option v-for="(expense_type, key) in expense_types" :key="key" :value="expense_type.id">{{expense_type.name}}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="old_expense.amount">Amount</label>
                        <input type="number" required min="1" id="old_expense.amount" v-model="old_expense.amount" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="old_expense.remark">Remark</label>
                        <textarea id="old_expense.remark" v-model="old_expense.remark" class="form-control"></textarea>
                    </div>

                    <div class="form-group d-flex">
                        <button type="submit" class="btn btn-success ml-auto">Update</button>
                    </div>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="errors">
                        <ul>
                            <li v-for="(item, index) in errors" :key="index">
                                <strong> {{item.join('\n')}} </strong>
                            </li>
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import validate from "validate.js";
    export default {
        name: "ExpenseEdit",
        async created() {
            try {
                var response = await this.getExpenseByID(this.$route.params.id);
                this.old_expense = response.data;
            } catch(e) {
                this.errors= {
                    "serer_error": [
                        e.message
                    ]
                };
            }
        },
        data() {    
            return {
                old_expense: {
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
            }
        },
        created() {
            this.initialize();
        },
        methods: {
            updateExpense() {
                this.errors = null;

                const errors = validate(this.old_expense, this.validation_rules);

                if(errors) {
                    this.errors = errors;
                    return;
                }

                var app = this;
                axios.put(`/api/expenses/${this.$route.params.id}`, this.old_expense)
                .then((response) => {
                    app.$store.dispatch('expense/reload');
                    app.$router.push({ path: "/expenses" });
                })
                .catch((error) => {
                    app.errors = error.response.data.errors;
                });
            },

            async initialize() {
                try {
                    var response_expense_types = await axios.get("/api/expense-types/all");
                    this.expense_types = response_expense_types.data;
                    var response_expense = await axios.get(`/api/expenses/${this.$route.params.id}`);
                    this.old_expense = response_expense.data;
                } catch (e) {
                    this.errors = error.response.data.errors;
                }
            }
        }
    }
</script>
