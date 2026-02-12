<template>
    <div class="container-fluid">

        <div class="row">
            <div class="col">
                <h3>
                    Create User
                </h3>  
            </div>
        </div>
        <hr>
        <div class="row" v-if="errors">
            <div class="col">
                <ErrorsComponent :errors="errors"/>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <form @submit.prevent="createNewUser">
                    <div class="form-group form-row">
                        <label class="col-md-3" for="new_user.name">Name</label>
                        <input class="form-control col-md-4" required type="text" v-model="new_user.name" id="new_user.name">
                    </div>
                    <div class="form-group form-row">
                        <label class="col-md-3" for="new_user.email">Email</label>
                        <input class="form-control col-md-4" required type="email" v-model="new_user.email" id="new_user.email">
                    </div>
                    <div class="form-group form-row">
                        <label class="col-md-3" for="new_user.password">Password</label>
                        <input class="form-control col-md-4" required type="password" v-model="new_user.password" id="new_user.password">
                    </div>
                    <div class="form-group form-row">
                        <label class="col-md-3" for="new_user.confirm_password">Confirm Password</label>
                        <input class="form-control col-md-4" required type="password" v-model="new_user.confirm_password" id="new_user.confirm_password">
                    </div>
                    <div class="form-group d-flex col-md-7">
                        <button type="submit" class="btn btn-success ml-auto">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import ErrorsComponent from "./../../components/ErrorsComponent";

export default {
    name: "UserCreate",
    data() {
        return {
            new_user: {
                name: "",
                email: "",
                password: "",
                confirm_password: ""
            },
            errors: null
        };
    },
    components: {
        ErrorsComponent
    },
    methods: {
        createNewUser() {
            var app = this;
            axios.post("/api/users", this.new_user)
            .then((response) => {
                app.$router.push({path: "/users"});
            })
            .catch((error) => {
                app.errors = error.response.data.errors;
            });
        }
    }
}
</script>

