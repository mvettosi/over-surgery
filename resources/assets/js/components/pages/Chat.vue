<template>
    <v-container>
        <v-layout row>
            <v-spacer></v-spacer>
            <v-btn color="primary" :disabled="messages.length == 0" @click="closeChat">Close Chat &nbsp;
                <v-icon>close</v-icon>
            </v-btn>
        </v-layout>
        <v-layout row>
            <chat-log :messages="messages"></chat-log>
        </v-layout>
        <v-footer app inset :style="{height: chatComposerHeight}">
            <v-container>
                <v-layout>
                    <v-flex xs11>
                        <v-text-field name="message-input" label="Type your message here" id="message-input" v-model="messageText" @keyup.enter="addMessage" :disabled="awaitingConnection" autofocus></v-text-field>
                    </v-flex>
                    <v-flex xs1 align-end style="min-width: 100px">
                        <v-btn xs1 color="primary" flat @click="addMessage">Send &nbsp;
                            <v-icon>send</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-footer>
    </v-container>
</template>

<script>
import Echo from "laravel-echo";
export default {
    data() {
        return {
            Pusher: null,
            Echo: null,
            messageText: "",
            messages: [],
            awaitingConnection: false,
            recipientId: null,
            chatComposerHeight: "80px"
        };
    },
    created() {
        this.Pusher = require("pusher-js");
        this.Echo = new Echo({
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
        window.onbeforeunload = this.closeChat;

        if (this.$auth.user().account_type === "receptionist") {
            this.fetchRequests();
        }
        this.joinPairingChannel();
        // this.Echo.join("user-4").listen("MessageSent", this.eventReceived);
    },
    methods: {
        addMessage() {
            var url = "/messages";
            if (
                this.$auth.user().account_type === "patient" &&
                this.messages.length == 0
            ) {
                this.requestChat();
            } else {
                this.sendPrivateMessage();
            }
        },
        requestChat() {
            var url = "/messages";
            this.axios
                .post(url, {
                    message: this.messageText
                })
                .then(response => {
                    var msg = response.data;
                    msg.sender = this.$auth.user();
                    this.messages.push(msg);
                    this.Echo.private("user-" + this.$auth.user().id).listen(
                        "MessageSent",
                        this.eventReceived
                    );
                    this.awaitingConnection = true;
                    this.messageText = "";
                })
                .catch(e => {
                    this.messages = [];
                    this.$root.$snackbar.open(e.response.data.message, {
                        color: "error"
                    });
                });
        },
        sendPrivateMessage() {
            // TODO
            this.axios
                .post(url, {
                    message: this.messageText,
                    recipientId: this.recipientId
                })
                .then(response => {
                    this.awaitingConnection = true;
                })
                .catch(e => {
                    this.$root.$snackbar.open(e.response.data.message, {
                        color: "error"
                    });
                });
        },
        eventReceived(e) {
            if (this.$auth.user().account_type === "receptionist") {
                this.messages.push(e.message);
            }
        },
        closeChat() {
            // The request was sent but is being resigned before any receptionist could pick it up
            if (this.awaitingConnection) {
                var url = "/messages/" + this.messages[0].id;
                this.axios
                    .delete(url)
                    .then(response => {
                        this.cleanChatLog();
                        this.Echo.leave('pairing-channel');
                        this.joinPairingChannel();
                    })
                    .catch(e => {
                        this.$root.$snackbar.open(e.response.data.message, {
                            color: "error"
                        });
                    });
            }
        },
        cleanChatLog() {
            this.messages = [];
            this.awaitingConnection = false;
            this.Echo.leave();
        },
        joinPairingChannel() {
            this.Echo.join("pairing-channel")
                .here(users => { })
                .joining(user => {
                    console.log(user.name + " " + user.surname + " has entered");
                })
                .leaving(user => {
                    if (this.$auth.user().account_type === "receptionist") {
                        this.messages = this.messages.filter(message => {
                            message.sender_id != user.id
                        });
                    }
                })
                .listen("ChatRequest", this.eventReceived);
        },
        fetchRequests() {
            this.messages = [];
            var url = "/messages?requests=true";
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
