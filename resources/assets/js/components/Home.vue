<template>
    <v-app>
        <v-navigation-drawer :clipped="clipped" v-model="drawer" enable-resize-watcher app class="white">
            <v-toolbar flat class="transparent">
                <v-list class="pa-0">
                    <v-list-tile avatar>
                        <v-list-tile-avatar>
                            <img src="images/man.jpg">
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ $auth.user().name }} {{ $auth.user().surname }}</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-toolbar>
            <v-divider></v-divider>
            <v-list dense class="pt-0">
                <v-list-tile v-for="item in items" v-if="item.role === $auth.user().account_type" :key="item.title" :to="item.path">
                    <v-list-tile-action>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar fixed dark app :clipped-left="clipped" color="primary">
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title class="white--text">Over Surgery</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu bottom left>
                <v-btn icon slot="activator">
                    <v-icon>more_vert</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile v-for="(menu, i) in menus" :key="i" @click="menuAction(menu.title)">
                        <v-list-tile-title>{{ menu.title }}</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
        </v-toolbar>
        <v-content>
            <main>
                <v-confirm ref="confirm"></v-confirm>
                <v-material-snackbar ref="snackbar"></v-material-snackbar>
                <router-view></router-view>
            </main>
        </v-content>
    </v-app>
</template>
<script>
import Confirm from "./Confirm.vue";
import MaterialSnackbar from "./MaterialSnackbar.vue";
export default {
  data() {
    return {
      drawer: true,
      clipped: false,
      items: [
        {
          icon: "dashboard",
          title: "Dashboard",
          path: "/dashboard",
          role: "patient"
        },
        {
          icon: "person",
          title: "Availability",
          path: "/availability",
          role: "patient"
        },
        {
          icon: "event",
          title: "Appointments",
          path: "/appointments",
          role: "patient"
        },
        {
          icon: "attachment",
          title: "Prescriptions",
          path: "/prescriptions",
          role: "patient"
        },
        {
          icon: "calendar",
          title: "Calendar",
          path: "/calendar",
          role: "receptionist"
        }
      ],
      menus: [
        {
          title: "Logout"
        }
      ]
    };
  },
  components: {
    "v-confirm": Confirm,
    "v-material-snackbar": MaterialSnackbar
  },
  created() {
    this.$router.push("prescriptions");
  },
  mounted() {
    this.$root.$confirm = this.$refs.confirm;
    this.$root.$snackbar = this.$refs.snackbar;
  },
  methods: {
    menuAction(title) {
      switch (title) {
        case "Logout":
          this.$auth.logout();
          break;
      }
    }
  }
};
</script>