<template>
  <div>
    <div
      class="d-flex flex-row justify-content-center align-items-center flex-wrap text-center lead"
    >
      <div v-for="(player,key) in this.players" :key="key">
        <div v-if="player">
          <hero-picker-component
            :is_king="gameParsed.players[0].id==player.id"
            :username="player.user.username"
            :hero_src="player.hero.src"
            :hero_name="player.hero.name"
            :slot_index="+player.slot"
            :is_current_player="player.user.id==current_user_id"
            @changeSlot="refreshPageEveryone"
          ></hero-picker-component>
        </div>
        <div v-else>
          <hero-picker-component :slot_index="key" @changeSlot="refreshPageEveryone"></hero-picker-component>
        </div>
      </div>
    </div>
    <slot></slot>
    <div v-if="error" class="lead bg-danger text-light border shadow p-20">{{error.message}}</div>
  </div>
</template>

<script>
import AuthSocket from "../js/AuthSocket";
import axios from "axios";
import HeroPickerComponent from "./HeroPickerComponent.vue";
export default {
  components: {
    HeroPickerComponent,
  },
  props: {
    game: {
      type: String,
      default: "",
    },
    current_user_id:{
      type:String,
      deault:""
    }
  },
  data() {
    return {
      socket: new AuthSocket("ws://127.0.0.1:8989/send-to-all"),
      updatedGame: undefined,
      error: undefined,
    };
  },
  methods: {
    refreshPageEveryone(e) {
      if (e.data.error) {
        this.error = e.data.error;
        return;
      }
      this.error = null;
      this.socket.send({ refresh: true });
    },
  },
  created() {
    //set csrf for all post request
    axios.defaults.headers.common["X-CSRF-TOKEN"] = window.yii.getCsrfToken();
    this.socket.onmessageAuth = (e, res) => {
      console.log(res);
      if (res.refresh)
        axios
          .get("/match/connect?json=true")
          .then((res) => {
            console.log("new data after update");
            this.updatedGame = res.data;
            // this.$forceUpdate();
          })
          .catch((res) => console.log("Error REFRESH :>> ", res));
      if (res.data && res.data.users)
        this.users = this.usersTransform(res.data.users);
    };
  },
  computed: {
    gameParsed: function () {
      if (this.updatedGame) return this.updatedGame;
      return JSON.parse(this.game);
    },
    players: function () {
      const maxPlayers = 6;
      let sorted = this.gameParsed.players.sort((a, b) => a.sort - b.sort);
      let arr = [];
      arr[maxPlayers - 1] = undefined;
      sorted.forEach((player) => (arr[+player.slot] = player));
      return arr;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>