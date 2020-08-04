window.Vue=require('vue');

import HelloWorldComponent from './components/HelloWorldComponent.vue';
const app=new Vue({
    el:'#app',
    components:{
        HelloWorldComponent
    }
})