<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>Edit Car Made</h3>
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
                        <input type="text" id="nmae" class="form-control" v-model="old_car_made.name">
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
        name: "CarMadeEdit",
        async created() {
            try {

                var response = await this.getCarMadeByID(this.$route.params.id);
                this.old_car_made = response.data;
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
                old_car_made: {
                    name: "",
                }, 
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

                const errors = validate(this.old_car_made, this.validation_rules);

                if(errors) {
                    this.errors = errors;
                    return;
                }

                var app = this;
                axios.put(`/api/car-mades/${this.$route.params.id}`, this.old_car_made)
                .then((response) => {
                    app.$store.dispatch('car_made/reload');
                    app.$router.push({ path: "/car-mades" });
                })
                .catch((error) => {
                    app.errors = error.response.data.errors;
                });
            },
            getCarMadeByID(id) {
                return axios.get(`/api/car-mades/${id}`);
            }
        }
    }
</script>
