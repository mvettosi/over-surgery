<template>
    <v-container>
        <v-layout>
            <v-flex xs11>
                <v-text-field name="message-input" label="Type your message here" id="message-input" v-model="messageText" @keyup.enter="sendMessage" autofocus></v-text-field>
            </v-flex>
            <v-flex xs1 align-end style="min-width: 100px">
                <v-btn xs1 color="primary" flat @click="sendMessage">Send &nbsp;
                    <v-icon>send</v-icon>
                </v-btn>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
export default {
  data() {
    return {
      messageText: ""
    };
  },
  methods: {
    sendMessage() {
      this.$emit("messagesent", this.messageText);
      this.messageText = "";
    }
  }
};
</script>

<style>

</style>
