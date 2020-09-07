<template>
  <div class="bg-secondary text-dark-50 shadow rounded p-3 m-3 w-75">
    <div v-if="is_finished">
      Поздравляю. Вы выйграли аукцион. Итоговая цена {{current}}
      <div class="btn btn-success" @click="finish">Заплатить</div>
    </div>
    <div v-else-if="min<max" class="form-group">
      <label
        for="formControlRange"
        class="w-100 text-center lead text-capitalize"
      >Аукцион {{target_name}}</label>
      <div class="lead">Цена: {{min}}</div>
      <div class="lead">Величина ставки: {{current}}</div>
      <div v-if="canBet">
        <div v-if="!max_bet_player">
          <div class="btn btn-success" @click="makeBet">Поставить начальную ставку</div>
        </div>
        <div v-else class="shadow p-2 shadow bg-dark text-light rounded">
          <div class>Повысить на:</div>
          <div class="d-flex justify-content-around">
            <div class="btn btn-success" @click="betPercent(0.10)">{{takePartCost(0.10)}}</div>
            <div class="btn btn-success" @click="betPercent(0.25)">{{takePartCost(0.25)}}</div>
            <div class="btn btn-success" @click="betPercent(0.50)">{{takePartCost(0.50)}}</div>
            <div class="btn btn-success" @click="betPercent(0.7)">{{takePartCost(0.7)}}</div>
          </div>
          <div>
            <div
              class="btn btn-dark mt-2 width-100 d-flex justify-content-center"
              @click="toggleExpend"
            >
              <img
                src="/web/images/arrow_down.svg"
                :class="{'rotate-180':expend}"
                width="20vw"
                height="15vw"
              />
            </div>
            <div v-if="expend">
              <div class="d-flex">
                {{min}}
                <input
                  type="range"
                  v-model="percent"
                  class="form-control-range"
                  id="formControlRange"
                />
                {{max}}
              </div>
              <div class="btn btn-success w-100">Поставить {{current}}</div>
            </div>
          </div>
        </div>
        <div class="btn btn-danger w-100 mt-2" @click="leave">Покинуть аукцион</div>
      </div>
    </div>
  
  </div>
</template>

<script>
export default {
  props: {
    min: {
      type: Number,
      required: true,
    },
    max: {
      type: Number,
      required: true,
    },
    target_type: {
      type: String,
      required: true,
    },
    target_id: {
      type: Number,
      required: true,
    },
    target_name: {
      type: String,
      required: true,
    },
    canBet: {
      type: Boolean,
      required: true,
    },
    player_id: {
      type: Number,
      required: true,
    },
    max_bet_player: {
      type: Object,
      required: true,
    },
    is_finished:{
      type:Boolean,
      default:false
    }
  },
  data() {
    return {
      percent: 0,
      current: this.min,
      step: (this.max - this.min) / 100,
      expend: false,
    };
  },
  methods: {
    makeBet(valueBet = this.current) {
      this.$axios.post(
        `/auction/bet/player_id=${this.player_id}&cost=${valueBet}`
      );
    },
    leave() {
      this.$axios.post(`/auction/leave?player_id=${player_id}`);
    },
    takePartCost(percent) {
      return this.min * percent;
    },
    toggleExpend() {
      this.expend = !this.expend;
    },
    betPercent(percent) {
      let valueBet = this.takePartCost(percent);
      this.makeBet(valueBet);
    },
    async finish(){
      let result=this.$axios.post(`/auction/leave?player_id=${player_id}`);
      this.emit("finish",result)
    }
  },
  watch: {
    percent(newPersent, oldValue) {
      this.current = Math.ceil(this.min + this.step * this.percent);
    },
  },
};
</script>

<style scoped>
.rotate-180 {
  transform: rotateZ(180deg);
}
</style>