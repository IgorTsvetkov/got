<template>
    <div class="f f-row f-center f-wrap">
        <div v-for="(img, index) in imgs" :key="index">
            <div class="hero-img m-10">
                <img  width="100px" :src="'/web/images/figurines/'+img">
            </div>
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
            imgType:{
                type:String,
                default:"0"
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
    }
</script>

<style scoped>
    .hero-img:hover{
        cursor:pointer;
        filter: brightness(80%);
    }
</style>