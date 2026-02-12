<template>
        <div class="modal" tabindex="-1" role="dialog" id="newExpenseAddModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form @submit.prevent="addExpense">
                        <div class="modal-header">
                            <h3 class="modal-title">Add Expense</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
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
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="expense_add_errors">
                                <ul>
                                    <li v-for="(item, index) in expense_add_errors" :key="index">
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
    name: "ExpenseAddModal",
    data() {
        return {
            new_expense: {
                expense_type_id: "",
                amount: 0,
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
            expense_add_errors: null
        };
    },

    created() {
        this.initialize();
    },
    methods: {
        addExpense() {
            const errors = validate(this.new_expense, this.validation_rules);

            if(errors) {
                this.expense_add_errors = errors;
                return;
            }

            this.$emit('expenseAdded', this.new_expense);
        },
        show() {
            $('#newExpenseAddModal').modal('show');
        },
        hide() {
            $('#newExpenseAddModal').modal('hide');
        },
        async initialize() {
            try {
                var response = await axios.get('/api/expense-types/all');
                this.expense_types = response.data;
            } catch(e) {
                this.expense_add_errors= {
                    "serer_error": [
                        e.message
                    ]
                };
            }
        }
    }
}
</script>

