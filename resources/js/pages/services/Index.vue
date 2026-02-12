<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h3>Services</h3>
                </div>
                <div class="col">
                    <div class="d-flex">
                        <router-link to="/services/create" class="btn btn-success ml-auto">
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
                                    <th style="width:25%;">Description</th>
                                    <th>Price</th>
                                    <th style="width:25%;">Remark</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-right">
                                <tr v-for="(service, index) in services" :key="index">
                                    <td>{{service.id}}</td>
                                    <td class="text-center">{{service.name}}</td>
                                    <td class="text-center">{{service.description}}</td>
                                    <td>{{service.price}}</td>
                                    <td class="text-center">{{service.remark}}</td>
                                    <td class="text-center">
                                        <router-link :to="getEditLink(service.id)"><i class="fas fas-fw fa-edit"></i></router-link>
                                        <a @click="showDeleteModal(service.id)" class="text-danger"><i class="fas fa-trash"></i></a>
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
        <div class="modal" tabindex="-1" role="dialog" id="serviceDelModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure to delete this service?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete this service. You won't be able to recover after deleted. Are you sure?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" @click="deleteService(service_to_delete)">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from "./../../components/Pagination.vue";
export default {
    name: "ServiceIndex",
    data() {
        return {
            service_to_delete: null,
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
            this.$store.dispatch("service/getServices", {page: page});
        },
        async initialize() {
            if(!this.$store.state.service.loaded) {
                await this.$store.dispatch("service/getServices");            
            }
        },
        getEditLink(id) {
            return `/services/${id}/edit`;
        },
        showDeleteModal(id) {
            this.service_to_delete = id;
            $('#serviceDelModal').modal('show');
        },
        deleteService(id) {
            var app = this;
            axios.delete(`/api/services/${id}`)
            .then((response) => {
                $('#serviceDelModal').modal('hide');
                app.service_to_delete = null;
                app.$store.dispatch("service/reload");
            })
            .catch((error) => {
                app.errors = error.response.data.errors;
            });
        }
    },
    computed: {
        services() {
            return this.$store.state.service.services;
        },
        pagination_meta() {
            return this.$store.state.service.pagination_meta;
        }
    }
}
</script>
