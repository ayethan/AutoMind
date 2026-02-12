<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h3>New Car</h3>
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
                <form @submit.prevent="addCar">
                    <div class="form-group">
                        <label for="new_car.car_no">Car No.</label>
                        <input type="text" required id="new_car.car_no" v-model="new_car.car_no" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="new_car.model">Model</label>
                        <input type="text" required id="new_car.model" v-model="new_car.model" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="new_car.car_made_id"></label>
                        <select class="form-control" v-model="new_car.car_made_id" name="new_car.car_made_id" id="new_car.car_made_id">
                            <option value="">--Car Made--</option>
                            <option v-for="(car_made, key) in car_mades" :key="key" :value="car_made.id">{{car_made.name}}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="new_car.customer_id">Customers</label>
                        <select class="form-control" v-model="new_car.customer_id" name="new_car.customer_id" id="new_car.customer_id">
                            <option value="">--Customer--</option>
                            <option v-for="(customer, key) in customers" :key="key" :value="customer.id">{{customer.name}}</option>
                        </select>
                    </div>

                    <div class="form-group d-flex">
                        <button type="submit" class="btn btn-success ml-auto">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import validate from "validate.js";
export default {
  name: "ExpenseCreate",
  data() {
    return {
      new_car: {
          car_no: "",
          customer_id: "",
          car_made_id: "",
          model: ""
      },
      car_mades: [],
      customers: [],
      validation_rules: {
        car_no: {
            presence: true,
            length:{
                minimum: 1,
                message: "should have at least 1 character."
            }
        }
      },
      errors: null
    };
  },

  created() {
    this.initialize();
  },
  methods: {
    addCar() {
      var app = this;
      app.errors = null;
      const errors = validate(this.new_car, this.validation_rules);

      if (errors) {
        this.errors = errors;
        return;
      }

      axios.post("/api/cars", app.new_car)
      .then(response => {
          app.$router.push({ path: "/cars" });
      })
      .catch((e) => {
        app.errors = {
          server_error: [e.message]
        }
      });
    },

    async initialize() {
      try {
        this.car_mades = (await axios.get("/api/car-mades/all")).data;
        this.customers = (await axios.get("/api/customers/all")).data;
      } catch (e) {
        this.errors = error.response.data.errors;
      }
    }
  }
};
</script>

