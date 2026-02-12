<template>
        <div class="modal" tabindex="-1" role="dialog" id="newPaymentModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form @submit.prevent="addPayment">
                        <div class="modal-header">
                            <h3 class="modal-title">Add Payment</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="new_payment.date">Date</label>
                                <input type="date" required id="new_payment.date" v-model="new_payment.date" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="new_payment.amount">Amount</label>
                                <input type="number" required min="1" id="new_payment.amount" v-model="new_payment.amount" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="new_payment.remark">Remark</label>
                                <textarea id="new_payment.remark" v-model="new_payment.remark" class="form-control"></textarea>
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
            new_payment: {
                sale_id: "",
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
                }
            },
            expense_add_errors: null
        };
    },

    methods: {
        addPayment() {
            const errors = validate(this.new_payment, this.validation_rules);

            if(errors) {
                this.expense_add_errors = errors;
                return;
            }

            this.$emit('paymentAdded', this.new_payment);
        },
        show() {
            $('#newPaymentModal').modal('show');
        },
        hide() {
            $('#newPaymentModal').modal('hide');
        }
    }
}
</script>

