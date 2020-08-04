window.Vue=require('vue');

import HelloWorldComponent from './components/HelloWorldComponent.vue';
import MapComponent from './components/MapComponent.vue';
const app=new Vue({
    el:'#app',
    components:{
        HelloWorldComponent,
        MapComponent 
    }
})