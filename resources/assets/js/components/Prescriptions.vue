<template>
    <v-container grid-list-md>
        <v-layout class="mb-3" row wrap>
            <v-flex xs12 sm6 md4 lg4 xl3 v-for="(prescription, index) in prescriptions" :key="`prescription-${index}`">
                <v-card hover>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-icon>person</v-icon>
                                {{ prescription.doctor.name + ' ' + prescription.doctor.surname }}
                            </v-flex>
                            <v-flex xs12>
                                <v-icon>timer</v-icon>
                                {{ prescription.expiration_date }}
                            </v-flex>
                            <v-flex v-if="!prescription.show" xs12>
                                <v-icon>format-list-bulleted</v-icon>
                                {{ prescription.summary }}
                            </v-flex>
                        </v-layout>
                        <v-slide-y-transition>
                            <v-list v-show="prescription.show" three-line>
                                <template v-for="(endorsement, index) in prescription.endorsements">
                                    <v-list-tile avatar ripple :key="index">
                                        <v-list-tile-content>
                                            <v-list-tile-title>{{ endorsement.medication.name }}</v-list-tile-title>
                                            <v-list-tile-sub-title class="text--primary">{{ endorsement.dose }}</v-list-tile-sub-title>
                                            <v-list-tile-sub-title>{{ endorsement.quantity }}</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-divider v-if="index + 1 < prescription.endorsements.length" :key="`divider-${index}`"></v-divider>
                                </template>
                            </v-list>
                        </v-slide-y-transition>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn v-if="!prescription.extended" color="primary" @click="extendPrescription(prescription.id)">Extend</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn icon @click.native="prescription.show = !prescription.show">
                            <v-icon>{{ prescription.show ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
export default {
  data() {
    return {
      prescriptions: []
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      this.prescriptions = [];
      var url =
        "/prescriptions?with_medications=true&with_doctor=true&patient_id=" +
        this.$auth.user().id;
      this.axios
        .get(url)
        .then(response => {
          response.data.forEach(function(prescription) {
            prescription.show = false;
            prescription.summary = "";
            prescription.endorsements.forEach(function(endorsement, index) {
              if (index != 0) {
                prescription.summary += ", ";
              }
              prescription.summary += endorsement.medication.name;
            });
          });
          this.prescriptions = response.data;
        })
        .catch(e => {
          this.$root.$snackbar.open(e.response.data.message, {
            color: "error"
          });
        });
    },
    extendPrescription(id) {
      var url = "/prescriptions/" + id + "?extend=true";
      this.axios
        .put(url)
        .then(response => {
          this.$root.$snackbar.open(response.data.message, {
            color: "success"
          });
          this.fetchData();
        })
        .catch(e => {
          this.$root.$snackbar.open(e.response.data.message, {
            color: "error"
          });
        });
    }
  }
};
</script>