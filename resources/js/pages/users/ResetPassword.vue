<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>
                    Reset User's Password
                </h3>
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
            <div class="col">
                <form @submit.prevent="reset">
                    <div class="form-group form-row">
                        <label for="payload.admin_password" class="col-md-3">Admin Password</label>
                        <input type="password" v-model="payload.admin_password" id="payload.admin_password" class="form-control col-md-4">
                    </div>
                    <div class="form-group form-row">
                        <label for="payload.password" class="col-md-3">New Password</label>
                        <input type="password" v-model="payload.password" id="payload.password" class="form-control col-md-4">
                    </div>
                    <div class="form-group form-row d-flex col-md-7">
                        <button type="submit" class="btn btn-success ml-auto">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ResetPassword",
    data() {
        return {
            payload: {
                admin_password: "",
                password: ""
            },  
            errors: null
        };
    },
    methods: {
        reset() {
            var app = this;
            axios.put(`/api/users/${this.$route.params.id}`, this.payload)
            .then((response) => {
                app.$router.push({ path: "/users" });
            })
            .catch((error) => {
                app.errors = error.response.data.errors;
            });
        }
    }
}
</script>
