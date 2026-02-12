<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">Setting</div>
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
            <div class="col-sm-6">
                <form @submit.prevent="update">
                    <div class="form-group form-row" v-for="(setting, index) in settings" :key="index">
                        <label class="col-sm-6">{{setting.key}}</label>
                        <input type="text" class="form-control col-sm-6" v-model="setting.value" required>
                    </div>
                    <div class="form-group d-flex">
                        <button type="submit" class="btn btn-success ml-auto">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Setting",
    data() {
        return {
            settings: [],
            errors: null
        };
    },
    async created() {
        this.settings = (await axios.get('/api/settings')).data;
    },
    methods: {
        async update() {
            this.errors = null;
            try {
                this.settings = (await axios.put('/api/settings', this.settings)).data;
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

