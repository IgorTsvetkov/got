<template>
<div>
  <div class="f f-row f-center f-wrap text-center">
    <div class="hpc-block m-5" v-for="(img, index) in imgs" :key="index" @click="changeSlot(index)">
      <div class="hero-img m-10">
        <img width="100px" :src="'/web/images/figurines/'+img"/>
      </div>
      <h4 class="hpc-green" v-if="users[index]">{{users[index]}}</h4>
      <h4 v-else>{{"Место "+(1+index)}}</h4>
    </div>
  </div>
    <div v-if="error" class="hpc-error p-20">{{error.message}}</div>
</div>
</template>

<script>
import AuthSocket from '../js/AuthSocket'
import axios from 'axios'
    export default {
        props: {
            name: {
                type: String,
                default: "field_name"
            },
            users_string:{
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
                users:{},
                error:null,
                socket:new AuthSocket("ws://127.0.0.1:8989/change-slot"),
            }
        },
        methods: {
            changeSlot(value) {
                this.socket.send({slot:value});
            },
            usersStringToObj(users_string){
                let users=JSON.parse(users_string); 
                return this.usersTransform(users);
            },
            usersTransform(users){
                let obj={}
                users.forEach((item,key)=>obj[item.slot]=item.username);
                return obj;
            }
        },
        created () {
            this.users=this.usersStringToObj(this.users_string);

            this.socket.onmessageAuth=(e,res)=>{
                console.log(res);            
                if(res.error){
                    this.error=res.error;
                    return;
                }
                this.error=null;
                if(res.data&&res.data.users)
                    this.users=this.usersTransform(res.data.users);
            }
        },
        computed: {
            // parsedUsers() {
            //     if(this.users){
            //         let x=JSON.parse(this.users); 
            //         window.x=x;
            //         let b={};
            //         x.forEach((item,key)=>b[item.slot]=item.username);
            //         console.log("users",b);
            //         return b;
            //     }
            // }

        },
    }
</script>

<style scoped>

.hpc-block{
    background: #909090;
    border: unset;
    border-radius: 10px;
    box-shadow: 0 0 3px 1px black;
}
.hpc-block:hover {
  cursor: pointer;
  filter: brightness(80%);
}
.hpc-error{
    background:rgb(199, 4, 4);
    color:white;
    padding: 20px;
    border-radius:10px;
    margin:5px;
}
.hpc-green{
    color:darkgreen;

}
</style>