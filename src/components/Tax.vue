<template>
  <div class="bg-dark text-light p-5 m-5 w-100">
    <div>
      <h1 class="h1 lead">{{tax.name}}</h1>Уровень налога
      <div v-if="taxStatus" class="d-flex flex-column lead">
        <div
          class="d-flex justify-content-between"
          :class="{'bg-warning  text-dark':taxStatus.count==1}"
        >
          <div>Один дом</div>
          <div>{{tax.tax1}}</div>
        </div>
        <div
          class="d-flex justify-content-between"
          :class="{'bg-warning  text-dark':taxStatus.count==2}"
        >
          <div>Два дома</div>
          <div>{{tax.tax2}}</div>
        </div>
        <div
          class="d-flex justify-content-between"
          :class="{'bg-warning  text-dark':taxStatus.count==3}"
        >
          <div>Три дома</div>
          <div>{{tax.tax3}}</div>
        </div>
        <div
          class="d-flex justify-content-between"
          :class="{'bg-warning  text-dark':taxStatus.count==4}"
        >
          <div>Четыре дома</div>
          <div>{{tax.tax4}}</div>
        </div>
        <div v-if="!is_readonly">
          <div v-if="!isBought">
            <div class="btn btn-primary w-100" @click="buy">Купить</div>
            <button-start-action :type_id="this.$estateTypes['tax']" :id="+tax.id"></button-start-action>
          </div>
          <div v-else-if="isEnemyTax">
            <div class="btn btn-danger w-100" @click="payRent">Заплатить ренту</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { estateTypes } from "../js/config";
import ButtonStartAction from "./ButtonStartAction.vue";
export default {
  components: {
    ButtonStartAction,
  },
  props: {
    tax: {
      type: Object,
      default: undefined,
    },
    is_readonly: {
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
      taxStatus: undefined,
    };
  },
  computed: {
    isEnemyTax() {
      return +this.taxStatus.player_id !== this.my_player_id;
    },
    color() {
      return this.isEnemyTax ?"bg-danger":"bg-warning";
    },
    isBought() {
      return this.taxStatus && this.taxStatus.player_id;
    },
    isPlayerOwner() {
      return this.my_player_id === this.taxStatus.player_id;
    },
  },
  async created() {
    this.taxStatus = await this.getTaxGameStatus();
  },
  methods: {
    async getTaxGameStatus() {
      let result = await this.$axios.get(
        `/gamestatus/tax/view?id=${this.tax.id}&game_session_id=${this.game_session_id}`
      );
      if (!result) throw new Error("no result getTaxGameStatus");
      return result.data;
    },
    async buy() {
      let result = await this.$axios.post(
        `/common-estate/buy?type_id=${estateTypes["tax"]}&id=${this.tax.id}`
      );
      //if success
      if (result && result.data && result.data.data) {
        this.taxStatus = await this.getTaxGameStatus();
        this.$emit("taxBuy", result);
      } else throw new Error("Can't get getTaxGameStatus request result");
    },
    async payRent() {
      let type = "tax";
      let estate_id = this.tax.id;
      let result = await this.$axios.post(
        "/common-estate/pay-rent?player_to_id=" +
          this.taxStatus.player_id +
          "&&type_id=" +
          this.$estateTypes[type] +
          "&&id=" +
          estate_id
      );
      if (result) {
        this.$emit("payRent", result);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
</style>