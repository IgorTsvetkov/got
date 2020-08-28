<template>
  <div
    v-if="property"
    class="d-flex flex-column shadow p-4 lead bg-dark text-light"
    style="width:300px"
  >
    <div
      class="d-flex flex-column justify-content-between justify-content-center px-2"
      v-if="isBought"
    >
      <div class="d-flex justify-content-center align-items-center bg-primary text-light px-2 py-1">
        <figurine :hero="propertyGameStatus.player.hero"></figurine>
        <span class="mx-1">{{propertyGameStatus.player.user.username}}</span>
      </div>
    </div>
    <div class="h3 font-weight-light p-2 text-center">
      <div class="text-capitalize">{{property.name}}</div>
    </div>
    <div v-for="rent in this.rents" :key="rent.label">
      <property-rent-field
        :class="{'bg-warning text-dark':isActiveRent(rent.fieldName)}"
        class="px-2"
        :label="rent.label"
        :cost="+rent.cost"
      />
    </div>

    <hr />
    <div v-if="!is_action_done&&isPlayerOnCell">
      <button v-if="!isBought" class="btn btn-success text-light w-100 shadow" @click="buy">
        <span class="h4 py-3">Купить {{property.cost}}</span>
      </button>
      <div v-else>
        <div v-if="isPlayerOwner">
          <button
            v-if="!isActiveRent('rent_inn')"
            class="btn btn-success text-light w-100 shadow"
            @click="improve"
          >
            <span class="h4 py-3">Улучшить {{property.homes_inn_cost}}</span>
          </button>
        </div>
        <div else>
          <button class="btn btn-danger text-light w-100 shadow" @click="payRent">
            <span class="h4 py-3">Заплатить ренту</span>
          </button>
        </div>
      </div>
    </div>

    <hr />

    <!-- <p>Если игроку принадлежит <span class="font-weight-bold">все</span> имущество одной цветовой группы, рента <span class="font-weight-bold">удваивается</span></p> -->
  </div>
</template>
<script>
import Figurine from "./Figurine.vue";
import PropertyRentField from "./PropertyRentField.vue";
export default {
  components: {
    Figurine,
    PropertyRentField,
  },
  props: {
    id: {
      type: Number,
      default: undefined,
    },
    is_action_done: {
      type: Boolean,
      default: false,
    },
    myPlayer: {
      type: Object,
      default: undefined,
    },
    position: {
      type: Number,
      default: undefined,
    },
  },
  data() {
    return {
      property: undefined,
      propertyGameStatus: undefined,
      rents: undefined,
    };
  },
  async beforeMount() {
    await this.getProperty(this.id);
  },
  created() {},
  methods: {
    async getProperty(id) {
      let result = await this.$axios.get("/property/view?id=" + id);
      if (result) {
        this.property = result.data;
        this.propertyGameStatus = this.property.propertyGameStatus;
        this.rents = [
          { fieldName: "rent", label: "Рента", cost: this.property.rent },
          {
            fieldName: "rent_home1",
            label: "Рента c 1 домом",
            cost: this.property.rent_home1,
          },
          {
            fieldName: "rent_home2",
            label: "Рента c 2 домами",
            cost: this.property.rent_home2,
          },
          {
            fieldName: "rent_home3",
            label: "Рента c 3 домами",
            cost: this.property.rent_home3,
          },
          {
            fieldName: "rent_home4",
            label: "Рента c 4 домами",
            cost: this.property.rent_home4,
          },
          {
            fieldName: "rent_inn",
            label: "Рента c постоялым двором",
            cost: this.property.rent_inn,
          },
        ];
      }
    },
    async buy() {
      let result = await this.$axios.post(
        "/property-game-status/create?property_id=" + this.id
      );
      if (result) {
        {
          this.$emit("propertyBuy", result);
          await this.getProperty(this.id);
        }
      }
    },
    async improve() {
      let result = await this.$axios.post(
        "/property-game-status/improve?property_id=" + this.id
      );
      if (result) {
        this.$emit("propertyImprove", result);
        await this.getProperty(this.id);
      }
    },
    async payRent() {
      let result = await this.$axios.post(
        "/player/pay-rent"
      );
      if (result) {
        this.$emit("propertyImprove", result);
        await this.getProperty(this.id);
      }
    },
    isActiveRent(name) {
      if (
        this.propertyGameStatus &&
        this.propertyGameStatus.rentState &&
        this.propertyGameStatus.rentState.name == name
      )
        return true;
      return false;
    },
  },
  computed: {
    isBought() {
      return this.propertyGameStatus;
    },
    isPlayerOnCell() {
      return this.position == this.myPlayer.position;
    },
    isPlayerOwner() {
      return this.myPlayer.id === this.propertyGameStatus.player_id;
    },
  },
  watch: {
    async id(newValue, oldValue) {
      this.getProperty(this.id);
    },
  },
};
</script>

<style lang="scss" scoped>
</style>