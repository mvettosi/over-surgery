<template>
    <v-container>
        <v-layout row>
            <v-spacer></v-spacer>
            <v-btn color="primary" :disabled="(isPatient && messages.length == 0) || (isReceptionist && recipientId == null)" @click="closeChat">Close Chat &nbsp;
                <v-icon>close</v-icon>
            </v-btn>
        </v-layout>
        <v-layout row>
            <v-container class="chat-log">
                <v-card class="chat-message" v-for="(message, index) in messages" :key="`message-${index}`" @click="pickPatient(message)">
                    <v-layout>
                        <v-flex xs9>
                            <small class="subheading">{{ getSenderName(message) }}:</small>
                            <p class="title">{{ message.message }}</p>
                        </v-flex>
                        <v-flex xs3>
                            <v-btn color="primary" v-if="(isReceptionist && recipientId == null)" @click="pickPatient(message)">Accept Chat</v-btn>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-container>
        </v-layout>
        <v-footer app inset :style="{height: chatComposerHeight}">
            <v-container>
                <v-layout>
                    <v-flex xs11>
                        <v-text-field name="message-input" label="Type your message here" id="message-input" v-model="messageText" @keyup.enter="addMessage" :disabled="(isPatient && awaitingConnection) || (isReceptionist && recipientId == null)" autofocus></v-text-field>
                    </v-flex>
                    <v-flex xs1 align-end style="min-width: 100px">
                        <v-btn xs1 color="primary" flat @click="addMessage" :disabled="(isPatient && awaitingConnection) || (isReceptionist && recipientId == null)">Send &nbsp;
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
            // Dependencies
            Pusher: null,
            Echo: null,

            // State variables
            messageText: "",
            messages: [],
            awaitingConnection: false,
            recipientId: null,

            // Shortcuts
            isPatient: false,
            isReceptionist: false,
            userChannel: '',

            // Other configs
            chatComposerHeight: "80px"
        };
    },
    created() {
        // Init dependencies
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

        // Initialize shortcuts
        this.isReceptionist = this.$auth.user().account_type === "receptionist";
        this.isPatient = this.$auth.user().account_type === "patient";
        this.userChannel = "user-" + this.$auth.user().id;

        // Initialize data
        if (this.isReceptionist) {
            this.fetchRequests();
        }
        this.joinPairingChannel();
        // this.Echo.join("user-4").listen("MessageSent", this.eventReceived);
    },
    methods: {
        // Sending Messages
        getSenderName(message) {
            return message.sender.name + ' ' + message.sender.surname;
        },
        addMessage() {
            var url = "/messages";
            if (
                this.isPatient &&
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
                    this.Echo.private(this.userChannel).listen(
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
            var url = "/messages";
            this.axios
                .post(url, {
                    message: this.messageText,
                    recipient_id: this.recipientId
                })
                .then(response => {
                    this.messageText = "";
                })
                .catch(e => {
                    this.$root.$snackbar.open(e.response.data.message, {
                        color: "error"
                    });
                });
        },
        // Receiving Messages
        eventReceived(e) {
            this.messages.push(e.message);
            if (this.awaitingConnection) {
                this.awaitingConnection = false;
                this.recipientId = e.message.sender_id;
            }
        },
        // Closing chat
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
            this.Echo.leave(this.userChannel);
        },
        // Joining Channels
        joinPairingChannel() {
            this.Echo.join("pairing-channel")
                .here(users => { })
                .joining(user => {
                    console.log(user.name + " " + user.surname + " has entered");
                })
                .leaving(user => {
                    if (this.isReceptionist) {
                        this.messages = this.messages.filter(message => {
                            message.sender_id != user.id
                        });
                    }
                })
                .listen("ChatRequest", e => {
                    if (this.isReceptionist) {
                        this.eventReceived(e);
                    }
                });
        },
        pickPatient(message) {
            if (this.isReceptionist && this.recipientId == null) {
                this.recipientId = message.sender.id;
                this.messages = [message];
                this.Echo.private("user-" + message.sender.id).listen(
                    "MessageSent",
                    this.eventReceived
                );
            }
        },
        // Fetching Data
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
    },
    beforeRouteLeave(to, from, next) {
        this.closeChat();
        next();
    }
};
</script>

<style>
.chat-log .chat-message:nth-child(even) {
  background-color: #ccc;
}
.chat-message {
  padding: 1rem;
}
.chat-message > p {
  margin-bottom: 0.5rem;
}
</style>
