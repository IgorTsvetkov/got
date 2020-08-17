<template>
    <div>
          <div v-for="(item,key) in [...Array(5).keys()]" :key="key">
            {{ key }}
          </div>
          <div v-for="player in gameParsed.players" :key="player.id">     
                <hero-picker-component
                :is_owner="gameParsed.players[0].user_id==player.user_id" 
                :username="player.user.username"
                :hero_src="player.hero.src"
                :hero_name="player.hero.name"
                :index="player.slot"
                @changeSlot="refreshPageEveryone"
                >
                </hero-picker-component>
            </div>
        <!-- <div v-if="error" class="lead bg-warning text-light border shadow  p-20">{{error.message}}</div> -->
    </div>
</template>

<script>
import AuthSocket from "../js/AuthSocket";
import axios from "axios";
import HeroPickerComponent from './HeroPickerComponent.vue';
export default {
  components:{
    HeroPickerComponent
  },
  props: {
    game:{
      type:String,
      default:""
    }
  },
    data() {
    return {
      socket: new AuthSocket("ws://127.0.0.1:8989/send-to-all"),
      six:range(1,6),
    };
  },
  methods: {
    refreshPageEveryone(){
      this.socket.send({refresh:true});
    }
  },
  created() {
    window.x=this.gameParsed;
    //set csrf for all post request
    axios.defaults.headers.common['X-CSRF-TOKEN']=window.yii.getCsrfToken();
    this.socket.onmessageAuth = (e, res) => {
      console.log(res);
      if (res.refresh)
        axios
          .get("/match/connect?layout=false")
          .then((res) => {
            console.log(res);
          })
          .catch((res) => console.log("Error REFRESH :>> ", res));
      if (res.data && res.data.users)
        this.users = this.usersTransform(res.data.users);
    };
  },
  computed: {
    gameParsed:function(){
      return JSON.parse(this.game);
    },
    playerSlot:function(){

    }
  },
};
</script>

<style lang="scss" scoped>

</style>