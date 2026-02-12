<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h3>Cars</h3>
                </div>
                <div class="col">
                    <div class="d-flex">
                        <router-link to="/cars/create" class="btn btn-success ml-auto">
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
                                    <th>Car No</th>
                                    <th>Model</th>
                                    <th>Car Made</th>
                                    <th>Customer</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr v-for="(car, index) in cars" :key="index">
                                    <td class="text-right">{{car.id}}</td>
                                    <td>{{car.car_no}}</td>
                                    <td>{{car.model}}</td>
                                    <td>{{car.car_made.name}}</td>
                                    <td>{{car.customer.name}}</td>
                                    <td>
                                        <router-link :to="getEditLink(car.id)"><i class="fas fas-fw fa-edit"></i></router-link>
                                        <a @click="showDeleteModal(car.id)" class="text-danger"><i class="fas fa-trash"></i></a>
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
        <div class="modal" tabindex="-1" role="dialog" id="carDelModal">
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
                        <button type="button" class="btn btn-danger" @click="deleteCar(car_to_delete)">Delete</button>
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
            car_to_delete: null,
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
            this.$store.dispatch("car/getCars", {page: page});
        },
        async initialize() {
            if(!this.$store.state.car.loaded) {
                await this.$store.dispatch("car/getCars");            
            }
        },
        getEditLink(id) {
            return `/cars/${id}/edit`;
        },
        showDeleteModal(id) {
            this.car_to_delete = id;
            $('#carDelModal').modal('show');
        },
        deleteCar(id) {
            var app = this;
            axios.delete(`/api/cars/${id}`)
            .then((response) => {
                $('#carDelModal').modal('hide');
                app.car_to_delete = null;
                this.$store.dispatch("car/reload");
            })
            .catch((error) => {
                app.errors = error.response.data.errors;
            });
        }
    },
    computed: {
        cars() {
            return this.$store.state.car.cars;
        },
        pagination_meta() {
            return this.$store.state.car.pagination_meta;
        }
    }
}
</script>
