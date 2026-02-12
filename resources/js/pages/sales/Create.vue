<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col"><h3>New Sale</h3></div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-4 col-xs-12">
                <form @submit.prevent="add">
                    <div class="form-group">
                        <label for="car_no">Car No.</label>
                        <input class="form-control" type="text" id="car_no" v-model="new_sale.car_no">
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" v-model="new_sale.is_taxi" id="is_taxi">
                            <label for="is_taxi" class="form-check-label">Taxi</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input class="form-control" type="date" id="date" v-model="new_sale.date">
                    </div>
                    <div class="form-group">
                        <label for="mileage">Milage</label>
                        <input type="text" class="form-control" id="milage" v-model="new_sale.mileage">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import validate from "validate.js";

export default {
    name: "SaleNew",
    data() {
        return {
            new_sale: {
                car_no: "",
                date: "",
                car_id: "",
                mileage: "",
                is_taxi: ""
            },
            errors: null,
            validation_rules: {
                car_no: {
                    presence: true,
                    length: {
                        minimum: 1,
                        message: "should have at least 1 character."
                    }
                },
                date: {
                    datetime: {
                        dateOnly: true
                    }
                }
            }
        };
    },
    methods: {
        add() {
            var app = this;
            this.errors = null; 

            validate.extend(validate.validators.datetime, {
                parse: function(value, options) {
                    return new Date(value).valueOf()
                },
                format: function(value, options) {
                    return new Date(value)
                }
            })

            const errors = validate(this.new_sale, this.validation_rules);

            if(errors) {
                this.errors = errors;
                return;
            }

            axios.post("/api/sales", this.new_sale)
            .then((response) => {
                var id = response.data.data.id;
                app.$router.push({path: `/sales/${id}/edit`});
            })
            .catch((error) => {
                app.errors = error.response.data.errors;
            });
        }
    }
}
</script>