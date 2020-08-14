<template>
    <div>
        <input type="text" v-model="messageSend">
        <div v-for="(message,i) in messages" :key="i">
            {{ message }}
        </div>
        <button @click="click">Click</button>
    </div>
</template>

<script>
    import AuthSocket from "../js/AuthSocket";
    export default {
        data() {
            return {
                messageSend:"",
                socket: new AuthSocket("ws://127.0.0.1:8989/auth"),
                messages:[]
            }
        },
        created () {
            this.socket.onmessageAuth=(e,data)=>{
                console.log('data :>> ', data);
                this.messages.push(data.message);
            }
        },
        methods: {
            click() {
                this.socket.send({message:this.messageSend});
                this.messageSend="";
            }
        },
    }
</script>

<style scoped>

</style>