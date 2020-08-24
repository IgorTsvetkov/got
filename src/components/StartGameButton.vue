<template>
    <div>
        <a @click="startGame" class="btn btn-primary w-100"><slot></slot></a>
    </div>
</template>

<script>
    
    export default {
        props: {
            action: {
                type: String,
                default: ""
            },
            game_id:{
                type:String,
                default:undefined
            }
        },
        methods: {
            startGame(e) {
                if(this.action)
                    this.$axios.post(this.action).then(res=>{
                        if(res.data.started){
                            this.socket.send({action:"start-game"});
                        }
                    });
            }
        },
        data() {
            return {
                socket:undefined,
            }
        },
        created () {
            this.socket=this.$socketGet(this.game_id,"send-local-to-all");
            this.socket.addMessageCallback((e,res)=>{
                if (res.action == "start-game") 
                    window.location.pathname = "/got/game";

            })
        },
    }
</script>

<style lang="scss" scoped>

</style>