<template>
    <div class="d-flex justify-content-between">
      <div>{{label}}</div>
      <div>{{cost}}</div>
    </div>
</template>

<script>
    export default {
        props: {
            label: {
                type: String,
                default: "rentField"
            },
            cost: {
                type: Number,
                default: 0
            },
        },
    }
</script>

<style  scoped>

</style>