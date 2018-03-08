<template>
  <v-app id="login">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md5>
            <v-card class="elevation-12">
              <v-toolbar>
                <v-toolbar-title class="mx-auto">OverSurgery</v-toolbar-title>
              </v-toolbar>
              <div class="alert alert-danger" v-if="error && !success">
                <p>There was an error, unable to complete registration.</p>
              </div>
              <div class="alert alert-success" v-if="success">
                <p>Registration completed. You can now
                  <router-link :to="{name:'login'}">sign in.</router-link>
                </p>
              </div>
              <v-form @submit.prevent="register" v-if="!success" method="post">
                <v-card-text>
                  <v-text-field prepend-icon="person" name="name" label="Name" type="text" v-model="name" required></v-text-field>
                  <v-text-field prepend-icon="email" name="email" label="E-mail" type="text" v-model="email" required></v-text-field>
                  <v-text-field prepend-icon="lock" name="password" label="Password" id="password" type="password" v-model="password" required></v-text-field>
                </v-card-text>
                <v-card-actions>
                  <v-btn flat :to="{ name: 'login' }" color="primary">Back to login</v-btn>
                  <v-spacer></v-spacer>
                  <v-btn type="submit" color="primary" class="text-lg-right">Register</v-btn>
                </v-card-actions>
                <v-divider></v-divider>
              </v-form>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </v-app>
  <!-- <div>
        <div class="alert alert-danger" v-if="error && !success">
            <p>There was an error, unable to complete registration.</p>
        </div>
        <div class="alert alert-success" v-if="success">
            <p>Registration completed. You can now <router-link :to="{name:'login'}">sign in.</router-link></p>
        </div>
        <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
            <div class="form-group" v-bind:class="{ 'has-error': error && errors.name }">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" v-model="name" required>
                <span class="help-block" v-if="error && errors.name">{{ errors.name }}</span>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': error && errors.email }">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                <span class="help-block" v-if="error && errors.email">{{ errors.email }}</span>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': error && errors.password }">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" v-model="password" required>
                <span class="help-block" v-if="error && errors.password">{{ errors.password }}</span>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
            <router-link :to="{ name: 'login' }">Login</router-link>
        </form>
    </div> -->
</template>
<script>
export default {
  data() {
    return {
      name: "",
      email: "",
      password: "",
      error: false,
      errors: {},
      success: false
    };
  },
  methods: {
    register() {
      var app = this;
      this.$auth.register({
        params: {
          name: app.name,
          email: app.email,
          password: app.password
        },
        success: function() {
          app.success = true;
        },
        error: function(resp) {
          app.error = true;
          app.errors = resp.response.data.errors;
        },
        redirect: null
      });
    }
  }
};
</script>