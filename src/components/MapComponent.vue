<template>
  <div class="d-flex align-items-center justify-content-center">
    <div class="grid position-relative">
      <div class="position-absolute absolute-right-0">
        <match-menu class="z-index-10">
          <leave-match-button :game_id="game.id">Покинуть</leave-match-button>
        </match-menu>
      </div>
      <div class="not-draggable" v-for="cell in cells" :key="cell.position">
        <!-- {{game}} -->
        <cell :playerOwner="playerOwner(cell.position)" :cell="cell">
          <div v-for="(player,key) in game.players" :key="key">
            <div v-if="player.position==cell.position">
              <figurine :hero="player.hero"></figurine>
            </div>
          </div>
        </cell>
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
            <div v-if="isMyTurn">
              <button
                v-if="!Boolean(+game.is_dice_rolled)"
                class="btn btn-light"
                @click="move()"
                @keypress.enter="move()"
              >Бросить кубик</button>
              <button
                v-else
                class="btn btn-light"
                @click="endTurn()"
                @keypress.enter="endTurn()"
              >Закончить ход</button>
            </div>
          </div>
        </div>
      </div>
      <div class="empty-center">
        <div class="w-100 h-100 d-flex bg-warning">
          <div class="w-50 h-inherit d-flex justify-content-center align-items-center">
            <div v-if="isMyTurn">
              <div v-if="myCell&&myCell.property&&game.is_dice_rolled">
                <property-card
                  :is_action_done="Boolean(+this.game.is_action_done)"
                  :id="+myCell.property_id"
                  @propertyBuy="onpropertyBuy"
                  @propertyImprove="onpropertyImprove"
                  @propertyPayRent="onpropertyPayRent"
                  :myPlayer="myPlayer"
                  :position="+myCell.position"
                ></property-card>
              </div>
            </div>
          </div>
          <div class="w-50 bg-primary d-flex flex-column h-100 p-2">
            <chat
              :from="myPlayer.user.username"
              :from_img="myPlayer.hero.src"
              :game_id="+game.id"
              :color="myPlayer.hero.color"
            ></chat>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ImageComponent from "./ImageComponent.vue";
import LeaveMatchButton from "./LeaveMatchButton.vue";
import Cell from "./Cell.vue";
import Figurine from "./Figurine.vue";
import Chat from "./Chat.vue";
import PropertyCard from "./PropertyCard.vue";
import MatchMenu from "./MatchMenu.vue";

import AuthSocket from "../js/AuthSocket";
import { updateModel, updateModelInArrayAll } from "../js/modelHelper";

export default {
  components: {
    ImageComponent,
    Chat,
    Cell,
    MatchMenu,
    PropertyCard,
    Figurine,
    LeaveMatchButton,
  },
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
    this.myPlayer = this.game.players.find((p) => p.id == this.player_id);
    let result = await this.$axios.get("/cell?game_id=" + this.game.id);
    if (result) this.cells = result.data;
    this.socket = this.$socketGet(this.game.id, "send-local-to-all");
    this.socket.addMessageCallback((e, parsedData) => {
      let action = this.$response.getAction(parsedData);
      if (!action) return;
      let data = parsedData.data.data;
      let player;
      switch (action) {
        case "property-pay-rent":
          updateModel(this.game, data.game);
          updateModelInArrayAll(this.game.players, data.players);
          break;
        case "move":
        case "property-improve":
          player = this.findPlayer(data.player.id);
          updateModel(player, data.player);
          updateModel(this.game, data.game);
          break;
        case "property-bought":
          player = this.findPlayer(data.player.id);
          updateModel(player, data.player);
          player.propertyCells.push({ position: player.position });
          updateModel(this.game, data.game);
          break;
        case "end-turn":
          updateModel(this.game, data.game);
          break;
        default:
          throw new Error("can't handle this socket action");
          break;
      }
      // this.$forceUpdate();
    });
  },
  methods: {
    onpropertyBuy(result) {
      if (this.$response.handleGameError(result, this.socket)) return;
      let property = result.data.data.property;
      console.log("property :>> ", property);
      let systemChatMessage = `
      <span style="color:${this.myPlayer.hero.color}">${this.myPlayer.user.username}</span>
      <img src="${this.myPlayer.hero.src}" width="35px" class="text-wrap" /> 
      купил новую собственность 
      <span class="text-capitalize px-1 ${property.color}">${property.name}</span>`;

      this.socket.send(result, systemChatMessage);
    },
    onpropertyImprove(result) {
      if (this.$response.handleGameError(result, this.socket)) return;
      let property = result.data.data.property;

      let systemChatMessage = `
      <span style="color:${this.myPlayer.hero.color}">${this.myPlayer.user.username}</span>
      <img src="${this.myPlayer.hero.src}" width="35px" class="text-wrap" />
      улучшил собственность
      <span class="text-capitalize ${property.color}">${property.name}</span>
      `;

      this.socket.send(result, systemChatMessage);
    },
    onpropertyPayRent(result) {
      if (this.$response.handleGameError(result, this.socket)) return;
      let data = result.data.data;

      let player_to = this.findPlayer(data.player_to_id);

      let systemChatMessage = `<span style="color:${this.myPlayer.hero.color}">${this.myPlayer.user.username}</span>
      <img src="${this.myPlayer.hero.src}" width="35px" class="text-wrap" /> заплатил <span class="text-success">${data.cost}</span>
      игроку
      <span style="color:${player_to.hero.color}">${player_to.user.username}</span>
      <img src="${player_to.hero.src}" width="35px" class="text-wrap" /> 
      `;
      this.socket.send(result, systemChatMessage);
    },
    async move(player_id) {
      let result = await this.$axios.post(
        `/got/move?player_id=${this.player_id}`
      );
      if (result) {
        let data = result.data.data;
        let systemChatMessage = `<span style="color:${
          this.myPlayer.hero.color
        }">${this.myPlayer.user.username}</span>        
         переместил <img src="${
           this.myPlayer.hero.src
         }" width="35px" class="text-wrap" /> на ${this.getStep(data.step)}`;
        this.socket.send(result, systemChatMessage);
      }
    },
    getStep(step) {
      let name;
      switch (step) {
        case 1:
          name = "клетку";
          break;
        case (2, 3, 4):
          name = "клетки";
          break;
        default:
          "клеток";
          break;
      }
      return step + " " + name;
    },
    async endTurn(player_id) {
      let result = await this.$axios.post(
        `/got/end-turn?player_id=${this.player_id}`
      );
      if (result) {
        let data = result.data.data;
        let nextPlayer = this.findPlayer(data.game.turn_player_id);
        // debugger
        let systemChatMessage = `
        <span style="color:${this.myPlayer.hero.color}">${this.myPlayer.user.username}</span>
        <img src="${this.myPlayer.hero.src}" width="35px" class="text-wrap" /> 
        передал ход
        <span style="color:${nextPlayer.hero.color}">${nextPlayer.user.username}</span>
         <img src="${nextPlayer.hero.src}" width="35px" class="text-wrap" /> 
        `;
        this.socket.send(result, systemChatMessage);
      }
    },
    findPlayer(id) {
      return this.game.players.find((p) => p.id == id);
    },
    playerOwner(position) {
      return this.game.players.find(
        (p) =>
          p.propertyCells &&
          p.propertyCells.find((cell) => cell.position == position)
      );
    },
  },
  computed: {
    myCell: function () {
      if (this.cells.length > 0) {
        console.log("this.myPlayer :>> ", this.myPlayer);
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
.absolute-right-0 {
  right: 0px;
}
</style>