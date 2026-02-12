<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>Edit Customer</h3>
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
                <form @submit.prevent="update">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="nmae" class="form-control" v-model="old_customer.name">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" class="form-control" v-model="old_customer.phone">
                    </div>
                    <div class="form-group">
                        <label for="remark">Remark</label>
                        <textarea type="text" id="remark" class="form-control" v-model="old_customer.remark"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import validate from "validate.js";
    export default {
        name: "CustomerEdit",
        async created() {
            try {

                var response = await this.getCustomerByID(this.$route.params.id);
                this.old_customer = response.data;
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
                old_customer: {
                    name: "",
                    phone: "",
                    remark: ""
                }, 
                validation_rules: {
                    name: {
                        presence: true,
                        length: {
                            minimum: 1,
                            message: "should have at least 1 character."
                        }
                    },
                    phone: {
                        presence: true,
                        length: {
                            minimum: 1,
                            message: "should have at least 1 character."
                        }
                    }
                },
                errors: null
            }
        },
        methods: {
            update() {
                this.errors = null;

                const errors = validate(this.old_customer, this.validation_rules);

                if(errors) {
                    this.errors = errors;
                    return;
                }

                var app = this;
                axios.put(`/api/customers/${this.$route.params.id}`, this.old_customer)
                .then((response) => {
                    app.$store.dispatch('customer/reload');
                    app.$router.push({ path: "/customers" });
                })
                .catch((error) => {
                    app.errors = error.response.data.errors;
                });
            },
            getCustomerByID(id) {
                return axios.get(`/api/customers/${id}`);
            }
        }
    }
</script>
