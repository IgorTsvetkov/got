window.Vue=require('vue');

import AuthSocket from "./js/AuthSocket";

Vue.prototype.$socketStorage=new Map();
Vue.prototype.$socketGet=function(game_id,action){
    if(!this.$socketStorage.has(action))
      this.$socketStorage.set(action,new AuthSocket(game_id,"ws://127.0.0.1:8989/"+action));
   return this.socket=this.$socketStorage.get(action);
  }
import HelloWorldComponent from './components/HelloWorldComponent.vue';
import MapComponent from './components/MapComponent.vue';
import HeroPickerComponent from './components/HeroPickerComponent.vue';
import HeroPickerWrapper from './components/HeroPickerWrapper.vue';
import StartGameButton from './components/StartGameButton.vue';
import FormAjax from './components/FormAjax.vue';
import PropertyCard from './components/PropertyCard.vue';

const app=new Vue({
    el:'#app',
    components:{
        HelloWorldComponent,
        MapComponent,
        HeroPickerComponent,
        HeroPickerWrapper,
        StartGameButton,
        PropertyCard,
        FormAjax
    }
})