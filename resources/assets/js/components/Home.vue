<template>
    <!-- <div class="panel panel-default">
        <div class="panel-heading">
            <nav>
                <ul class="list-inline">
                    <li>
                        <router-link :to="{ name: 'home' }">Home</router-link>
                    </li>
                    <li v-if="!$auth.check()" class="pull-right">
                        <router-link :to="{ name: 'login' }">Login</router-link>
                    </li>
                    <li v-if="!$auth.check()" class="pull-right">
                        <router-link :to="{ name: 'register' }">Register</router-link>
                    </li>
                    <li v-if="$auth.check()" class="pull-right">
                        <a href="#" @click.prevent="$auth.logout()">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
        <h1>Home</h1>
    </div> -->
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
            <v-btn icon>
                <v-icon>more_vert</v-icon>
            </v-btn>
        </v-toolbar>
        <v-content>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-content>
    </v-app>
    <!-- <v-app light>
        <v-navigation-drawer :clipped="clipped" v-model="drawer" enable-resize-watcher app class="white">
            <v-list>
                <v-list-group v-for="item in items" :value="item.active" :key="item.title">
                    <v-list-tile slot="item" :to="item.path == '#' ? '' : item.path" :exact="item.exact" class="yellow--text" active-class="red--text">
                        <v-list-tile-action>
                            <v-icon>{{ item.action }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action v-if="item.items.length > 0">
                            <v-icon>keyboard_arrow_down</v-icon>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list-group>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar fixed app :clipped-left="clipped">
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title>Topics</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon>
                <v-icon>more_vert</v-icon>
            </v-btn>
        </v-toolbar>
        <v-content>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-content>
    </v-app> -->
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
        // {
        //   action: "local_activity",
        //   title: "Attractions",
        //   path: "/",
        //   items: []
        // },
        // {
        //   action: "restaurant",
        //   title: "Breakfast",
        //   path: "/breakfast",
        //   items: []
        // }
        // { title: "Home", icon: "dashboard" },
        // { title: "About", icon: "question_answer" }
      ]
    };
  }
};
</script>