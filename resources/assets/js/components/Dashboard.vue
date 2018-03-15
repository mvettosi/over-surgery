<template>
    <v-app class="teal">
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12 sm6>
                    <v-card height="100%" to="/availability">
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
                    <v-card height="100%">
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
                    <v-card height="100%">
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
                    <v-card height="100%">
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
    </v-app>
</template>
<script>
export default {
  data() {
    return {
      doctorsOnDuty: 0,
      doctorsAvailable: 0,
      nursesOnDuty: 0,
      nursesAvailable: 0,
      appointmentsThisMonth: 1,
      nextAppDate: "20/02/2018",
      nextAppTime: "3:00 pm",
      nextAppLocation: "12 King Street, Cambridge",
      nextAppDoctor: "John Heope",
      prescriptions: 0,
      expiringPrescriptions: 0,
      newTests: 0
    };
  }
};
</script>