<template>
    <full-calendar :events="fcEvents" locale="en"></full-calendar>
</template>
<script>
var demoEvents = [
    {
        title: "Sunny Out of Office",
        start: "2018-04-15",
        end: "2018-04-27"
    }
];
export default {
    data() {
        return {
            fcEvents: demoEvents
        };
    },
    components: {
        "full-calendar": require("vue-fullcalendar")
    }
};
</script>