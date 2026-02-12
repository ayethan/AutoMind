<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h3>Sub Categories</h3>
                </div>
                <div class="col">
                    <div class="d-flex">
                        <router-link to="/sub-categories/create" class="btn btn-success ml-auto">
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
                                    <th>Category</th>
                                    <th>Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr v-for="(sub_category, index) in sub_categories" :key="index">
                                    <td class="text-right">{{sub_category.id}}</td>
                                    <td>{{sub_category.category.name}}</td>
                                    <td>{{sub_category.name}}</td>
                                    <td>
                                        <router-link :to="getEditLink(sub_category.id)"><i class="fas fas-fw fa-edit"></i></router-link>
                                        <a @click="showDeleteModal(sub_category.id)" class="text-danger"><i class="fas fa-trash"></i></a>
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
        <div class="modal" tabindex="-1" role="dialog" id="subCategoryDelModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure to delete this sub category?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to delete this sub category. You won't be able to recover after deleted. Are you sure?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" @click="deleteCategory(sub_category_to_delete)">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from "./../../components/Pagination.vue";
export default {
    name: "SubCategoryIndex",
    data() {
        return {
            sub_category_to_delete: null,
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
            this.$store.dispatch("sub_category/getSubCategories", {page: page});
        },
        async initialize() {
            if(!this.$store.state.sub_category.loaded) {
                await this.$store.dispatch("sub_category/getSubCategories");            
            }
        },
        getEditLink(id) {
            return `/sub-categories/${id}/edit`;
        },
        showDeleteModal(id) {
            this.sub_category_to_delete = id;
            $('#subCategoryDelModal').modal('show');
        },
        deleteCategory(id) {
            var app = this;
            axios.delete(`/api/sub-categories/${id}`)
            .then((response) => {
                $('#subCategoryDelModal').modal('hide');
                app.sub_category_to_delete = null;
                app.$store.dispatch("sub_category/reload");
            })
            .catch((error) => {
                app.errors = error.response.data.errors;
            });
        }
    },
    computed: {
        sub_categories() {
            return this.$store.state.sub_category.sub_categories;
        },
        pagination_meta() {
            return this.$store.state.sub_category.pagination_meta;
        }
    }
}
</script>
