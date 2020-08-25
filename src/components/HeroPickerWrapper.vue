<template>
  <div>
    <div class="d-flex w-100 justify-content-end bg-light">
        <div class="w-100 d-flex justify-content-center align-items-center"><div class="font-weight-bolder lead">Игра № {{gameParsed.id}}</div></div>
        <a class="btn btn-danger" :href="'/match/leave?game_id='+gameParsed.id" @click.prevent="leave">&times;</a>
    </div>
    <div
      class="d-flex flex-row justify-content-center align-items-center flex-wrap text-center lead"
    >
      <div v-for="(player,key) in this.players" :key="key">
        <div v-if="player">
          <hero-picker-component
            :is_king="gameParsed.leader_user_id==player.user_id"
            :username="player.user.username"
            :hero_src="player.hero.src"
            :hero_name="player.hero.name"
            :slot_index="+player.slot"
            :is_current_player="player.user.id==current_user_id"
            :player_id="+player.id"
            @changeSlot="refreshPageEveryone"
            @heroChanged="refreshPageEveryone"
          ></hero-picker-component>
        </div>
        <div v-else>
          <hero-picker-component :slot_index="key" @slotChanged="refreshPageEveryone"></hero-picker-component>
        </div>
      </div>
    </div>
    <div class="d-flex">
    <slot v-if="this.gameParsed.leader_user_id===this.current_user_id"></slot>
    <div class="d-flex justify-content-center align-items-center">
    </div>
    </div> 

    <div v-if="error" class="lead bg-danger text-light border shadow p-20">{{error.message}}</div>
  </div>
</template>

<script>

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
    current_user_id: {
      type: String,
      deault: "",
    },
  },
  data() {
    return {
      error: undefined,
      socket: undefined,
      gameParsed: undefined,
    };
  },
  beforeCreate() {},
  methods: {
    async leave(e){
      let result= await this.$axios.post(e.target.href);
      if(result){
        this.refreshPageEveryone(result.data);
        window.location.pathname="/match";
      }
    },
    refreshPageEveryone(e) {
      if (e&&e.data&&e.data.error) {
        this.error = e.data.error;
        return;
      }
      this.error = null;
      this.socket.send({ action: "refresh" });
    },
  },
  created() {
    this.gameParsed = JSON.parse(this.game);
    this.socket = this.$socketGet(this.gameParsed.id, "send-local-to-all");
    this.socket.addMessageCallback((e,parsedData)=>{
      if(parsedData.action&&parsedData.action==="join"){
        this.refreshPageEveryone(e);
      }
    });
    this.socket.addMessageCallback((e, res) => {
        console.log(res);
        if (res.action == "refresh")
          this.$axios
            .get("/match/connect?json=true")
            .then((res) => {
              console.log("new data after update");
              this.gameParsed = res.data;
              // this.$forceUpdate();
            })
            .catch((res) => console.log("Error REFRESH :>> ", res));
        if (res.data && res.data.users)
          this.users = this.usersTransform(res.data.users);
      });
  },
  computed: {
    // gameParsed: function () {
    //   if (this.updatedGame) return this.updatedGame;
    //   return JSON.parse(this.game);
    // },
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