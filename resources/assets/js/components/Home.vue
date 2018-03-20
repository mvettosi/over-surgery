<template>
    <v-app>
        <v-navigation-drawer :clipped="clipped" v-model="drawer" enable-resize-watcher app class="white">
            <v-toolbar flat>
                <v-list>
                    <v-list-tile>
                        <v-list-tile-title class="title">
                            Application
                        </v-list-tile-title>
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
        <v-toolbar fixed app :clipped-left="clipped">
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title>Topics</v-toolbar-title>
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
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-content>
    </v-app>
</template>
<script>
export default {
  data() {
    return {
      drawer: true,
      clipped: false,
      items: [
        {
          icon: "local_activity",
          title: "Dashboard",
          path: "/dashboard",
          role: "patient"
        },
        {
          icon: "restaurant",
          title: "Availability",
          path: "/availability",
          role: "patient"
        },
        {
          icon: "restaurant",
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
  created() {
    this.$router.push("dashboard");
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