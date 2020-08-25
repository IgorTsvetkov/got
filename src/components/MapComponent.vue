<template>
  <div class="d-flex align-items-center justify-content-center">
    <div class="grid">
      <div v-for="(cell,index) in cells" :key="index">
        <!-- {{game}} -->
        <cell :cell="cell" :players="game.players"></cell>
      </div>
      <div class="cell-center position-relative">
        <img src="/web/images/center.jpg" alt />
        <div
          class="d-flex justify-content-center align-items-center w-100 h-100 position-absolute top-0"
        >
          <div>
            <div v-for="player in game.players" :key="player.id">
              <div class="bg-dark text-light lead">{{player.user.username}} : {{ player.money }}$</div>
            </div>
            <button v-if="isMyTurn" class="btn btn-light" @click="move()">Бросить кубик</button>
          </div>
        </div>
      </div>
      <div class="empty-center">
        <div class="w-100 h-100 d-flex bg-warning">
          <div class="w-50 h-inherit d-flex justify-content-center align-items-center">
            <div v-if="isMyTurn">
              <div v-if="myCell&&myCell.property">
                <property-card :id="+myCell.property_id" @propertyChange="onpropertyChange"></property-card>
              </div>
            </div>

            <a :href="'/match/leave?game_id='+game.id">Покинуть игру</a>
          </div>
          <div class="w-50 bg-primary d-flex flex-column h-100 p-2">
            <chat :from="myPlayer.user.username" :from_img="myPlayer.hero.src" :game_id="+game.id"></chat>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ImageComponent from "./ImageComponent.vue";
// import CellInfo from "./CellInfo.vue";
import Cell from "./Cell.vue";
import Chat from "./Chat.vue";
import PropertyCard from "./PropertyCard.vue";

import AuthSocket from "../js/AuthSocket";

export default {
  components: { ImageComponent, Chat, Cell, PropertyCard },
  props: {
    gameString: {
      type: String,
      default: "",
    },
    player_id: {
      type: String,
      default: undefined,
    },
  },
  data() {
    return {
      position: 0,
      game: undefined,
      cells: [],
      socket: undefined,
    };
  },
  async beforeMount() {
    //set csrf for all post request

    this.game = JSON.parse(this.gameString);
    this.myPlayer=this.game.players.find((p) => p.id == this.player_id);

    let result = await this.$axios.get("/cell");
    if (result) this.cells = result.data;
    this.socket = this.$socketGet(this.game.id, "send-local-to-all");
    this.socket.addMessageCallback((e, parsedData) => {
      if (parsedData.action && parsedData.action == "move") {
        let player = this.findPlayer(parsedData.data.player_id);
        this.game.turn_player_id = parsedData.data.turn_player_id;
        console.log('player.position :>> ', player.position);
        player.position = parsedData.data.position;
        this.$forceUpdate();
      }
      if (parsedData.action && parsedData.action == "nextTurn") {
        let player = this.findPlayer(parsedData.data.player_id);
        this.game.turn_player_id = parsedData.data.turn_player_id;
        this.$forceUpdate();
      }
      if (parsedData.action && parsedData.action == "property-change") {
        let player = this.findPlayer(parsedData.data.player_id);
        player.money = parsedData.data.money;
        this.$forceUpdate();
      }
    });
  },
  created() {},
  methods: {
    onpropertyChange(e) {
      let data = e.data;
      data.action = "property-change";
      console.log("property data :>> ", data);
      if(data.data.success){
        let systemChatMessage=`${this.myPlayer.user.username} купил новую собвсвенность`;
        this.socket.send(data,systemChatMessage);
      }
    },
    move(player_id) {
      this.$axios.post(`/got/move?player_id=${this.player_id}`).then((res) => {
        let data=res.data.data;
        let systemChatMessage=`Игрок ${this.myPlayer.user.username} переместил фигурку на ${data.step}`;
        this.socket.send(res.data,systemChatMessage);
      });
    },
    findPlayer(id) {
      return this.game.players.find((p) => (p.id = id));
    },
  },
  computed: {
    myCell: function () {
      if (this.cells.length > 0) {
        console.log('this.myPlayer :>> ', this.myPlayer);
        let cell = this.cells.find((x) => x.position == this.myPlayer.position);
        return cell;
      }
      return undefined;
    },
    isMyTurn: function () {
      return this.player_id == this.game.turn_player_id;
    },
  },
};
</script>

<style scoped>
body {
  background: black;
}
.grid {
  display: grid;
  grid-template-columns: 8.4vw repeat(14, 5.2vw) 8.4vw;
  grid-template-rows: auto;
  grid-gap: 0.5vw;
  width: 98vw;
  background: black;
}
.cell-center {
  grid-row: 2/6;
  grid-column: 2/7;
  height: auto;
  width: 100%;
}
.empty-center {
  grid-row: 2/6;
  grid-column: 7/16;
}
.col-1 {
  grid-column: 1/2;
}
.row-2 {
  grid-row: 2/3;
}
.row-3 {
  grid-row: 3/4;
}
.row-4 {
  grid-row: 4/5;
}
.row-5 {
  grid-row: 5/6;
}
.col-16 {
  grid-column: 16/17;
}
.empty-center {
  width: 100%;
}
.cell-center img {
  width: inherit;
}
</style>