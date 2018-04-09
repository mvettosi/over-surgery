<template>
    <v-container>
        <v-layout row>
            <h1>Chat</h1>
        </v-layout>
        <v-layout row>
            <chat-log :messages="messages"></chat-log>
        </v-layout>
        <v-footer app inset :style="{height: chatComposerHeight}">
            <chat-composer @messagesent="addMessage"></chat-composer>
        </v-footer>
    </v-container>
</template>

<script>
import Echo from "laravel-echo";
export default {
  data() {
    return {
      messages: [],
      chatComposerHeight: "80px"
    };
  },
  created() {
    this.fetchMessages();

    window.Pusher = require("pusher-js");
    window.Pusher.logToConsole = true;

    window.Echo = new Echo({
      broadcaster: "pusher",
      key: process.env.MIX_PUSHER_APP_KEY,
      cluster: process.env.MIX_PUSHER_APP_CLUSTER,
      encrypted: true,
      auth: {
        headers: {
          Authorization: "Bearer " + this.$auth.token()
        }
      }
    });

    window.Echo.join("chatroom")
      .here(users => {
        //
      })
      .joining(user => {
        console.log(user.name);
      })
      .leaving(user => {
        console.log(user.name);
      })
      .listen("MessagePosted", e => {
        this.messages.push({
          message: e.message.message,
          user: e.user
        });
      });
  },
  methods: {
    addMessage(message) {
      this.messages.push({
        message: message,
        user: this.$auth.user()
      });
      var url = "/messages";
      this.axios
        .post(url, {
          message: message
        })
        .then(response => {})
        .catch(e => {
          this.$root.$snackbar.open(e.response.data.message, {
            color: "error"
          });
        });
    },
    fetchMessages() {
      this.messages = [];
      var url = "/messages";
      this.axios
        .get(url)
        .then(response => {
          this.messages = response.data;
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

<style>

</style>
