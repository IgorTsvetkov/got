<template>
    <div class="p-4 lead position-relative">
        <a class="btn btn-light" @click="toggle"><img src="/web/images/open-menu.svg" height="50px" ></a>
        <div v-if="this.show" class="bg-dark text-light p-2 position-absolute offset-top-50">
        <h4 class="text-center">Матч</h4>
        <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                show: false,
            }
        },
        methods: {
            toggle() {
                this.show=!this.show;
            }
        },
    }
</script>

<style scoped>
    .offset-top-50{
        /* top:50px; */
    }
</style>