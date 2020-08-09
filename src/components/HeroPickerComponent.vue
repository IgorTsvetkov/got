<template>
    <div class="f f-row f-center f-wrap text-center">
        <div v-for="(img, index) in imgs" :key="index">
            <div class="hero-img m-10">
                <img  width="100px" :src="'/web/images/figurines/'+img">
            </div>
            <h1>{{parsedUsernames[index]}}</h1>
        </div>
    </div>
</template>

<script>
import AuthSocket from '../js/AuthSocket'
    export default {
        props: {
            name: {
                type: String,
                default: "field_name"
            },
            usernames:{
                type:String,
                default:""
            }
        },
        data() {
            return {
                imgs: [
                    "figure1.png",
                    "figure2.png",
                    "figure3.png",
                    "figure4.png",
                    "figure5.png",
                    "figure6.png",
                    "figure7.png",
                    "figure8.png",
                ],
                socket:new AuthSocket("ws://127.0.0.1:8989/pick"),
            }
        },
        created () {
            this.socket.onmessage=(e,{data})=>{
                
            }
        },
        computed: {
            parsedUsernames() {
                if(this.usernames){
                    let x=JSON.parse(this.usernames); 
                    console.log("usernames",x);
                    return x;
                }
            }
        },
    }
</script>

<style scoped>
    .hero-img:hover{
        cursor:pointer;
        filter: brightness(80%);
    }
</style>