<template>
    <v-container class="chat-log">
        <chat-message v-for="(message, index) in messages" :key="`message-${index}`" :message="message"></chat-message>
    </v-container>
</template>

<script>
export default {
  props: ["messages"]
};
</script>

<style>
.chat-log .chat-message:nth-child(even) {
  background-color: #ccc;
}
</style>
