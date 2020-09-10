<template>
  <div
    v-if="property"
    class="d-flex flex-column shadow p-4 lead bg-dark text-light"
    style="width:300px"
  >
    <div class="d-flex flex-column justify-content-between justify-content-center p-0 m-0">
      <div class="d-flex justify-content-between w-100 m-0 p-2">
        <div class="h3 font-weight-light">
          <div class="text-capitalize m-0 p-0">{{property.name}}</div>
        </div>
        <div v-if="isBought" class="d-flex justify-content-center align-items-center text-light">
          <figurine :hero="propertyGameStatus.player.hero"></figurine>
        </div>
      </div>
    </div>
    <div v-for="rent in this.rents" :key="rent.label">
      <property-rent-field
        :class="{'bg-warning text-dark shadow':isActiveRent(rent.fieldName)}"
        class="px-2"
        :label="rent.label"
        :cost="+rent.cost"
      />
    </div>

    <hr />
    <div v-if="!is_readonly&&isPlayerOnCell">
      <div v-if="!isBought">
        <button class="btn btn-success text-light w-100 shadow mb-1" @click="buy">
          <span class="h4 py-3">Купить {{property.cost}}</span>
        </button>
        <button-start-action type="property" :id="id"></button-start-action>
      </div>
      <div v-else>
        <div v-if="isPlayerOwner">
          <button
            v-if="!isMaxLevel()&&Boolean(propertyGameStatus.is_group_full)"
            class="btn btn-success text-light w-100 shadow"
            @click="improve"
          >
            <span class="h4 py-3">Улучшить {{property.homes_inn_cost}}</span>
          </button>
          <span v-show="!Boolean(propertyGameStatus.is_group_full)" class="small">
            <a>
              Купите
              <span class="font-weight-bold text-warning">все</span> имущество одной цветовой группы, для улучшения
            </a>
          </span>
        </div>
        <div v-if="!isPlayerOwner">
          <button class="btn btn-danger text-light w-100 shadow" @click="payRent">
            <span class="h4 py-3">Заплатить ренту</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Figurine from "./Figurine.vue";
import PropertyRentField from "./PropertyRentField.vue";
import ButtonStartAction from "./ButtonStartAction.vue";
import {estateTypes} from "../js/config";
export default {
  components: {
    Figurine,
    PropertyRentField,
    ButtonStartAction,
  },
  props: {
    id: {
      type: Number,
      default: undefined,
    },
    is_readonly: {
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
  created() {
  },
  methods: {
    async getProperty(id) {
      let result = await this.$axios.get("/property/view?id=" + id);
      if (result) {
        this.property = result.data;
        this.propertyGameStatus = this.property.propertyGameStatus;
        this.rents = [
          {
            level: 1,
            fieldName: "rent",
            label: "Рента",
            cost: this.property.rent,
          },
          {
            level: 2,
            fieldName: "rent_home1",
            label: "Рента c 1 домом",
            cost: this.property.rent_home1,
          },
          {
            level: 3,
            fieldName: "rent_home2",
            label: "Рента c 2 домами",
            cost: this.property.rent_home2,
          },
          {
            level: 4,
            fieldName: "rent_home3",
            label: "Рента c 3 домами",
            cost: this.property.rent_home3,
          },
          {
            level: 5,
            fieldName: "rent_home4",
            label: "Рента c 4 домами",
            cost: this.property.rent_home4,
          },
          {
            level: 6,
            fieldName: "rent_inn",
            label: "Рента c постоялым двором",
            cost: this.property.rent_inn,
          },
        ];
      }
    },
    async buy() {
      let result = await this.$axios.post(`/common-estate/buy?type_id=${estateTypes["property"]}&id=${this.id}`);
      if (result) {
        {
          this.$emit("propertyBuy", result);
          await this.getProperty(this.id);
        }
      }
    },
    async improve() {
      let result = await this.$axios.post(
        "/gamestatus/property/improve?id=" + this.id
      );
      if (result) {
        this.$emit("propertyImprove", result);
        await this.getProperty(this.id);
      }
    },
    async payRent() {
      let type="property";
      let estate_id=this.property.id;
      let result = await this.$axios.post(
        "/common-estate/pay-rent?player_to_id=" +this.propertyGameStatus.player_id 
        +"&&type_id="+this.$estateTypes[type]
        +"&&id=" +estate_id
      );
      if (result) {
        this.$emit("payRent", result);
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
    isMaxLevel() {
      return this.isActiveRent("rent_inn");
    },
  },
  computed: {
    isBought() {
      return !!this.propertyGameStatus;
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