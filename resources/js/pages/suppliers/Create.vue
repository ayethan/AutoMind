<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>New Supplier</h3>
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
                <form @submit.prevent="add">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="nmae" class="form-control" v-model="new_supplier.name">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" class="form-control" v-model="new_supplier.phone">
                    </div>
                    <div class="form-group">
                        <label for="remark">Remark</label>
                        <textarea type="text" id="remark" class="form-control" v-model="new_supplier.remark"></textarea>
                    </div>
                    <div class="form-group d-flex">
                        <button type="submit" class="btn btn-success ml-auto">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import validate from "validate.js";
    export default {
        name: "SupplierCreate",
        data() {
            return {
                new_supplier: {
                    name: "",
                    phone: "",
                    remark: ""
                }, 
                validation_rules: {
                    name: {
                        presence: true,
                        length:{
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
            add() {
                var app = this;
                this.errors = null;

                const errors = validate(this.new_supplier, this.validation_rules);

                if(errors) {
                    this.errors = errors;
                    return;
                }

                axios.post("/api/suppliers", this.new_supplier)
                .then((response) => {
                    app.$router.push({ path: "/suppliers" });
                })
                .catch((error) => {
                    app.errors = error.response.data.errors;
                });
            }
        }
    }
</script>
