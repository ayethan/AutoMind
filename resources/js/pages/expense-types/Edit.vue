<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>Edit Expense Type</h3>
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
                <form @submit.prevent="updateExpenseType">
                    <div class="form-group">
                        <label for="old_expense_type.name">Name</label>
                        <input required id="old_expense_type.name" v-model="old_expense_type.name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="old_expense_type.remark">Remark</label>
                        <textarea required id="old_expense_type.remark" v-model="old_expense_type.remark" class="form-control"></textarea>
                    </div>

                    <div class="form-group d-flex">
                        <button type="submit" class="btn btn-success ml-auto">Add</button>
                    </div>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="errors">
                        <ul>
                            <li v-for="(item, index) in errors" :key="index">
                                <strong> {{item.join('\n')}} </strong>
                            </li>
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  name: "ExpenseTypeEdit",
  data() {
    return {
      old_expense_type: {
        name: "",
        remark: ""
      },
      errors: null
    };
  },
  async created() {
    try {
      var response = await axios.get(`/api/expense-types/${this.$router.params.id}`);
      this.old_expense_type = response.data;
    } catch (e) {
      this.errors = {
        serer_error: [e.message]
      };
    }
  },
  methods: {
    updateExpenseType() {
      var app = this;

      axios
        .put(`api/expense-types/${this.$router.params.id}`, app.old_expense_type)
        .then(response => {
          app.$router.push({ path: "/expenses-types" });
        })
        .catch(error => {
          app.errors = error.response.data.errors;
        });
    }
  }
};
</script>

