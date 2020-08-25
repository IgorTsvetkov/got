<template>
  <div class="bg-warning p-1 rounded shadow m-1">
    {{hero_name}}
    <div class="hero-img m-1 position-relative">
      <img class="hover-img" width="100px" :src="hero_src" @click="toggleHeroes" />
      <div
        class="img-picker position-absolute z-index-100 shadow-lg bg-dark text-light"
        v-if="is_current_player&&heroesPicker"
      >
        <div class="d-flex align-content-center justify-content-center bg-primary text-light">
          <h4 class="w-100 lead m-0 line-height-2">Выбор персонажа</h4>
          <button class="btn btn-danger sticky-top rounded-0" @click="toggleHeroes">&times;</button>
        </div>
        <div class="overflow-auto d-flex p-1">
          <div v-for="(hero) in heroes" :key="hero.id">
            <img :class="{'hover-img':is_current_player}" class="reverse" width="100px" :src="hero.src" @click="changeHero(hero.id)" />
          </div>
        </div>
      </div>
    </div>
    <div @click="changeSlot(slot_index)">
      <h4 :class="{'bg-dark':is_current_player,'bg-secondary':!is_current_player}" class="h4 p-1 text-light lead shadow" v-if="username">
        {{username}}<span v-if="is_current_player"> [вы]</span>
        <img v-if="is_king" src="/web/images/crown.svg" width="25px" />
      </h4>
      <h4 class="btn btn-secondary w-100 h4 p-1 lead shadow" v-else>Сесть</h4>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    is_king: {
      type: Boolean,
      default: false,
    },
    username: {
      type: String,
      default: "",
    },
    hero_src: {
      type: String,
      default: "/web/images/figurines/figure0.png",
    },
    hero_name: {
      type: String,
      default: "Faceless men",
    },
    slot_index: {
      type: Number,
      default: null,
    },
    is_current_player: {
      type: Boolean,
      default: false,
    },
    player_id:{
      type:Number,
      default:undefined
    }
  },
  data() {
    return {
      heroesPicker: false,
      heroes: [],
    };
  },
  methods: {
    async changeHero(hero_id){
      let result = await this.$axios.post(`/player/update-hero?player_id=${this.player_id}&hero_id=${hero_id}`);
      console.log(result.data);
      console.log('result.data change hero :>> ', result.data);
      this.$emit("heroChanged", result);
    },
    async changeSlot(value) {
      let result = await this.$axios.post(`/match/change-slot?slot=${value}`);
      this.$emit("slotChanged", result);
    },
    toggleHeroes() {
      if (this.is_current_player) {
        this.heroesPicker = !this.heroesPicker;
      }
    },

  },
  created() {
    
    this.$axios.get("/hero").then((res) => {
      //  console.log(res.data)
      this.heroes = res.data;
    });
  },
  computed: {
    currentPlayer() {
      return this.data 
    }
  },
};
</script>

<style scoped>
.z-index-100 {
  z-index: 100;
}
img.hover-img:hover,
img.hover-img.reverse {
  filter: grayscale(0.8);
  cursor: pointer;
}
img.hover-img.reverse:hover {
  filter: none;
  cursor: pointer;
}
.img-picker {
  width: 500px;
}
.line-height-2 {
  line-height: 2;
}
</style>