<template>
  <v-app id="login">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
              <v-toolbar>
                <v-toolbar-title class="mx-auto">OverSurgery</v-toolbar-title>
              </v-toolbar>
              <v-form @submit.prevent="login" method="post">
                <v-card-text>
                  <v-text-field prepend-icon="person" name="login" label="Login" type="text" v-model="email" required></v-text-field>
                  <v-text-field prepend-icon="lock" name="password" label="Password" id="password" type="password" v-model="password" required></v-text-field>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn type="submit" color="primary" class="text-lg-right">Login</v-btn>
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
      drawer: null,
      email: null,
      password: null,
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
          email: app.email,
          password: app.password
        },
        success: function() {},
        error: function() {},
        rememberMe: true,
        redirect: "/home",
        fetchUser: true
      });
    }
  }
};
</script>