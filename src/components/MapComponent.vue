<template>
  <div class="f f-center">
    <div class="grid">
      <div class="relative f" v-for="(cell,index) in cells" :key="index">
        <!-- {{gameParsed}} -->
        <div class="absolute">
          <div v-for="(player,key) in gameParsed.players" :key="key">
            <div v-if="cell.position==player.position">
              <div class="d-flex figurine">
                <img height="100%" :src="player.hero.src" />
                <div class="bg-danger">position:{{player.position}}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="f f-center-horizontal">
          <ImageComponent
            v-if="cell.property"
            :src="getImage(cell)"
            :price="cell.property.cost"
            :price_bgcolor="cell.property.group.color_name"
          ></ImageComponent>
          <ImageComponent v-else :src="getImage(cell)"></ImageComponent>
        </div>
      </div>
      <div class="cell-center">
        <img src="/web/images/center.jpg" alt />
      </div>
      <!-- <img class="empty-center" /> -->
      <div class="empty-center">
        <div class="w-100 h-100 d-flex bg-warning">
          <div class="w-50 h-100 d-flex justify-content-center align-items-center">
            <div v-if="this.player_id==this.gameParsed.turn_player_id">
              <button class="btn btn-primary" @click="move()">Бросить кубик</button>
            </div>
          </div>
          <div class="w-50 h-100 bg-primary">
            <div class="bg-secondary w-100 h-75">
                <div v-for="message in messages" :key="message.id">
                    {{ message }}
                </div>
            </div>
            <div class="container-fluid">
              <div class="row">
                <input class="col-9" v-model="message" type="text" />
                <input class="col-3 m-0 btn btn-success"  value="Добавить" type="button" @click="sendMessage"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ImageComponent from "./ImageComponent.vue";
import AuthSocket from "../js/AuthSocket";
import axios from "axios";
export default {
  components: { ImageComponent },
  props: {
    game: {
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
      cells: [],
      position: 0,
      socket: new AuthSocket("ws://127.0.0.1:8989/send-to-all"),
      gameParsed: undefined,
      message:"",
      messages:[],
    };
  },
  beforeMount() {
    this.gameParsed = JSON.parse(this.game);
  },
  created() {
    //set csrf for all post request
    axios.defaults.headers.common["X-CSRF-TOKEN"] = window.yii.getCsrfToken();

    axios
      .get("/cells", {
        params: {
          expand: "property.group,tax,utility,event",
        },
      })
      .then(({ data }) => {
        this.cells = data;
      })
      .catch((err) => {
        console.error(err);
      });
    this.socket.onmessageAuth = (e, parsedData) => {
      if (parsedData.action && parsedData.action == "move") {
        let player = this.gameParsed.players.find(
          (el) => el.id == parsedData.data.player_id
        );
        player.position = parsedData.data.position;
        console.log(
          "parsedData.data.turn_player_id :>> ",
          parsedData.data.turn_player_id
        );
        this.gameParsed.turn_player_id = parsedData.data.turn_player_id;
        this.$forceUpdate();
      }
      if(parsedData.action && parsedData.action == "chat"){
          this.messages.push(parsedData.message);
      }
    };
  },
  methods: {
    move($player_id) {
      axios.post(`/got/move?player_id=${this.player_id}`).then((res) => {
        this.socket.send({
          action: "move",
          data: {
            position: res.data.position,
            player_id: this.player_id,
            turn_player_id: res.data.turn_player_id,
          },
        });
      });
    },
    sendMessage(){
        this.socket.send({action:"chat",message:this.message});
    },
    getImage(cell) {
      let x = cell.property
        ? cell.property.src
        : cell.tax
        ? cell.tax.src
        : cell.event
        ? cell.event.src
        : cell.utility
        ? cell.utility.src
        : "";
      return x;
    },
    findPlayer(id) {
      return this.gameParsed.players.find((p) => (p.id = id));
    },
  },
  computed: {
    // turn_player_id: function () {
    //     return this.gameParsed.turn_player_id;
    // },
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
  height: 100%;
}
.cell-center img {
  width: inherit;
}
/*FIGURINE STYLES*/
.absolute {
  position: absolute;
}
.relative {
  position: relative;
}
.f {
  display: flex;
}
.f-center {
  justify-items: center;
  justify-content: center;
  align-items: center;
}
.f-center-horizontal {
  align-items: center;
}
.figurine {
  height: 40px;
  width: 40px;
  display: flex;
  width: inherit;
  flex-wrap: wrap;
  z-index: 1;
}
</style>