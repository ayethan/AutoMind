<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h3>Expense Types</h3>
                </div>
                <div class="col">
                    <div class="d-flex">
                        <router-link to="/expense-types/create" class="btn btn-success ml-auto">
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
                                    <th>Name</th>
                                    <th style="width: 30%;">Remark</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr v-for="(expense_type, index) in expense_types" :key="index">
                                    <td class="text-right">{{expense_type.id}}</td>
                                    <td>{{expense_type.name}}</td>
                                    <td>{{expense_type.remark}}</td>
                                    <td>
                                        <router-link :to="getEditLink(expense_type.id)"><i class="fas fas-fw fa-edit"></i></router-link>
                                        <a @click="showDeleteModal(expense_type.id)" class="text-danger"><i class="fas fa-trash"></i></a>
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
        <div class="modal" tabindex="-1" role="dialog" id="expenseTypeDelModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure to delete this Expense Type?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete this expense. You won't be able to recover after deleted. Are you sure?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" @click="deleteExpenseType(expense_type_to_delete)">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from "./../../components/Pagination.vue";
export default {
    name: "ExpenseTypeIndex",
    data() {
        return {
            expense_type_to_delete: null,
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
            this.$store.dispatch("expense_type/getExpenseTypes", {page: page});
        },
        async initialize() {
            if(!this.$store.state.expense_type.loaded) {
                await this.$store.dispatch("expense_type/getExpenseTypes");            
            }
        },
        getEditLink(id) {
            return `/expense-types/${id}/edit`;
        },
        showDeleteModal(id) {
            this.expense_type_to_delete = id;
            $('#expenseTypeDelModal').modal('show');
        },
        deleteExpenseType(id) {
            var app = this;
            axios.delete(`/api/expense_types/${id}`)
            .then((response) => {
                $('#expenseTypeDelModal').modal('hide');
                app.expense_type_to_delete = null;
                this.$store.dispatch("expense_type/reload");
            })
            .catch((error) => {
                app.errors = error.response.data.errors;
            });
        }
    },
    computed: {
        expense_types() {
            return this.$store.state.expense_type.expense_types;
        },
        pagination_meta() {
            return this.$store.state.expense_type.pagination_meta;
        }
    }
}
</script>
