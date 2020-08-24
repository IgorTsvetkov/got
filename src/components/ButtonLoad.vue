<template>
    <a  @click='loadHtml'><slot></slot></a>
</template>

<script>
    

    export default {
        props: {
            action: {
                type: String,
                default: undefined
            },
            to: {
                type: String,
                default: undefined
            },
        },
        data() {
            return {
                data: ""
            }
        },
        methods: {
            async loadHtml(){
                if(this.action&&this.to){
                    let result = await this.$axios.get(this.action);
                    let toEl=document.querySelector(this.to);
                    this.data=result.data;
                    toEl.innerHTML=result.data;            
                }
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>