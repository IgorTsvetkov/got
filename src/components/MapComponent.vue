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
              <div class="bg-dark text-light lead">
                {{player.user.username}} : {{player.money}}
                <text-with-money text="$" />
              </div>
            </div>
            <div v-if="isMyTurn">
              <button
                v-if="canRollDices"
                class="btn btn-light"
                @click="move()"
                @keypress.enter="move()"
              >Бросить кубики</button>
              <button
                v-if="canFinishTurn"
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
            <div
              class="w-100 h-100 d-flex align-items-center justify-content-center"
              v-if="isMyTurn&&myCell&&isFigurineMoved"
            >
              <div v-if="myCell.property">
                <property-card
                  :is_turn_finished="isFinishedTurn"
                  :id="+myCell.property_id"
                  @propertyBuy="onpropertyBuy"
                  @propertyImprove="onpropertyImprove"
                  @propertyPayRent="onpropertyPayRent"
                  :myPlayer="myPlayer"
                  :position="+myCell.position"
                ></property-card>
              </div>
              <div class="w-100 h-100" v-if="myCell.event">
                <event-card
                  class="w-100 h-100"
                  :is_finished_turn="isFinishedTurn"
                  :current_event_id="+game.current_event_id"
                  :event="myCell.event"
                  @eventDone="oneventDone"
                  @turnStatusUpdate="onturnStatusUpdate"
                />
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
import EventCard from "./EventCard.vue";
import TextWithMoney from "./TextWithMoney.vue";

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
    EventCard,
    TextWithMoney,
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
        case "players-and-game":
          updateModel(this.game, data.game);
          updateModelInArrayAll(this.game.players, data.players);
          break;
        case "my-player-and-game":
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
        case "game":
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
    onturnStatusUpdate(result){
      let systemChatMessage = `${this.userNameAndHeroHTML()} нужно бросить кости`;
      this.socket.send(result,systemChatMessage);
    },
    oneventDone(result) {
      if (this.$response.handleGameError(result, this.socket)) return;

      let systemChatMessage = "событие выполнено";
      this.socket.send(result, systemChatMessage);
    },
    onpropertyBuy(result) {
      if (this.$response.handleGameError(result, this.socket)) return;
      console.log('onproperty buy result :>> ', result);
      let property = result.data.data.property;
      let systemChatMessage = `${this.userNameAndHeroHTML()} купил новую собственность ${this.propertyHTML(property)}`;

      this.socket.send(result, systemChatMessage);
    },
    onpropertyImprove(result) {
      if (this.$response.handleGameError(result, this.socket)) return;
      let property = result.data.data.property;

      let systemChatMessage = `${this.userNameAndHeroHTML()} улучшил собственность ${this.propertyHTML(property)}`;

      this.socket.send(result, systemChatMessage);
    },
    onpropertyPayRent(result) {
      if (this.$response.handleGameError(result, this.socket)) return;

      let data = result.data.data;

      let player_to = this.findPlayer(data.player_to_id);

      let systemChatMessage = `${this.userNameAndHeroHTML()} заплатил <span class="text-success">${data.cost}</span> игроку ${this.userNameAndHeroHTML(player_to)}`;
      this.socket.send(result, systemChatMessage);
    },
    async rollDices() {
      let result = await this.$axios.post(
        `/got/roll?player_id=${this.player_id}`
      );
      if (result) {
        if (this.$response.handleGameError(result, this.socket)) return;
        let game=result.data.data.game;
        let systemChatMessage = `${this.userNameAndHeroHTML()} бросил кости и получил ${game.roll_count_first} и ${game.roll_count_second} `;
        this.socket.send(result, systemChatMessage);
      }
    },
    async move(player_id) {
      let result = await this.$axios.post(
        `/got/move?player_id=${this.player_id}`
      );
      if (result) {
        let data = result.data.data;
        let systemChatMessage = `${this.usernameHTML()} переместил ${this.heroHTML()} на ${data.step} ${this.getCellName(data.step)}`;
        this.socket.send(result, systemChatMessage);
      }
    },
    getCellName(step) {
      let name;
      switch (step) {
        case 1:
          name = "клетку";
          break;
        case (2, 3, 4):
          name = "клетки";
          break;
        default:
          name = "клеток";
          break;
      }
      return name;
    },
    async endTurn(player_id) {
      let result = await this.$axios.post(
        `/got/end-turn?player_id=${this.player_id}`
      );
      if (result) {
        let data = result.data.data;
        let nextPlayer = this.findPlayer(data.game.turn_player_id);
        let systemChatMessage = `${this.usernameHTML()} ${this.heroHTML()}
        передал ход ${this.usernameHTML()} ${this.heroHTML()}`;
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
    usernameHTML(player=this.myPlayer){
      return `<span style="color:${player.hero.color}">
                ${player.user.username}
            </span>`;
    },
    heroHTML(player=this.myPlayer){
      return  `<img src="${player.hero.src}" width="35px" class="text-wrap" />`;
    },
    userNameAndHeroHTML(player=this.myPlayer){
      return this.usernameHTML(player)+" "+this.heroHTML(player);
    },
    propertyHTML(property){
      return `<span class="text-capitalize px-1 ${property.color}">${property.name}</span>`;
    }
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
    canRollDices: function () {
      return this.isBeginTurn || this.canRollAgain;
    },
    //turn stages
    canRollAgain: function () {
      return this.game.turn_stage == this.$turnStages["rollAgain"];
    },
    isBeginTurn: function () {
      return this.game.turn_stage == this.$turnStages["begin"];
    },
    isFinishedTurn: function () {
      return this.game.turn_stage == this.$turnStages["finished"];
    },
    canFinishTurn: function () {
      return +this.game.turn_stage >= this.$turnStages["canFinish"];
    },
    isFigurineMoved: function () {
      return +this.game.turn_stage >= this.$turnStages["figurineMoved"];
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