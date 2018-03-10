<template>
  <v-app id="login">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md10 lg7 xl5>
            <v-card class="elevation-12">
              <v-toolbar>
                <v-toolbar-title class="mx-auto primary--text">OVERSURGERY</v-toolbar-title>
              </v-toolbar>
              <v-card-text v-if="error && !success" class="error--text text-xs-center">
                There was an error, unable to complete registration.
              </v-card-text>
              <v-card-text v-if="success" class="primary--text text-xs-center">
                Registration completed. Check your email box for the confirmation email and your temporary credentials.
                <v-btn flat :to="{ name: 'login' }" color="primary">Back to login</v-btn>
              </v-card-text>
              <v-form @submit.prevent="register" v-if="!success" v-model="valid" method="post">
                <v-card-text>
                  <v-layout row justify-space-between v-bind="columnize">
                    <v-flex xs12 class="mx-2">
                      <v-text-field prepend-icon="person" name="name" label="Name" type="text" :rules="nameRules" :error-messages="errors.name" v-model="name" required></v-text-field>
                    </v-flex>
                    <v-flex xs12 class="mx-2">
                      <v-text-field prepend-icon="person" name="surname" label="Surname" type="text" :rules="surnameRules" :error-messages="errors.surname" v-model="surname" required></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-layout row justify-space-between v-bind="columnize">
                    <v-flex xs12 class="mx-2">
                      <v-text-field prepend-icon="email" name="email" label="E-mail" type="email" :rules="emailRules" :error-messages="errors.email" v-model="email" required></v-text-field>
                    </v-flex>
                    <v-flex xs12 class="mx-2">
                      <v-text-field prepend-icon="call" name="phone" label="Phone Number" type="tel" :rules="phoneRules" :error-messages="errors.phone" v-model="phone" required></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-layout row justify-space-between v-bind="columnize">
                    <v-flex xs12 class="mx-2">
                      <v-text-field prepend-icon="home" name="address" label="Address" type="text" :rules="addressRules" :error-messages="errors.address" v-model="address" required></v-text-field>
                    </v-flex>
                    <v-flex xs12 class="mx-2">
                      <v-text-field prepend-icon="location_city" name="postcode" label="Postcode" :rules="postcodeRules" :error-messages="errors.postcode" type="text" v-model="postcode" required></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-layout row justify-space-between v-bind="columnize">
                    <v-flex xs12 class="mx-2">
                      <v-menu ref="menu" lazy :close-on-content-click="false" v-model="menu" transition="scale-transition" offset-y full-width :nudge-right="40" min-width="290px">
                        <v-text-field slot="activator" label="Birthday date" prepend-icon="event" :rules="birthdateRules" :error-messages="errors.birthdate" v-model="birthdate" readonly></v-text-field>
                        <v-date-picker ref="picker" v-model="birthdate" @change="save" min="1950-01-01" :max="new Date().toISOString().substr(0, 10)"></v-date-picker>
                      </v-menu>
                    </v-flex>
                    <v-flex xs12 class="mx-2">
                      <v-select :items="doctypes" name="document_type" label="Document Type" :rules="doctypeRules" :error-messages="errors.document_type" v-model="document_type" single-line required></v-select>
                    </v-flex>
                    <v-flex xs12 class="mx-2">
                      <v-text-field prepend-icon="description" name="document_id" label="Document ID" type="text" :rules="docidRules" :error-messages="errors.document_id" v-model="document_id" required></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-divider></v-divider>
                </v-card-text>
                <v-card-actions>
                  <v-btn flat :to="{ name: 'login' }" color="primary">Back to login</v-btn>
                  <v-spacer></v-spacer>
                  <v-btn type="submit" color="primary" class="text-lg-right" :disabled="!valid">Register</v-btn>
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
      name: "",
      nameRules: [v => !!v || "Name is required"],
      surname: "",
      surnameRules: [v => !!v || "Surname is required"],
      email: "",
      emailRules: [
        v => !!v || "E-mail is required",
        v =>
          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "E-mail must be valid"
      ],
      phone: "",
      phoneRules: [v => !!v || "Phone is required"],
      address: "",
      addressRules: [v => !!v || "Address is required"],
      postcode: "",
      postcodeRules: [v => !!v || "Post Code is required"],
      birthdate: "",
      birthdateRules: [v => !!v || "Birth Date is required"],
      document_type: "",
      doctypeRules: [v => !!v || "Document Type is required"],
      document_id: "",
      docidRules: [v => !!v || "Document ID is required"],
      menu: false,
      modal: false,
      error: false,
      errors: {},
      success: false,
      doctypes: [
        { text: "Identity Card", value: "identity_card" },
        { text: "Passport", value: "passport" }
      ]
    };
  },
  computed: {
    columnize() {
      const binding = {};
      if (this.$vuetify.breakpoint.smAndDown) binding.column = true;
      return binding;
    }
  },
  watch: {
    menu(val) {
      val && this.$nextTick(() => (this.$refs.picker.activePicker = "YEAR"));
    }
  },
  methods: {
    register() {
      var app = this;
      this.$auth.register({
        params: {
          name: app.name,
          surname: app.surname,
          email: app.email,
          phone: app.phone,
          address: app.address,
          postcode: app.postcode,
          birthdate: app.birthdate,
          document_type: app.document_type,
          document_id: app.document_id
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
    },
    save(date) {
      this.$refs.menu.save(date);
    }
  }
};
</script>