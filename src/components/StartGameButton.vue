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
            async startGame(e) {
                if(this.action){
                    let result=await this.$axios.post(this.action);
                    this.$emit("startGame",result);
                }

            }
        },
    }
</script>

<style lang="scss" scoped>

</style>