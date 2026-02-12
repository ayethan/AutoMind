<template>
    <div class="container">
        <div class="row justify-content-center vh-100 ">
            <div class="col-lg-4 col-md-6 col-sm-12 align-self-center">
                <div class="row justify-content-center text-center">
                    <div class="col">
                        <h1><strong>Tiny ERP</strong></h1>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col">
                        <form @submit.prevent="authenticate">
                            <div class="form-group">
                                <div class="row justify-content-center">
                                    <div class="col">
                                        <label for="email" class="text-md-right">Email</label>
                                        <input id="email" type="email" class="form-control" v-model="credentials.email" required autofocus>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row  justify-content-center">
                                    <div class="col">
                                        <label for="password" class=" text-md-right">Password</label>
                                        <input id="password" type="password" class="form-control" v-model="credentials.password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row justify-content-center">
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row" v-if="authError">
                                <div class="alert alert-danger">
                                    <p>{{authError}}</p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { login } from "../helpers/auth";
export default {
  name: "login",
  data: function() {
    return {
      credentials: {
        email: "",
        password: ""
      }
    };
  },
  methods: {
    authenticate() {
      this.$store.dispatch("auth/login");
      login(this.$data.credentials)
        .then(response => {
          this.$store.commit("auth/loginSuccess", response);
          this.$router.push({ path: "/dashboard" });
        })
        .catch(error => {
          this.$store.commit("auth/loginFailed", { error });
        });
    }
  },
  computed: {
    authError() {
      return this.$store.state.auth.auth_error;
    }
  }
};
</script>
