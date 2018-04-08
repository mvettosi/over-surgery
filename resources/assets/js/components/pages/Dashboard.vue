<template>
    <v-container grid-list-md>
        <v-layout row wrap>
            <v-flex xs12 sm6>
                <v-card height="100%" to="/availability" class="pa-2" hover>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex class="display-2" align-start>
                                Doctors:
                            </v-flex>
                        </v-layout>
                        <v-layout class="mb-3" row wrap>
                            <v-flex xs6 class="headline">
                                {{ doctorsOnDuty }} on duty
                            </v-flex>
                            <v-flex xs6 class="headline">
                                {{ doctorsAvailable }} available
                            </v-flex>
                        </v-layout>
                        <v-layout class="mt-3" row wrap>
                            <v-flex class="display-2" align-start>
                                Nurses:
                            </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex xs6 class="headline">
                                {{ nursesOnDuty }} on duty
                            </v-flex>
                            <v-flex xs6 class="headline">
                                {{ nursesAvailable }} available
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-flex>
            <v-flex xs12 sm6>
                <v-card height="100%" class="pa-2" hover>
                    <v-container grid-list-md :fill-height="!appointmentsThisMonth > 0">
                        <v-layout row wrap :align-center="!appointmentsThisMonth > 0">
                            <v-flex class="display-1" align-start>
                                {{ appointmentsThisMonth }} appointments this month
                            </v-flex>
                        </v-layout>
                        <v-layout class="mt-3" v-if="appointmentsThisMonth > 0" row wrap>
                            <v-flex xs12 class="headline">
                                Next Appointment:
                            </v-flex>
                            <v-flex xs1 class="headline">
                                <v-icon>event</v-icon>
                            </v-flex>
                            <v-flex xs5 class="headline">
                                {{ nextAppDate }}
                            </v-flex>
                            <v-flex xs1 class="headline">
                                <v-icon>schedule</v-icon>
                            </v-flex>
                            <v-flex xs5 class="headline">
                                {{ nextAppTime }}
                            </v-flex>
                            <v-flex xs1 class="headline">
                                <v-icon>place</v-icon>
                            </v-flex>
                            <v-flex xs5 class="headline">
                                {{ nextAppLocation }}
                            </v-flex>
                            <v-flex xs1 class="headline">
                                <v-icon>person</v-icon>
                            </v-flex>
                            <v-flex xs5 class="headline">
                                {{ nextAppDoctor }}
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-flex>
        </v-layout>
        <v-layout row wrap>
            <v-flex xs12 sm6>
                <v-card height="100%" class="pa-2" hover>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex xs12 class="headline my-3">
                                {{ prescriptions }} Prescriptions
                            </v-flex>
                            <v-flex xs12 class="headline my-3">
                                {{ expiringPrescriptions }} about to expire!
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-flex>
            <v-flex xs12 sm6>
                <v-card height="100%" class="pa-2" hover>
                    <v-container grid-list-md fill-height>
                        <v-layout row wrap align-center>
                            <v-flex xs12 class="headline my-3" v-if="newTests > 0">
                                {{ newTests }} NEW test results!
                            </v-flex>
                            <v-flex xs12 class="headline my-3" v-else>
                                No new test results
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
export default {
  data() {
    return {
      // Staff on duty
      doctorsOnDuty: 0,
      nursesOnDuty: 0,
      // Staff Available
      doctorsAvailable: 0,
      nursesAvailable: 0,
      // Appointments
      appointmentsThisMonth: 0,
      nextAppDate: "Loading...",
      nextAppTime: "Loading...",
      nextAppLocation: "Loading...",
      nextAppDoctor: "Loading...",
      // Prescriptions
      prescriptions: 0,
      expiringPrescriptions: 0,
      // Tests
      newTests: 0,
      //Errors
      errors: []
    };
  },
  created() {
    this.fetchAppointments();
    this.fetchAvailability();
    this.fetchPrescriptions();
    this.fetchTests();
  },
  watch: {
    $route: "fetchAppointments"
  },
  methods: {
    fetchAppointments() {
      this.appointmentsThisMonth = 0;
      this.nextAppDate = "Loading...";
      this.nextAppTime = "Loading...";
      this.nextAppLocation = "Loading...";
      this.nextAppDoctor = "Loading...";
      this.axios
        .get("/appointments?count=true&patient_id=" + this.$auth.user().id)
        .then(response => {
          this.appointmentsThisMonth = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
      this.axios
        .get("/appointments?first=true&patient_id=" + this.$auth.user().id)
        .then(response => {
          var appDate = new Date(response.data.start_time);
          this.nextAppDate = appDate.toLocaleDateString();
          this.nextAppTime = this.getTimeString(appDate);
          this.nextAppLocation = response.data.location;
          this.axios
            .get("/users/" + response.data.doctor_or_nurse_id)
            .then(responseDoc => {
              this.nextAppDoctor =
                responseDoc.data.name + " " + responseDoc.data.surname;
            })
            .catch(e => {
              this.errors.push(e);
            });
          this.nextAppDoctor = "?";
        })
        .catch(e => {
          this.errors.push(e);
        });
    },
    fetchAvailability() {
      this.doctorsOnDuty = 0;
      this.nursesOnDuty = 0;
      this.doctorsAvailable = 0;
      this.nursesAvailable = 0;
      this.axios
        .get("/users?on_duty=true&count=true&account_type=doctor")
        .then(response => {
          this.doctorsOnDuty = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
      this.axios
        .get("/users?on_duty=true&count=true&account_type=nurse")
        .then(response => {
          this.nursesOnDuty = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
      this.axios
        .get("/users?available=true&count=true&account_type=doctor")
        .then(response => {
          this.doctorsAvailable = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
      this.axios
        .get("/users?available=true&count=true&account_type=nurse")
        .then(response => {
          this.nursesAvailable = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
    },
    fetchPrescriptions() {
      this.prescriptions = 0;
      this.expiringPrescriptions = 0;
      this.axios
        .get("/prescriptions?count=true&&patient_id=" + this.$auth.user().id)
        .then(response => {
          this.prescriptions = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
      this.axios
        .get(
          "/prescriptions?count=true&expiring=true&patient_id=" +
            this.$auth.user().id
        )
        .then(response => {
          this.expiringPrescriptions = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
    },
    fetchTests() {
      this.newTests = 0;
      this.axios
        .get("/tests?count=true&patient_id=" + this.$auth.user().id)
        .then(response => {
          this.newTests = response.data;
        })
        .catch(e => {
          this.errors.push(e);
        });
    },
    getTimeString(date) {
      var ampm = "am";
      var h = date.getHours();
      var m = date.getMinutes();
      if (h >= 12) {
        if (h > 12) h -= 12;
        ampm = "pm";
      }
      if (m < 10) m = "0" + m;
      return h + ":" + m + " " + ampm;
    }
  }
};
</script>