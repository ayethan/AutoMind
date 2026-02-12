<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>
                    Edit User
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
                <form @submit.prevent="updateUser">
                    <div class="form-group form-row">
                        <label class="col-md-3" for="old_user.name">Name</label>
                        <input class="form-control col-md-4" required type="text" v-model="old_user.name" id="old_user.name">
                    </div>
                    <div class="form-group form-row">
                        <label class="col-md-3" for="old_user.email">Email</label>
                        <input class="form-control col-md-4" required type="email" v-model="old_user.email" id="old_user.email">
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
    name: "UserEdit",
    data() {
        return {
            old_user: {
                name: "",
                email: ""
            },
            errors: null
        };
    },
    async created() {
        try {
            var response = await axios.get(`/api/users/${this.$route.params.id}`);
            this.old_user = response.data;
        } catch(error) {
                app.errors = error.response.data.errors;
        }
    },
    components: {
        ErrorsComponent
    },
    methods: {
        updateUser() {
            var app = this;
            axios.put(`/api/users/${this.$route.params.id}`, this.old_user)
            .then((response) => {
                app.$store.dispatch('user/reload');
                app.$router.push({path: "/users"});
            })
            .catch((error) => {
                app.errors = error.response.data.errors;
            });
        }
    }
}
</script>

