<template>
  <div v-if="property" class="d-flex flex-column shadow p-4 lead bg-warning" style="width:300px">
    <div
      class="d-flex flex-column justify-content-between justify-content-center px-2"
      v-if="isBought"
    >
      <div class="d-flex justify-content-center align-items-center bg-primary text-light px-2 py-1">
        <figurine :hero="property.propertyGameStatuses[0].player.hero"></figurine>
        <span class="mx-1">{{property.propertyGameStatuses[0].player.user.username}}</span>
      </div>
    </div>
    <div class="h3 font-weight-light p-2 text-center">
      <div class="text-capitalize">{{property.name}}</div>
    </div>
    <div class="text-center">Рента:{{property.rent}}</div>

    <div class="d-flex justify-content-between">
      <div>Рента с домом</div>
      <div>{{property.rent_home1}}</div>
    </div>
    <div class="d-flex justify-content-between">
      <div>Рента с 2 домами</div>
      <div>{{property.rent_home2}}</div>
    </div>
    <div class="d-flex justify-content-between">
      <div>Рента с 3 домами</div>
      <div>{{property.rent_home3}}</div>
    </div>
    <div class="d-flex justify-content-between">
      <div>Рента с 4 домами</div>
      <div>{{property.rent_home4}}</div>
    </div>
    <div class="d-flex justify-content-between">
      <div>Рента с постоялым двором</div>
      <div>{{property.rent_inn}}</div>
    </div>
    <hr />
    <div v-if="!isBought">
      <button class="btn btn-success text-light w-100 shadow" @click="buy">
        <span class="h4 py-3">Купить {{property.cost}}</span>
      </button>
    </div>

    <hr />
    <!-- <p>Если игроку принадлежит <span class="font-weight-bold">все</span> имущество одной цветовой группы, рента <span class="font-weight-bold">удваивается</span></p> -->
  </div>
</template>
<script>
import axios from "axios";
import Figurine from "./Figurine.vue";
export default {
  components: {
    Figurine,
  },
  props: {
    id: {
      type: Number,
      default: undefined,
    },
  },
  data() {
    return {
      property: undefined,
    };
  },
  async beforeMount() {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = window.yii.getCsrfToken();
    await this.getProperty(this.id);
  },
  created() {},
  methods: {
    async getProperty(id) {
      let result = await axios.get("/property/view?id=" + id);
      if (result) {
        this.property = result.data;
      }
    },
    async buy() {
      let result = await axios.post(
        "/property-game-status/create?property_id=" + this.id
      );
      if (result) {
        {
          this.$emit("propertyChange",result);
          await this.getProperty(this.id);
        }
      }
    },
  },
  computed: {
    isBought() {
      return this.property.propertyGameStatuses.length > 0;
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