<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>Edit Product</h3>
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
                        <label for="category_id">Category</label>
                        <select v-model="old_product.category_id" class="form-control" id="category_id">
                            <option value="">--Select--</option>
                            <option v-for="(value, key) in categories" :value="value.id" :key="key">{{value.name}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sub_category_id">Sub Category</label>
                        <select v-model="old_product.sub_category_id" class="form-control" id="sub_category_id">
                            <option value="">--Select--</option>
                            <option v-for="(value, key) in sub_categories" :value="value.id" :key="key">{{value.name}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" class="form-control" v-model="old_product.name">
                    </div>
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" id="code" class="form-control" v-model="old_product.code">
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="text" id="stock" class="form-control" v-model="old_product.stock">
                    </div>
                    <div class="form-group">
                        <label for="buy_price">Buy Price</label>
                        <input type="text" id="buy_price" class="form-control" v-model="old_product.buy_price">
                    </div>
                    <div class="form-group">
                        <label for="sell_price">Sell Price</label>
                        <input type="text" id="sell_price" class="form-control" v-model="old_product.sell_price">
                    </div>
                    <div class="form-group">
                        <label for="remark">Remark</label>
                        <textarea type="text" id="remark" class="form-control" v-model="old_product.remark"></textarea>
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
        name: "ProductEdit",
        async created() {
            try {
                var response = await this.getProductByID(this.$route.params.id);
                this.old_product = response.data;
                var response_cat = await axios.get("/api/categories/all");
                this.categories = response_cat.data;
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
                old_product: {
                    name: "",
                    stock: "",
                    buy_price: "",
                    sell_price: "",
                    category_id: "",
                    sub_category_id: "",
                    code: ""
                }, 
                categories: [],
                validation_rules: {
                    name: {
                        presence: true,
                        length:{
                            minimum: 1,
                            message: "should have at least 1 character."
                        }
                    },
                    stock: {
                        presence: true,
                        numericality: {
                            onlyInteger: true,
                            greaterThan: 0,
                            message: "must be an integer."
                        }
                    },
                    buy_price: {
                        presence: true,
                        numericality: {
                            onlyInteger: true,
                            greaterThan: 0,
                            message: "must be an integer."
                        }
                    },
                    sell_price: {
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
            update() {
                this.errors = null;

                const errors = validate(this.old_product, this.validation_rules);

                if(errors) {
                    this.errors = errors;
                    return;
                }
                var app = this;
                axios.put(`/api/products/${this.$route.params.id}`, this.old_product)
                .then((response) => {
                    app.$store.dispatch('product/reload');
                    app.$router.push({ path: "/products" });
                })
                .catch((error) => {
                    app.errors = error.response.data.errors;
                });
            },
            getProductByID(id) {
                return axios.get(`/api/products/${id}`);
            }
        },
        computed: {
            sub_categories: function() {
                var app = this;
                var category = this.categories.find(function(element) {
                    return element.id == app.old_product.category_id
                });
                if(category) {
                    return category.sub_categories;
                }
                return [];
            }
        }
    }
</script>
