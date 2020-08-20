<template>
    <form :method="method" :action="action" @submit.prevent="ajaxSubmit">
        123
        <slot></slot>
    </form>
</template>

<script>
    export default {
        props: {
            method: {
                type: String,
                default: "GET"
            },
            action: {
                type: String,
                default: "/"
            },
        },
        methods: {
            ajaxSubmit(e) {
                alert("hello");
                return false;
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>