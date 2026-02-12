<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>Edit Sub Category</h3>
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
                        <label for="category">Category</label>
                        <select v-model="old_sub_category.category_id" class="form-control">
                            <option value="">--Select--</option>
                            <option v-for="(value, key) in categories" :value="value.id" :key="key">{{value.name}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="nmae" class="form-control" v-model="old_sub_category.name">
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
        name: "SubCategoryEdit",
        async created() {
            try {

                var response = await this.getSubCategoryByID(this.$route.params.id);
                this.old_sub_category = response.data;
                this.categories = (await axios.get("/api/categories/all")).data;
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
                old_sub_category: {
                    category_id: "",
                    name: ""
                }, 
                categories: [],
                validation_rules: {
                    name: {
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

                const errors = validate(this.old_sub_category, this.validation_rules);

                if(errors) {
                    this.errors = errors;
                    return;
                }

                var app = this;
                axios.put(`/api/sub-categories/${this.$route.params.id}`, this.old_sub_category)
                .then((response) => {
                    app.$store.dispatch('sub_category/reload');
                    app.$router.push({ path: "/sub-categories" });
                })
                .catch((error) => {
                    app.errors = error.response.data.errors;
                });
            },
            getSubCategoryByID(id) {
                return axios.get(`/api/sub-categories/${id}`);
            }
        }
    }
</script>
