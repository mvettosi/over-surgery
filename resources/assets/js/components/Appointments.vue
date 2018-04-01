<template>
    <v-container grid-list-md>
        <v-layout v-for="(appointment, index) in appointments" :key="`appointment-${index}`" class="mt-3" row wrap>
            <v-flex xs12>
                <v-card class="pa-2">
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex xs6 sm4 md3 lg3 xl2 class="headline">
                                <v-icon>event</v-icon>
                                {{ new Date(appointment.start_time).toLocaleDateString() }}
                                <!-- 2018/03/31 -->
                            </v-flex>
                            <v-flex xs6 sm5 md7 lg7 xl9 class="headline">
                                <v-icon>person</v-icon>
                                {{ appointment.doctor.name + ' ' + appointment.doctor.surname }}
                                <!-- Georgette Willms -->
                            </v-flex>
                            <v-flex sm3 md2 lg2 xl1 v-if="$vuetify.breakpoint.smAndUp && new Date(appointment.start_time).toLocaleDateString() !== new Date().toLocaleDateString()">
                                <v-menu transition="slide-y-transition" bottom>
                                    <v-btn color="primary" slot="activator">Edit</v-btn>
                                    <v-list>
                                        <v-list-tile v-for="hours in appointment.doctor.availableHours" :key="hours.title" @click="editAppointment(appointment.id, appointment.doctor.id, hours.title)">
                                            <v-list-tile-title>{{ hours.title }}</v-list-tile-title>
                                        </v-list-tile>
                                    </v-list>
                                </v-menu>
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs6 sm4 md3 lg3 xl2 class="headline">
                                <v-icon>schedule</v-icon>
                                {{ new Date(appointment.start_time).toLocaleString('en-UK', { hour: 'numeric', minute: 'numeric', hour12: true }) }}
                                <!-- 12:00 pm -->
                            </v-flex>
                            <v-flex xs6 sm5 md7 lg7 xl9 class="headline">
                                <v-icon>place</v-icon>
                                {{ appointment.location }}
                                <!-- 87276 Nellie Rue East Payton, WY 34277 -->
                            </v-flex>
                            <v-flex sm3 md2 lg2 xl1 v-if="$vuetify.breakpoint.smAndUp" class="headline">
                                <v-btn color="primary" @click="confirmDelete(appointment.id)">Delete</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                    <v-card-actions v-if="$vuetify.breakpoint.xs">
                        <v-spacer></v-spacer>
                        <v-btn color="primary" v-if="new Date(appointment.start_time).toLocaleDateString() !== new Date().toLocaleDateString()">Edit</v-btn>
                        <v-spacer></v-spacer>
                        <v-btn color="primary">Delete</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
        <v-snackbar v-model="snackbar" :color="snackbarType" :timeout="snackbarTimeout">
            {{ snackbarMessage }}
            <v-btn dark flat @click.native="snackbar = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>
<script>
export default {
  data() {
    return {
      appointments: [],
      snackbar: false,
      snackbarTimeout: 2750,
      snackbarMessage: "",
      snackbarType: "error"
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      this.appointments = [];
      var url =
        "/appointments?with_patient=true&with_availability=true&patient_id=" +
        this.$auth.user().id;
      this.axios
        .get(url)
        .then(response => {
          this.appointments = response.data;
        })
        .catch(e => {
          this.manageError(e);
        });
    },
    editAppointment(id, doctor_id, time) {
      var url = "/appointments/" + id;
      this.snackbar = false;
      this.axios
        .put(url, {
          start_hour: parseInt(time.substr(0, time.indexOf(":")))
        })
        .then(response => {
          this.manageSuccess(response);
          this.fetchData();
        })
        .catch(e => {
          this.manageError(e);
        });
    },
    confirmDelete(id) {
      this.$root
        .$confirm(
          "Delete appointment",
          "Are you sure you want to delete this appointment?",
          { color: "error" }
        )
        .then(confirm => {
          if (confirm) {
            this.deleteAppointment(id);
          }
        });
    },
    deleteAppointment(id) {
      var url = "/appointments/" + id;
      this.axios
        .delete(url)
        .then(response => {
          this.manageSuccess(response);
          this.fetchData();
        })
        .catch(e => {
          this.manageError(e);
        });
    },
    manageSuccess(response) {
      this.snackbarType = "success";
      this.snackbarMessage = response.data.message;
      this.snackbar = true;
    },
    manageError(e) {
      this.errors.push(e.response.data);
      this.snackbarType = "error";
      this.snackbarMessage = e.response.data.message;
      this.snackbar = true;
    }
  }
};
</script>