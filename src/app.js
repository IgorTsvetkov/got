window.Vue=require('vue');

import axios from "axios";
import {throttle} from "lodash";
Vue.prototype.$_={throttle};
Vue.prototype.$axios=axios;
if(window.yii)
    Vue.prototype.$axios.defaults.headers.common["X-CSRF-TOKEN"] = window.yii.getCsrfToken();
import AuthSocket from "./js/AuthSocket";
Vue.prototype.$socketStorage=new Map();
import {socketPathes,turnStages,estateTypes} from "./js/config";
Vue.prototype.$socketGet=function(game_id,action){
    if(!this.$socketStorage.has(action))
    {
      this.$socketStorage.set(action,new AuthSocket(game_id,socketPathes[action]));
    }
   return this.socket=this.$socketStorage.get(action);
  }
Vue.prototype.$turnStages=turnStages;
Vue.prototype.$estateTypes=estateTypes;
import Response from "./js/Response";
Vue.prototype.$response=new Response();

import MapComponent from './components/MapComponent.vue';
import HeroPickerComponent from './components/HeroPickerComponent.vue';
import HeroPickerWrapper from './components/HeroPickerWrapper.vue';
import FormAjaxWrapper from './components/FormAjaxWrapper.vue';
import ButtonLoad from './components/ButtonLoad.vue';
import GameTable from './components/GameTable.vue';
import PropertyCard from './components/PropertyCard.vue';
import Auction from './components/Auction.vue';
import Dices from './components/Dices.vue';



const app=new Vue({
    el:'#app',
    components:{
        MapComponent,
        HeroPickerComponent,
        HeroPickerWrapper,
        FormAjaxWrapper,
        ButtonLoad,
        GameTable,
        PropertyCard,
        Auction,
        Dices,

    }
})