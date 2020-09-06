<template>
  <div class="bg-dark text-light p-5 m-4 w-100">
    <div>
      <h1 class="h1 lead">{{utility.name}}</h1>
      <h2 class="lead w-100">Коммунальные предприятия</h2>
      <div v-if="utilityStatus" class="d-flex flex-column lead">
        <div
          class="d-flex justify-content-between"
        >
          <div>Количество</div>
          <div>Рента</div>
        </div>
        <div
          class="d-flex justify-content-between"
          :class="{'bg-warning  text-dark':utilityStatus.count==1}"
        >
          <div>Одно</div>
          <div>Бросок кубика x4</div>
        </div>
        <div
          class="d-flex justify-content-between"
          :class="{'bg-warning  text-dark':utilityStatus.count==2}"
        >
          <div>Два</div>
          <div>Бросок кубика x10</div>
        </div>
        <div v-if="!isReadOnly&&isForSale">
          <div class="btn btn-primary w-100" @click="buy">Купить</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    utility: {
      type: Object,
      default: undefined,
    },
    isReadOnly: {
      type: Boolean,
      default: true,
    },
    my_player_id: {
      type: Number,
      required: true,
    },
    game_session_id: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      utilityStatus: undefined,
    };
  },
  computed: {
    isMyUtility() {
      return this.utilityStatus.player_id == my_player_id;
    },
    color() {
      return this.isMyUtility ? "bg-warning" : "bg-danger";
    },
    isForSale() {
      return !this.utilityStatus.player_id;
    },
  },
  async created() {
    this.utilityStatus = await this.getUtilityGameStatus();
  },
  methods: {
    async getUtilityGameStatus() {
      let result = await this.$axios.get(
        `/utility-game-status/view?game_session_id=${this.game_session_id}&utility_id=${this.utility.id}`
      );
      if (!result) throw new Error("no result getUtilityGameStatus");
      return result.data;
    },
    async buy() {
      let result = await this.$axios.post(
        `/utility-game-status/buy?id=${this.utility.id}`
      );
      //if success
      if (result && result.data && result.data.data) {
        this.utilityStatus = await this.getUtilityGameStatus();
        this.$emit("utilityBuy", result);
      } else throw new Error("Can't get getUtilityGameStatus request result");
    },
  },
};
</script>

<style lang="scss" scoped>
</style>