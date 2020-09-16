<template>
  <div class="d-flex align-items-center justify-content-center">
    <div class="grid position-relative">
      <div class="position-absolute absolute-right-0">
        <match-menu class="z-index-10">
          <leave-match-button :game_id="game.id">Покинуть</leave-match-button>
        </match-menu>
      </div>
      <div class="not-draggable" v-for="cell in cells" :key="cell.position">
        <cell
          :playerOwner="playerOwner(cell.id)"
          :cell="cell"
          :playerMoved="findPlayer(game.turn_player_id)"
          :startPositionArrow="+startPositionArrow"
          :endPositionArrow="+endPositionArrow"
        >
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
            <div class="bg-dark" v-for="player in game.players" :key="player.id">
              <div class="text-light lead" :class="{'bg-primary':player.id==game.turn_player_id}">
                {{player.user.username}} : {{player.money}}
                <text-with-money text="$" />
              </div>
            </div>
            <div v-if="isMyTurn">
              <div class="bg-success">ВАШ ХОД</div>
              <button
                v-if="canFinishTurn"
                class="btn btn-danger w-100"
                @click="endTurn()"
              >Закончить ход</button>

              <!-- <button v-if="canRollDices" class="btn btn-light" @click="rollDices()">Бросить кубики</button> -->
              <div @click="rollDicesIfCan()">
                <dices
                  :first="+game.roll_count_first"
                  :second="+game.roll_count_second"
                  :activate="isRolled"
                  @rollFinish="onrollFinish"
                  :readonly="!canRollDices"
                />
              </div>
            </div>
            <div v-else>
              <div class="bg-danger"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="empty-center">
        <div class="w-100 h-100 d-flex bg-warning">
          <div class="w-50 h-inherit d-flex justify-content-center align-items-center">
            <div
              class="w-100 h-100 d-flex align-items-center justify-content-center"
              v-if="isMyTurn&&myCell&&!isStartMoveTurn&&!auction"
            >
              <property-card
                v-if="myCell.property"
                :is_readonly="isReadOnly"
                :id="+myCell.property_id"
                @propertyBuy="onpropertyBuy"
                @propertyImprove="onpropertyImprove"
                @payRent="onpayRent"
                @auctionStarted="onauctionStarted"
                :myPlayer="myPlayer"
                :position="+myCell.position"
              ></property-card>
              <event-card
                v-if="myCell.event"
                class="w-100 h-100"
                :is_readonly="isReadOnly"
                :current_event_id="+game.current_event_id"
                :is_dice_rolled="isRollAgainFinish"
                :event="myCell.event"
                @eventDone="oneventDone"
                @turnStatusUpdate="onturnStatusUpdate"
              />
              <tax
                v-if="myCell.tax"
                :tax="myCell.tax"
                :is_readonly="isReadOnly"
                :my_player_id="+myPlayer.id"
                :game_session_id="+game.id"
                @taxBuy="ontaxBuy"
                @payRent="onpayRent"
              />
              <utility
                v-if="myCell.utility"
                :utility="myCell.utility"
                :is_readonly="isReadOnly"
                :my_player_id="+myPlayer.id"
                :game_session_id="+game.id"
                :is_dice_rolled="isRollAgainFinish"
                @utilityBuy="onutilityBuy"
                @payRent="onpayRent"
              />
            </div>
            <div
              v-if="auction"
              class="w-100 h-100 d-flex align-items-center justify-content-center"
            >
              <!-- {{auction.turn_player_id}}
              {{myPlayer.id}}-->

              <auction
                v-if="!isStartMoveTurn"
                :max="+myPlayer.money"
                :min="+auction.cost"
                :estate_type_id="+auction.estate_type_id"
                :estate_id="+auction.estate_id"
                :estate_name="auction.estate_name"
                :canBet="auction.turn_player_id==myPlayer.id"
                :player_id="+myPlayer.id"
                :max_bet_player_id="+auction.max_bet_player_id"
                :is_finished="Boolean(auction.is_finished)"
                @finish="onauctionFinished"
                @leaveAuction="onleaveAuction"
                @bet="onbetAuction"
              />
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
import TextWithMoney from "./TextWithMoney.vue";
import EventCard from "./EventCard.vue";
import Tax from "./Tax.vue";
import Utility from "./Utility.vue";
import Auction from "./Auction.vue";
import Dices from "./Dices.vue";

