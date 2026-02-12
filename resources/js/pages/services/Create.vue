<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>New Service</h3>
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
                        <input type="text" id="nmae" class="form-control" v-model="new_service.name">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea type="text" id="description" class="form-control" v-model="new_service.description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" id="price" class="form-control" v-model="new_service.price">
                    </div>
                    <div class="form-group">
                        <label for="remark">Remark</label>
                        <textarea type="text" id="remark" class="form-control" v-model="new_service.remark"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import validate from "validate.js";
    export default {
        name: "ServiceCreate",
        data() {
            return {
                new_service: {
                    name: "",
                    price: "",
                    description: "",
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
                    price: {
                        presence: true,
                        numericality: {
                            onlyInteger: true,
                            greaterThan: 0,
                            message: "must be an integer."
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

                const errors = validate(this.new_service, this.validation_rules);

                if(errors) {
                    this.errors = errors;
                    return;
                }

                axios.post("/api/services", this.new_service)
                .then((response) => {
                    this.$router.push({ path: "/services" });
                })
                .catch((error) => {
                    app.errors = error.response.data.errors;
                });
            }
        }
    }
</script>
