window.Vue=require('vue');

import HelloWorldComponent from './components/HelloWorldComponent.vue';
import MapComponent from './components/MapComponent.vue';
import HeroPickerComponent from './components/HeroPickerComponent.vue';
const app=new Vue({
    el:'#app',
    components:{
        HelloWorldComponent,
        MapComponent,
        HeroPickerComponent
    }
})