<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    Change Password
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
                <div class="col-md-6">
                    <form @submit.prevent="update">
                        <div class="form-group form-row">
                            <label for="user.old_password" class="col-sm-3">Old Passsowrd</label>
                            <input class="form-control col-sm-4" type="password" id="user.old_password" v-model="user.old_password" required>
                        </div>
                        <div class="form-group form-row">
                            <label for="user.password" class="col-sm-3">New Passsowrd</label>
                            <input class="form-control col-sm-4" type="password" id="user.password" v-model="user.password" required>
                        </div>
                        <div class="form-group form-row">
                            <label for="user.confirm_password" class="col-sm-3">Confirm Passsowrd</label>
                            <input class="form-control col-sm-4" type="password" id="user.confirm_password" v-model="user.confirm_password" required>
                        </div>
                        <div class="form-group form-row">
                            <div class="col-sm-7 d-flex">
                                <button type="submit" class="btn btn-success ml-auto">Change</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "PasswordChange",
    data() {
        return {
            user: {
                old_password: "",
                password: "",
                confirm_password: ""
            },
            errors: null
        };
    },
    methods: {
        async update() {
            this.errors = null;
            try {
                (await axios.post("/api/auth/password", this.user));
                this.user = {};
                alert("Updated Successfully");
            } catch (error) {
                this.errors = {
                    server_error: [
                        error.message
                    ]
                };
            }
        }
    }
}
</script>
