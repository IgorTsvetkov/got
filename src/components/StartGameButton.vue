<template>
    <div>
        <a @click="startGame" class="btn btn-primary w-100"><slot></slot></a>
    </div>
</template>

<script>
    import axios from "axios";
    export default {
        props: {
            action: {
                type: String,
                default: ""
            },
        },
        methods: {
            startGame(e) {
                if(this.action)
                    axios.post(this.action).then(res=>{
                        if(res.data.started){
                            this.socket.send({action:"start-game"});
                        }
                    });
            }
        },
        data() {
            return {
                socket:this.$socketGet("send-to-all"),
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>