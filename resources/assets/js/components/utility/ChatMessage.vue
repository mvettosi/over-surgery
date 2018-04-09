<template>
    <v-card class="chat-message">
        <small class="subheading">{{ message.user.name + ' ' + message.user.surname }}:</small>
        <p class="title">{{ message.message }}</p>
    </v-card>
</template>

<script>
export default {
  data() {
    return {};
  },
  props: ["message"]
};
</script>

<style>
.chat-message {
  padding: 1rem;
}
.chat-message > p {
  margin-bottom: 0.5rem;
}
</style>
