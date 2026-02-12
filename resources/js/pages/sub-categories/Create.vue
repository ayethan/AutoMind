<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>New Category</h3>
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
                        <label for="category">Category</label>
                        <select v-model="new_sub_category.category_id" class="form-control">
                            <option value="">--Select--</option>
                            <option v-for="(value, key) in categories" :value="value.id" :key="key">{{value.name}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="nmae" class="form-control" v-model="new_sub_category.name">
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
        name: "CategoryCreate",
        data() {
            return {
                new_sub_category: {
                    category_id: "",
                    name: ""
                }, 
                categories: [],
                validation_rules: {
                    name: {
                        presence: true,
                        length:{
                            minimum: 1,
                            message: "should have at least 1 character."
                        }
                    }
                },
                errors: null
            }
        },
        async created() {
            var response = await axios.get("/api/categories/all");
            this.categories = response.data;
        },
        methods: {
            add() {
                var app = this;
                this.errors = null;

                const errors = validate(this.new_sub_category, this.validation_rules);

                if(errors) {
                    this.errors = errors;
                    return;
                }

                axios.post("/api/sub-categories", this.new_sub_category)
                .then((response) => {
                    app.$router.push({ path: "/sub-categories" });
                })
                .catch((error) => {
                    app.errors = error.response.data.errors;
                });
            }
        }
    }
</script>
