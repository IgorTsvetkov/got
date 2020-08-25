<template>
    <div v-show="show" :class="type" class="alert alert-dismissible fade show" role="alert">
      <slot><strong>Holy guacamole!</strong> You should check in on some of those fields below.</slot>
      <button
        type="button"
        class="close"
        data-dismiss="alert"
        aria-label="Close"
        @click="hide"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: "alert-danger"
            },
        },
        data() {
            return {
                show: true,
            }
        },
        created(){
            setTimeout(()=>this.hide(),5000);
        },
        methods: {
            hide() {
                this.show=false;
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>