<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    Change Profile
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
                            <label for="user.email" class="col-sm-3">Email</label>
                            <input class="form-control col-sm-4" type="email" id="user.email" v-model="user.email" required>
                        </div>
                        <div class="form-group form-row">
                            <label for="user.name" class="col-sm-3">Name</label>
                            <input class="form-control col-sm-4" type="text" id="user.name" v-model="user.name" required>
                        </div>
                        <div class="form-group form-row">
                            <div class="col-sm-7 d-flex">
                                <button type="submit" class="btn btn-success ml-auto">Update</button>
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
    name: "Profile",
    data() {
        return {
            user: {
                email: "",
                name: ""
            },
            errors: null
        };
    },
    async created() {
        this.errors = null;
        try {
            this.user = (await axios.get("/api/auth/user")).data;
        } catch (error) {
            this.errors = {
                server_error: [
                    error.message
                ]
            };
        }
    },
    methods: {
        async update() {
            this.errors = null;
            try {
                this.user = (await axios.put("/api/auth/profile", this.user)).data;
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
