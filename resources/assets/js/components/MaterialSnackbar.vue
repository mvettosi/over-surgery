<template>
  <v-snackbar v-model="snackbar" :color="options.color" :timeout="options.timeout">
    {{ message }}
    <v-btn dark flat @click.native="snackbar = false">Close</v-btn>
  </v-snackbar>
</template>
<script>
export default {
  data() {
    return {
      snackbar: false,
      message: null,
      options: {
        color: "error",
        timeout: 2750
      }
    };
  },
  methods: {
    open(message, options) {
      this.snackbar = true;
      this.message = message;
      this.options = Object.assign(this.options, options);
    },
    close() {
      this.snackbar = false;
    }
  }
};
</script>