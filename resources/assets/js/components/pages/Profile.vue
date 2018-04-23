<template>
    <v-container grid-list-md>
        <v-layout row wrap>
            <v-flex xs12 sm6>
                <v-card class="pa-2">
                    <v-toolbar>
                        <v-toolbar-title class="mx-auto primary--text">Change Password</v-toolbar-title>
                    </v-toolbar>
                    <v-form @submit.prevent="changePassword" v-model="valid">
                        <v-card-text>
                            <v-layout row>
                                <v-flex xs12 class="mx-2">
                                    <v-text-field name="old_password" label="Old Password" :type="p1 ? 'password' : 'text'" min="8" prepend-icon="lock-open" :append-icon="p1 ? 'visibility' : 'visibility_off'" :append-icon-cb="() => (p1 = !p1)" :rules="passwordRules" v-model="old_password" require></v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row>
                                <v-flex xs12 class="mx-2">
                                    <v-text-field name="new_password1" label="New Password" :type="p1 ? 'password' : 'text'" min="8" prepend-icon="lock-open" :append-icon="p1 ? 'visibility' : 'visibility_off'" :append-icon-cb="() => (p1 = !p1)" :rules="passwordRules" v-model="new_password1" require></v-text-field>
                                </v-flex>
                            </v-layout>
                            <v-layout row>
                                <v-flex xs12 class="mx-2">
                                    <v-text-field name="new_password2" label="Repeat the new password" :type="p1 ? 'password' : 'text'" min="8" prepend-icon="lock-open" :append-icon="p1 ? 'visibility' : 'visibility_off'" :append-icon-cb="() => (p1 = !p1)" :rules="newPasswordRules" v-model="new_password2" require></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn type="submit" color="primary" class="text-lg-right" :disabled="!valid">Change Password</v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            valid: true,
            p1: true,
            old_password: '',
            new_password1: '',
            new_password2: '',
            passwordRules: [v => !!v || "password is required"],
            newPasswordRules: [
                v => !!v || "password is required",
                v => this.new_password1 == this.new_password2 || 'The new password and the password confirmation must match'
            ],
        }
    },
    methods: {
        changePassword() {
            var url = "/users/" + this.$auth.user().id;
            this.$root.$snackbar.close();
            this.axios
                .put(url, {
                    old_password: this.old_password,
                    new_password: this.new_password1
                })
                .then(response => {
                    this.$root.$snackbar.open(response.data.message, {
                        color: "success"
                    });
                    this.old_password = '';
                    this.new_password1 = '';
                    this.new_password2 = '';
                    this.$auth.logout();
                })
                .catch(e => {
                    this.$root.$snackbar.open(e.response.data.message, {
                        color: "error"
                    });
                });
        }
    }
}
</script>

<style>

</style>
