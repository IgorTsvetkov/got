<template>
        <div class="cell-wrapper f f-column">
            <div v-if="this.price" :class="[price,price_bgcolor]">{{this.price}}</div>
            <img :src="this.src" alt="">
        </div>
</template>

<script>
    export default {
        props: {
            src: {
                type: String,
                default:""
                },
            price:{
                type: String,
                default:""
            },
            text_bgcolor:{
                type:String,
                default:"bgblack"
            }
        },
        data() {
            return {
                price_bgcolor:this.text_bgcolor
            }
        },
        created(){
        }
    }
</script>

<style scoped>
.cell-wrapper{
    width: 100%;
    height: auto;
}
.cell-wrapper img,.cell-center img{
    width: inherit;
}
.f{
    display: flex;
}
.f-center{
    justify-content: center;
    align-items: center;
}
.f-column{
    flex-direction: column;
}
.price{
    background:white;
    text-align:center;
    font-size:0.8em;
}
.bgblack{
    background:black;
}
.bgred{
    background:red;
}
</style>