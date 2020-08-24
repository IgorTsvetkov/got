<template>
  <div>
    <a href="/match/create-lobby" @click.prevent="createLobby">Создать игровое лобби</a>

    <table class="table" v-if="games&&games.length>0">
      <thead>
        <tr>
          <th v-for="item in Object.keys(games[0])" :key="item.id" scope="col">{{item}}</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
          <tr v-for="game in games" :key="game.id">
              <td v-for="(col,index) in Object.values(game)" :key="index">
                  {{col}}
              </td> 
              <td><a href="#" @click.prevent="join(game.id)">Присоединиться</a></td>
          </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      games: undefined,
      socketGlobal:undefined,
    };
  },
  created() {
    this.socketGlobal=this.$socketGet("","send-to-all");
    this.socketGlobal.addMessageCallback((e,parsedData)=>{
        if(parsedData&&parsedData.action==="create-lobby"){
            console.log('parsedData :>> ', parsedData);
            console.log('this.games[0] :>> ', this.games[0]);
        }
            this.games.push(parsedData.data.game);
    });
    this.$axios
      .get("/match?json=true")
      .then((res) => {
        console.log("res.data :>> ", res.data);
        this.games = res.data;
      })
      .catch((err) => {
        console.error(err);
      });
  },
  methods: {
      async join(game_id) {
          let socket=this.$socketGet(game_id,"send-local-to-all");
          let result= await this.$axios.post("/match/join?game_id="+game_id);
              console.log('"hello" :>> ', "hello");
          if(result){
              socket.send(result.data);
              window.location.pathname="/match/connect";
          }
            
      },
      async createLobby(e){
          this.$axios.get(e.target.href)
          .then(res => {
              this.socketGlobal.send(res.data);
            //   window.location.pathname="/match/connect";
          })
          .catch(err => {
              console.error(err); 
          })
      }
  },
};
</script>

<style lang="scss" scoped>
</style>