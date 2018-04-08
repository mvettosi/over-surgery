<template>
  <v-container grid-list-md>
    <v-layout class="mb-3" row wrap>
      <v-flex xs12>
        <v-switch :label="uncheckedOnly ? 'Unchecked test results only' : 'All test results'" v-model="uncheckedOnly"></v-switch>
      </v-flex>
      <v-flex xs12 sm6 md4 lg4 xl3 v-for="(test, index) in tests" :key="`test-${index}`">
        <v-badge overlap :value="!test.checked" color="red">
          <span slot="badge">!</span>
          <v-card height="100%" hover>
            <v-card-title class="title">
              {{ test.disease }}
            </v-card-title>
            <v-card-text>
              <v-layout row wrap>
                <v-flex xs6 class="subheading">
                  <v-icon>event</v-icon>
                  {{ new Date(test.date_taken).toLocaleDateString() }}
                </v-flex>
                <v-flex xs6 class="subheading">
                  <v-icon>person</v-icon>
                  {{ test.doctor.name + ' ' + test.doctor.surname }}
                </v-flex>
                <v-flex xs12 class="subheading">
                  <v-icon>place</v-icon>
                  {{ test.hospital }}
                </v-flex>
              </v-layout>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="primary" @click="downloadResult(test.id, test.result)">Download</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-badge>
      </v-flex>
    </v-layout>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      tests: [],
      uncheckedOnly: true
    };
  },
  created() {
    this.fetchData();
  },
  watch: {
    uncheckedOnly: function() {
      this.fetchData();
    }
  },
  methods: {
    fetchData() {
      this.tests = [];
      var url =
        "/tests?include_checked=true&with_doctor=true&include_checked=" +
        !this.uncheckedOnly +
        "&patient_id=" +
        this.$auth.user().id;
      this.axios
        .get(url)
        .then(response => {
          this.tests = response.data;
        })
        .catch(e => {
          this.$root.$snackbar.open(e.response.data.message, {
            color: "error"
          });
        });
    },
    downloadResult(id, fileName) {
      this.axios({
        url: "/tests/" + id + "?download=true",
        method: "GET",
        responseType: "blob"
      }).then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", fileName);
        document.body.appendChild(link);
        link.click();
        this.fetchData();
      });
    }
  }
};
</script>