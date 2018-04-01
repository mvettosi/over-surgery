<template>
    <v-container grid-list-md>
        <v-layout row wrap>
            <v-flex xs7 sm5 md3 lg3 xl2>
                <v-select :items="workerTypes" name="worker_type" v-model="workerType"></v-select>
            </v-flex>
            <v-flex xs4 sm3 md2 lg2 xl1>
                <v-select :items="checkTypes" name="account_type" v-model="checkType"></v-select>
            </v-flex>
            <v-flex xs5 sm3 md2 lg2 xl1>
                <v-menu ref="menu" lazy :close-on-content-click="false" v-model="dateMenu" transition="scale-transition" offset-y full-width :nudge-right="40" min-width="290px">
                    <v-text-field slot="activator" prepend-icon="event" v-model="searchDate" readonly></v-text-field>
                    <v-date-picker ref="picker" v-model="searchDate"></v-date-picker>
                </v-menu>
            </v-flex>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="fetchData()" icon>
                <v-icon>refresh</v-icon>
            </v-btn>
        </v-layout>
        <v-layout justify-center column v-if="workerType == 'doctor' || workerType == 'both'">
            <v-subheader>Doctors</v-subheader>
            <v-expansion-panel :popout="$vuetify.breakpoint.smAndDown">
                <v-expansion-panel-content hide-actions v-for="(doctor, i) in doctors" :key="i">
                    <v-layout align-center row slot="header">
                        <v-flex xs2 sm1 md1 lg1 xl1>
                            <v-avatar size="36px" slot="activator">
                                <img :src="doctor.sex && doctor.sex === 'female' ? 'images/woman.jpg' : 'images/man.jpg'" alt="">
                            </v-avatar>
                        </v-flex>
                        <v-flex xs6 sm9 md2 lg2 xl3 class="title">
                            {{ doctor.name }} {{ doctor.surname }}
                        </v-flex>
                        <v-flex xs4 sm3 md4 lg3 xl3 hidden-sm-and-down class="subheading">
                            {{ doctor.email }}
                        </v-flex>
                        <v-flex xs4 sm3 md3 lg4 xl4 hidden-sm-and-down class="subheading">
                            {{ doctor.phone }}
                        </v-flex>
                        <v-flex xs1 sm1 md1 lg2 xl1 v-if="checkType === 'available'">
                            <v-menu transition="slide-y-transition" bottom>
                                <v-btn color="primary" slot="activator">Book</v-btn>
                                <v-list>
                                    <v-list-tile v-for="hours in doctor.availableHours" :key="hours.title" @click="bookAppointment(doctor.id, hours.title)">
                                        <v-list-tile-title>{{ hours.title }}</v-list-tile-title>
                                    </v-list-tile>
                                </v-list>
                            </v-menu>
                        </v-flex>
                    </v-layout>
                    <v-card v-if="$vuetify.breakpoint.smAndDown">
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-layout row wrap align-center>
                                <v-flex xs6 hidden-xs-only class="title">
                                    Email:
                                </v-flex>
                                <v-flex xs6 class="subheading">
                                    {{ doctor.email }}
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap align-center>
                                <v-flex xs6 hidden-xs-only class="title">
                                    Phone number:
                                </v-flex>
                                <v-flex xs6 class="subheading">
                                    {{ doctor.phone }}
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-layout>
        <v-layout justify-center column v-if="workerType === 'nurse' || workerType === 'both'">
            <v-subheader>Nurses</v-subheader>
            <v-expansion-panel :popout="$vuetify.breakpoint.smAndDown">
                <v-expansion-panel-content hide-actions v-for="(nurse, i) in nurses" :key="i">
                    <v-layout align-center row slot="header">
                        <v-flex xs2 sm1 md1 lg1 xl1>
                            <v-avatar size="36px" slot="activator">
                                <img :src="nurse.sex && nurse.sex === 'female' ? 'images/woman.jpg' : 'images/man.jpg'" alt="">
                            </v-avatar>
                        </v-flex>
                        <v-flex xs6 sm9 md2 lg2 xl3 class="title">
                            {{ nurse.name }} {{ nurse.surname }}
                        </v-flex>
                        <v-flex xs4 sm3 md4 lg3 xl3 hidden-sm-and-down class="subheading">
                            {{ nurse.email }}
                        </v-flex>
                        <v-flex xs4 sm3 md3 lg4 xl4 hidden-sm-and-down class="subheading">
                            {{ nurse.phone }}
                        </v-flex>
                        <v-flex xs1 sm1 md1 lg2 xl1 v-if="checkType === 'available'">
                            <v-menu transition="slide-y-transition" bottom>
                                <v-btn color="primary" slot="activator">Book</v-btn>
                                <v-list>
                                    <v-list-tile v-for="hours in nurse.availableHours" :key="hours.title" @click="bookAppointment(nurse.id, hours.title)">
                                        <v-list-tile-title>{{ hours.title }}</v-list-tile-title>
                                    </v-list-tile>
                                </v-list>
                            </v-menu>
                        </v-flex>
                    </v-layout>
                    <v-card v-if="$vuetify.breakpoint.smAndDown">
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-layout row wrap align-center>
                                <v-flex xs6 hidden-xs-only class="title">
                                    Email:
                                </v-flex>
                                <v-flex xs6 class="subheading">
                                    {{ nurse.email }}
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap align-center>
                                <v-flex xs6 hidden-xs-only class="title">
                                    Phone number:
                                </v-flex>
                                <v-flex xs6 class="subheading">
                                    {{ nurse.phone }}
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-layout>
    </v-container>
</template>
<script>
export default {
  data() {
    var checkTypesList = [
      { text: "Available", value: "available" },
      { text: "On Duty", value: "on_duty" }
    ];
    var workerTypesList = [
      { text: "Doctors and Nurses", value: "both" },
      { text: "Doctors", value: "doctor" },
      { text: "Nurses", value: "nurse" }
    ];
    var today = new Date().toJSON().slice(0, 10);
    return {
      checkType: checkTypesList[0].value,
      checkTypes: checkTypesList,
      workerType: workerTypesList[0].value,
      workerTypes: workerTypesList,
      dateMenu: false,
      searchDate: today,
      doctors: [],
      nurses: [],
      errors: []
    };
  },
  created() {
    this.fetchData();
  },
  watch: {
    checkType: function() {
      this.fetchData();
    },
    workerType: function() {
      this.fetchData();
    },
    searchDate: function() {
      this.fetchData();
    }
  },
  methods: {
    fetchData() {
      this.doctors = [];
      this.nurses = [];
      if (this.workerType == "doctor" || this.workerType == "both") {
        var url =
          "/users?account_type=doctor&" +
          this.checkType +
          "=true&date=" +
          this.searchDate;
        this.axios
          .get(url)
          .then(response => {
            this.doctors = response.data;
          })
          .catch(e => {
            this.$root.$snackbar.open(e.response.data.message, {
              color: "error"
            });
          });
      }
      if (this.workerType == "nurse" || this.workerType == "both") {
        var url =
          "/users?account_type=nurse&" +
          this.checkType +
          "=true&date=" +
          this.searchDate;
        this.axios
          .get(url)
          .then(response => {
            this.nurses = response.data;
          })
          .catch(e => {
            this.$root.$snackbar.open(e.response.data.message, {
              color: "error"
            });
          });
      }
    },
    bookAppointment(id, time) {
      var url = "/appointments";
      this.$root.$snackbar.close();
      this.axios
        .post(url, {
          start_time: this.searchDate + " " + time,
          doctor_or_nurse_id: id
        })
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