<template>
    <div class="btn btn-secondary text-light w-100 " @click="startAuction"><slot>Начать аукцион</slot></div>
</template>

<script>
    export default {
        props: {
            "type": {
                type: String,
                required:true
            },
            "id":{
                type: Number,
                required:true
            }
        },
        methods: {
            async startAuction() {
                let result=await this.$axios.post(`/auction/start?type=${this.type}&id=${this.id}`);
                this.$parent.$emit("auctionStarted",result);
            },
        },
    }
</script>