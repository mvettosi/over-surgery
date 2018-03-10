<template>
  <v-app id="login">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
              <v-toolbar>
                <v-toolbar-title class="mx-auto primary--text">OVERSURGERY</v-toolbar-title>
              </v-toolbar>
              <v-card-text v-if="loginFailed" class="error--text text-xs-center">
                There was an error, unable to complete registration.
              </v-card-text>
              <v-form @submit.prevent="login" v-model="valid" method="post">
                <v-card-text>
                  <v-text-field prepend-icon="person" name="username" label="Username" type="text" :rules="usernameRules" :error="loginFailed" v-model="username" required></v-text-field>
                  <v-text-field prepend-icon="lock" name="password" label="Password" id="password" type="password" :rules="passwordRules" :error="loginFailed" v-model="password" required></v-text-field>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn type="submit" color="primary" class="text-lg-right" :disabled="!valid">Login</v-btn>
                </v-card-actions>
                <v-divider></v-divider>
                <v-card-actions>
                  <a href="http://over-surgery.test/password/reset">Forgot Your Password?</a>
                  <v-spacer></v-spacer>
                  <v-btn flat :to="{ name: 'register' }" color="primary">Register</v-btn>
                </v-card-actions>
              </v-form>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </v-app>
</template>
<script>
export default {
  data() {
    return {
      valid: true,
      loginFailed: false,
      drawer: null,
      username: null,
      usernameRules: [v => !!v || "Userame is required"],
      password: null,
      passwordRules: [v => !!v || "Password is required"],
      error: false
    };
  },
  props: {
    source: String
  },
  methods: {
    login() {
      var app = this;
      this.$auth.login({
        params: {
          username: app.username,
          password: app.password
        },
        success: function() {},
        error: function() {
          app.loginFailed = true;
        },
        rememberMe: true,
        redirect: "/home",
        fetchUser: true
      });
    }
  }
};
</script>