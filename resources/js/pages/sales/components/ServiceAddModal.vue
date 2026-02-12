<template>
    <div class="modal" tabindex="-1" role="dialog" id="serviceAddModal">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h3 class="modal-title">Services</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <table class="table table-bordered table-striped">
                                        <thead class="text-center">
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Price</th>
                                                <th style="width: 30%;">Remark</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-right">
                                            <tr v-for="(service, index) in services" :key="index">
                                                <td>{{service.id}}</td>
                                                <td class="text-center">{{service.name}}</td>
                                                <td>{{service.description}}</td>
                                                <td>{{service.price}}</td>
                                                <td class="text-center">{{service.remark}}</td>
                                                <td class="text-center">
                                                    <a @click="addService(service.id)" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
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
                </div>
            </div>
        </div>
</template>


<script>
import Pagination from "./../../../components/Pagination.vue";

export default {
    components: {
        Pagination
    },
    name: "ServiceAddModal",
    async created() {
        if(!this.$store.state.service.loaded) {
            await this.$store.dispatch("service/getServices");
        }
    },
    computed: {
        pagination_meta() {
            return this.$store.state.service.pagination_meta;
        },
        services() {
            if(!this.$store.state.service.loaded) {
                this.$store.dispatch("service/getServices");
            }
            return this.$store.state.service.services;
        }
    },
    methods: {
        paginate(page) {
            this.$store.dispatch("service/getServices", {page: page});
        },
        addService(id) {
            this.$emit('serviceAdded', id);
        },
        show() {
            $('#serviceAddModal').modal('show');
        },
        hide() {
            $('#serviceAddModal').modal('hide');
        }
    }
    
}
</script>
