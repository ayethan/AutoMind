<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h3>Users</h3>
                </div>
                <div class="col">
                    <div class="d-flex">
                        <router-link to="/users/create" class="btn btn-success ml-auto">
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
                                    <th>Email</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-right">
                                <tr v-for="(user, index) in users" :key="index">
                                    <td>{{user.id}}</td>
                                    <td class="text-center">{{user.name}}</td>
                                    <td class="text-center">{{user.email}}</td>
                                    <td class="text-center">
                                        <router-link :to="getEditLink(user.id)"><i class="fas fas-fw fa-edit"></i></router-link>
                                        <router-link :to="getPasswordResetLink(user.id)"
                                         title="Reset Password" ><i class="fas fa-redo fas-fw"></i></router-link>
                                        <a @click="showDeleteModal(user.id)" class="text-danger"><i class="fas fa-trash"></i></a>
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
        <div class="modal" tabindex="-1" role="dialog" id="userDelModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure to delete this user?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete this user. You won't be able to recover after deleted. Are you sure?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" @click="deleteUser(user_to_delete)">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from "./../../components/Pagination.vue";
export default {
    name: "UserIndex",
    data() {
        return {
            user_to_delete: null,
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
            this.$store.dispatch("user/getUsers", {page: page});
        },
        async initialize() {
            if(!this.$store.state.user.loaded) {
                await this.$store.dispatch("user/getUsers");            
            }
        },
        getEditLink(id) {
            return `/users/${id}/edit`;
        },
        getPasswordResetLink(id) {
            return `/users/${id}/password/reset`;
        },
        showDeleteModal(id) {
            this.user_to_delete = id;
            $('#userDelModal').modal('show');
        },
        deleteUser(id) {
            var app = this;
            axios.delete(`/api/users/${id}`)
            .then((response) => {
                $('#userDelModal').modal('hide');
                app.user_to_delete = null;
                this.$store.dispatch("user/reload");
            })
            .catch((error) => {
                app.errors = {
                    server_error : [
                        error.message
                    ]
                };
            });
        }
    },
    computed: {
        users() {
            return this.$store.state.user.users;
        },
        pagination_meta() {
            return this.$store.state.user.pagination_meta;
        }
    }
}
</script>
