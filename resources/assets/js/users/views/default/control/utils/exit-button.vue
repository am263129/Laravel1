<template>
    <div class="exit-button">
        <img src="/themes/default/img/exit-icon.svg" alt="exit icon" width="100%">
    </div>
</template>

<script>
export default {
    methods: {
        BACK() {
            this.$store.commit("SHOW_SERIES_DETAILS_PAGE", false)
        }
    }
}
</script>

<style scoped>
    .exit-button {
        width:20px;
        transition: 300ms;
    }
    .exit-button:hover {
        transform: scale(1.2);
    }

</style>