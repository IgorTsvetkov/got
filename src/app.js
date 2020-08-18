window.Vue=require('vue');

import AuthSocket from "./js/AuthSocket";

Vue.prototype.$socketStorage=new Map();
Vue.prototype.$socketGet=function(action){
    if(!this.$socketStorage.has(action))
      this.$socketStorage.set(action,new AuthSocket("ws://127.0.0.1:8989/"+action));
   return this.socket=this.$socketStorage.get(action);
  }
import HelloWorldComponent from './components/HelloWorldComponent.vue';
import MapComponent from './components/MapComponent.vue';
import HeroPickerComponent from './components/HeroPickerComponent.vue';
import HeroPickerWrapper from './components/HeroPickerWrapper.vue';
import StartGameButton from './components/StartGameButton.vue';
import Test from './components/Test.vue';

const app=new Vue({
    el:'#app',
    components:{
        HelloWorldComponent,
        MapComponent,
        HeroPickerComponent,
        HeroPickerWrapper,
        Test,
        StartGameButton
    }
})