import AuthSocket from "../js/AuthSocket";
import { updateModel, updateModelInArrayAll } from "../js/modelHelper";
import { estateTypes } from "../js/config";
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
    Tax,
    Utility,
    Auction,
    Dices,
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
      auction: undefined,
      isRolled: false,
      startPositionArrow: undefined,
      endPositionArrow: undefined,
    };
  },
  async beforeMount() {
    this.game = JSON.parse(this.gameString);
    this.myPlayer = this.game.players.find((p) => p.id == this.player_id);
    this.auction = this.game.auction;
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
        case "auction-start":
          this.auction = data.auction;
          updateModelInArrayAll(this.game.players, data.players);
          break;
        case "auction":
          if (data.auction) updateModel(this.auction, data.auction);
          if (data.game) updateModel(this.game, data.game);
          if (data.players)
            updateModelInArrayAll(this.game.players, data.players);
          console.log("auction :>> ", this.auction);
          break;
        case "my-player-and-game":
          player = this.findPlayer(data.player.id);
          updateModel(player, data.player);
          updateModel(this.game, data.game);
          break;
        case "estate-bought":
          this.auction = null;
          player = this.findPlayer(data.player.id);
          updateModel(player, data.player);
          player.estates.push(data.estate);
          updateModel(this.game, data.game);
          break;
        case "auction-leave":
          this.auction = false;
        case "game":
          updateModel(this.game, data.game);
          break;
        case "roll":
          this.isRolled = true;
          updateModel(this.game, data.game);
          break;
        case "arrow":
          this.startPositionArrow=data.startPositionArrow;
          this.endPositionArrow=data.endPositionArrow;
          break;
        default:
          throw new Error("can't handle this socket action");
          break;
      }
      // this.$forceUpdate();
    });
  },
  methods: {
    onbetAuction(result) {
      let data = result.data.data;
      let systemChatMessage = `${this.userNameAndHeroHTML()} поставил ставку в размере ${
        data.auction.cost
      }`;
      this.socket.send(result, systemChatMessage);
    },
    onleaveAuction(result) {
      let data = result.data.data;
      let systemChatMessage;
      if (data.chatHelp)
        systemChatMessage = `${this.userNameAndHeroHTML()} ${
          data.chatHelp.message
        }`;
      this.socket.send(result, systemChatMessage);
    },
    onauctionFinished(result) {
      let systemChatMessage = `${this.userNameAndHeroHTML()} приобрел новую собственность. Ожидание завершения хода`;
      this.socket.send(result, systemChatMessage);
    },
    onauctionStarted(result) {
      let systemChatMessage;
      if (result.data.data.auction)
        systemChatMessage = `${this.userNameAndHeroHTML()} не изъявил желания приобрести ${
          result.data.data.auction.estate_name
        }. Начало аукциона`;
      else
        systemChatMessage = `${this.userNameAndHeroHTML()} Остался совсем один одинёшенек во всём королевстве, но всё равно пытается провести аукцион. Что это, жест отчаяния или попытка закончить забагованную игру?`;
      this.socket.send(result, systemChatMessage);
    },
    onutilityBuy(result) {
      let estate_type_id = result.data.data.chatHelp.estate_type_id;
      let estate_id = result.data.data.chatHelp.estate_id;
      let utility = this.findEstate("utility", estate_id);
      if (!utility) throw new Error("utility have not found");
      let systemChatMessage = `${this.userNameAndHeroHTML()} купил коммунальное предприятие ${
        utility.name
      }`;
      this.socket.send(result, systemChatMessage);
    },
    onpropertyBuy(result) {
      if (this.$response.handleGameError(result, this.socket)) return;
      console.log("onproperty buy result :>> ", result);

      let estate_id = result.data.data.chatHelp.estate_id;
      let property = this.findEstate("property", estate_id);
      let systemChatMessage = `${this.userNameAndHeroHTML()} купил новую собственность ${this.propertyHTML(
        property
      )}`;

      this.socket.send(result, systemChatMessage);
    },
    ontaxBuy(result) {
      let estate_id = result.data.data.chatHelp.estate_id;
      let tax = this.findEstate("tax", estate_id);
      if (!tax) throw new Error("Tax have not found");
      let systemChatMessage = `${this.userNameAndHeroHTML()} приобрел house ${
        tax.name
      }`;
      this.socket.send(result, systemChatMessage);
    },
    onpropertyImprove(result) {
      if (this.$response.handleGameError(result, this.socket)) return;
      let property = result.data.data.property;

      let systemChatMessage = `${this.userNameAndHeroHTML()} улучшил собственность ${this.propertyHTML(
        property
      )}`;

      this.socket.send(result, systemChatMessage);
    },
    onpayRent(result) {
      if (this.$response.handleGameError(result, this.socket)) return;

      let data = result.data.data;

      let player_to = this.findPlayer(data.player_to_id);

      let systemChatMessage = `${this.userNameAndHeroHTML()} заплатил <span class="text-success">${
        data.cost
      }</span> игроку ${this.userNameAndHeroHTML(player_to)}`;
      this.socket.send(result, systemChatMessage);
    },
    onturnStatusUpdate(result) {
      let systemChatMessage = `${this.userNameAndHeroHTML()} нужно бросить кости`;
      this.socket.send(result, systemChatMessage);
    },
    oneventDone(result) {
      if (this.$response.handleGameError(result, this.socket)) return;

      let systemChatMessage = "событие выполнено";
      this.socket.send(result, systemChatMessage);
    },
    rollDicesIfCan() {
      if (this.canRollDices) this.$_.throttle(this.rollDices, 3000)();
    },
    async rollDices() {
      let result = await this.$axios.post(`/got/roll-dices`);
      this.socket.send(result);
    },
    async onrollFinish() {
      this.isRolled = false;

      if (this.$turnStages["startMove"] == this.game.turn_stage) {
        let result = this.$response.setAction("arrow");
        let turn_player = this.findPlayer(this.game.turn_player_id);
        let startPositionArrow = (+turn_player.position);
        let endPositionArrow =
          (+turn_player.position) +
          (+this.game.roll_count_first) +
          (+this.game.roll_count_second);
        let data = { startPositionArrow, endPositionArrow };
        result=this.$response.setData(result,data);
        this.socket.send(result);
      }
      let result = await this.$axios.post(`/got/roll-dices-finish`);
      if (result) {
        if (this.$response.handleGameError(result, this.socket)) return;
        let game = result.data.data.game;
        let systemChatMessage = `${this.userNameAndHeroHTML()} бросил кости и получил 
        ${game.roll_count_first} и ${game.roll_count_second} `;
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
        передал ход ${this.usernameHTML(nextPlayer)} ${this.heroHTML(
          nextPlayer
        )}`;
        this.socket.send(result, systemChatMessage);
      }
    },
    findPlayer(id) {
      return this.game.players.find((p) => p.id == id);
    },
    findEstate(type, id) {
      let cell = this.cells.find((t) => t[type] && t[type].id == id);
      if (!cell) return false;
      return cell[type];
    },
    playerOwner(cell_id) {
      return this.game.players.find(
        (p) =>
          p.estates && p.estates.find((estate) => estate.cell_id == cell_id)
      );
    },
    usernameHTML(player = this.myPlayer) {
      return `<span style="color:${player.hero.color}">
                ${player.user.username}
            </span>`;
    },
    heroHTML(player = this.myPlayer) {
      return `<img src="${player.hero.src}" width="35px" class="text-wrap" />`;
    },
    userNameAndHeroHTML(player = this.myPlayer) {
      return this.usernameHTML(player) + " " + this.heroHTML(player);
    },
    propertyHTML(property) {
      return `<span class="text-capitalize px-1 ${property.color}">${property.name}</span>`;
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
    canRollDices: function () {
      return this.isStartMoveTurn || this.isRollAgain;
    },
    canFinishTurn: function () {
      return this.isFinishedTurn || this.isActionCanSkipTurn;
    },
    //turn stages
    isRollAgain: function () {
      return this.game.turn_stage == this.$turnStages["rollAgain"];
    },
    isRollAgainFinish: function () {
      return this.game.turn_stage == this.$turnStages["rollAgainFinish"];
    },
    isStartMoveTurn: function () {
      return this.game.turn_stage == this.$turnStages["startMove"];
    },
    isFinishedTurn: function () {
      return this.game.turn_stage == this.$turnStages["finished"];
    },
    isReadOnly: function () {
      return this.isFinishedTurn;
    },
    isActionUnSkipTurn: function () {
      return +this.game.turn_stage == this.$turnStages["actionUnskip"];
    },
    isActionCanSkipTurn: function () {
      return +this.game.turn_stage == this.$turnStages["actionCanSkip"];
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