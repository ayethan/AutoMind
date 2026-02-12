<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h3>Expenses</h3>
                </div>
                <div class="col">
                    <div class="d-flex">
                        <router-link to="/expenses/create" class="btn btn-success ml-auto">
                            <i class="fa fa-fw fa-plus"></i> Add
                        </router-link>
                    </div>
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
            <div class="row mt-3">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="text-center">
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Expense Type</th>
                                    <th>Amount</th>
                                    <th style="width: 30%;">Remark</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr v-for="(expense, index) in expenses" :key="index">
                                    <td class="text-right">{{expense.id}}</td>
                                    <td>{{expense.date}}</td>
                                    <td>{{expense.expense_type.name}}</td>
                                    <td class="text-right">{{expense.amount}}</td>
                                    <td class="text-left">{{expense.remark}}</td>
                                    <td>
                                        <router-link :to="getEditLink(expense.id)"><i class="fas fas-fw fa-edit"></i></router-link>
                                        <a @click="showDeleteModal(expense.id)" class="text-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
        </div>

        <!-- Modal -->
        <div class="modal" tabindex="-1" role="dialog" id="expenseDelModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure to delete this Expense?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete this expense. You won't be able to recover after deleted. Are you sure?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" @click="deleteExpense(expense_to_delete)">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from "./../../components/Pagination.vue";
export default {
    name: "expenseIndex",
    data() {
        return {
            expense_to_delete: null,
            errors: null

        }
    },
    components: {
        Pagination
    },
    created () {
        this.initialize();
    },
    methods: {
        paginate(page) {
            this.$store.dispatch("expense/getExpenses", {page: page});
        },
        async initialize() {
            if(!this.$store.state.expense.loaded) {
                await this.$store.dispatch("expense/getExpenses");            
            }
        },
        getEditLink(id) {
            return `/expenses/${id}/edit`;
        },
        showDeleteModal(id) {
            this.expense_to_delete = id;
            $('#expenseDelModal').modal('show');
        },
        deleteExpense(id) {
            var app = this;
            axios.delete(`/api/expenses/${id}`)
            .then((response) => {
                $('#expenseDelModal').modal('hide');
                app.expense_to_delete = null;
                this.$store.dispatch("expense/reload");
            })
            .catch((error) => {
                app.errors = error.response.data.errors;
            });
        }
    },
    computed: {
        expenses() {
            return this.$store.state.expense.expenses;
        },
        pagination_meta() {
            return this.$store.state.expense.pagination_meta;
        }
    }
}
</script>
