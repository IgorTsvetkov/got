<template v-if="show">
  <div class="bg-secondary text-dark-50 shadow rounded p-3 m-3 w-75">
    <div v-if="is_finished">
      <div v-if="player_id==max_bet_player_id">
        Поздравляю. Вы выйграли аукцион. Итоговая цена {{current}}
        <div class="btn btn-success" @click="finishAndBuy">Заплатить</div>
      </div>
      <div v-else>Аукцион не удался. Ожидание завершения хода...</div>
    </div>
    <div v-else-if="min<max" class="form-group">
      <label
        for="formControlRange"
        class="w-100 text-center lead text-capitalize"
      >Аукцион {{estate_name}}</label>
      <div class="lead">Цена: {{min}}</div>
      <!-- <div class="lead">Величина ставки: {{current}}</div> -->
      <div v-if="canBet">
        <div v-if="!max_bet_player_id">
          <div class="btn btn-success w-100" @click="makeBet()">Поставить {{current}}</div>
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
      <div v-else class="text-center lead bg-warning shadow rounded p-3">Вы не участвуете в аукционе</div>
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
    estate_type_id: {
      type: Number,
      required: true,
    },
    estate_id: {
      type: Number,
      required: true,
    },
    estate_name: {
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
    max_bet_player_id: {
      type: Number,
      default: undefined,
    },
    is_finished: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      percent: 0,
      current: this.min,
      step: (this.max - this.min) / 100,
      expend: false,
      show: true,
    };
  },
  methods: {
    async makeBet(valueBet = this.current) {
      let result=await this.$axios.post(`/auction/bet?cost=${valueBet}`);
      this.$emit("bet",result);
    },
    async leave() {
      let result = await this.$axios.post(
        `/auction/leave?player_id=${this.player_id}`
      );
      this.$emit("leaveAuction", result);
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
    async finishAndBuy() {
      let result =await this.$axios.post(`/auction/buy?player_id=${this.player_id}`);
      this.$emit("finish", result);
    },
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